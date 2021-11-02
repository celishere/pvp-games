<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\modes\duels;

use grpe\pvp\game\mode\BasicDuels;
use grpe\pvp\game\GameSession;

use grpe\pvp\game\Stage;
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
        parent::onStageChange($stageId);

        if ($stageId === Stage::RUNNING) {
            $contents = [I::get(I::IRON_SWORD), I::get(I::BOW), I::get(I::ARROW, 0, 32)];
            $armor_contents = [I::get(I::IRON_HELMET), I::get(I::IRON_CHESTPLATE), I::get(I::IRON_LEGGINGS), I::get(I::IRON_BOOTS)];

            foreach ($this->getSession()->getPlayers() as $player) {
                $player->getInventory()->setContents($contents);
                $player->getArmorInventory()->setContents($armor_contents);
            }
        }
    }
}