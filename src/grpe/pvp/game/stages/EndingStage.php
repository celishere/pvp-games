<?php

declare(strict_types=1);

namespace grpe\pvp\game\stages;

use grpe\pvp\game\Stage;
use grpe\pvp\game\GameSession;

use grpe\pvp\utils\Utils;

use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

/**
 * Class EndingStage
 * @package grpe\pvp\game\stages
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.1
 * @since   1.0.0
 */
class EndingStage extends Stage {

    /**
     * EndingStage constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        $this->setTime(11);

        foreach ($session->getPlayers() as $player) {
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();

            $player->getInventory()->setItem(8, Utils::createNamedTagItem(Item::get(Item::BED, 14), 'Выход', 'quit'));
        }

        parent::__construct($session);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return Stage::ENDING;
    }

    public function onTick(): void {
        $session = $this->getSession();
        
        $this->setTime($this->getTime() - 1);

        if ($this->getTime() > 0) {
            foreach ($session->getPlayers() as $player) {
                $player->sendPopup(TextFormat::colorize("&aИгра перезапуститься через &e". $this->getTime() ." &aс."));
            }
        } else {
            $session->reset();
        }
    }
}