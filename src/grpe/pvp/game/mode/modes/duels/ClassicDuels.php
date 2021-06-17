<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\modes\duels;

use grpe\pvp\game\mode\BasicDuels;
use grpe\pvp\game\GameSession;

use pocketmine\item\Item as I;

/**
 * Class ClassicDuels
 * @package grpe\pvp\game\mode\modes\duels
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class ClassicDuels extends BasicDuels {

    /**
     * ClassicDuels constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        parent::__construct($session);
    }

    /**
     * @param int $stageId
     */
    public function onStageChange(int $stageId): void {
        if ($stageId === GameSession::RUNNING_STAGE) {
            $contents = [I::get(I::IRON_SWORD), I::get(I::BOW), I::get(I::ARROW, 0, 32)];
            $armor_contents = [I::get(I::IRON_HELMET), I::get(I::IRON_CHESTPLATE), I::get(I::IRON_LEGGINGS), I::get(I::IRON_BOOTS)];

            $maxSlots = $this->getSession()->getData()->isTeam() ? 2 : 1;

            foreach ($this->getSession()->getPlayers() as $player) {
                for ($id = 0; $id < 2; $id++) {
                    if (count($this->teams[$id]) < $maxSlots) {
                        $this->teams[$id][$player->getLowerCaseName()] = $player;
                        break;
                    }
                }

                $player->getInventory()->setContents($contents);
                $player->getArmorInventory()->setContents($armor_contents);
            }
        }
    }
}