<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\modes;

use grpe\pvp\game\mode\BasicFFA;

use grpe\pvp\Main;
use grpe\pvp\player\PlayerData;

use pocketmine\Player;

/**
 * Class ClassicFFA
 * @package grpe\pvp\game\mode\modes
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class ClassicFFA extends BasicFFA {

    /**
     * @return array
     */
    public function getItems(): array {
        return [];
    }

    /**
     * @param Player $player
     */
    public function respawnPlayer(Player $player): void {
        $player->teleport($this->getPos());
    }

    public function tick(): void {
        foreach ($this->getSession()->getPlayers() as $player) {
            $playerData = Main::getPlayerDataManager()->getPlayerData($player);

            if ($playerData instanceof PlayerData) {
                $kills = $playerData->getKills();
                $deaths = $playerData->getDeaths();
                $ks = $playerData->getKillStreak();
                $kd = $playerData->getKillDeath();
                $ms = $playerData->getMaxKillStreak();

                $player->sendPopup("Убийств: $kills | Смертей: $deaths | K/S: $ks | K/D: $kd | Max K/S: ". $ms);
            }
        }
    }
}