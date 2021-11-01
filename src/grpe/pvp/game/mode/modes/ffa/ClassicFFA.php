<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\modes\ffa;

use grpe\pvp\Main;

use grpe\pvp\player\PlayerData;

use grpe\pvp\game\mode\BasicFFA;

use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class ClassicFFA
 * @package grpe\pvp\game\mode\modes\ffa
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class ClassicFFA extends BasicFFA {

    /**
     * @param Player $player
     */
    public function respawnPlayer(Player $player): void {
        $player->getInventory()->setContents($this->getItems());
        $player->getArmorInventory()->setContents($this->getArmor()); //нет в 1.1

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
                $maxKs = $playerData->getMaxKillStreak();

                $player->sendPopup(TextFormat::colorize("&fУбийств: &c$kills &8| &fСмертей: &e$deaths &8| &fK/S: &b$ks &8| &fK/D: &a$kd &8| &fMax K/S: &2". $maxKs));
            }
        }
    }
}