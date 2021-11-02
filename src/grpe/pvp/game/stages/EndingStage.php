<?php

declare(strict_types=1);

namespace grpe\pvp\game\stages;

use grpe\pvp\game\Stage;
use grpe\pvp\game\GameSession;

use pocketmine\utils\TextFormat;

/**
 * Class EndingStage
 * @package grpe\pvp\game\stages
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class EndingStage extends Stage {

    /**
     * EndingStage constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        $this->setTime(11);

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