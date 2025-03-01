<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\Main;

use grpe\pvp\player\PlayerData;

use grpe\pvp\game\GameSession;

use pocketmine\level\Position;

use pocketmine\utils\TextFormat;

use pocketmine\Player;

/**
 * Class BasicFFA
 * @package grpe\pvp\game
 *
 * @version 1.0.1
 * @since   1.0.0
 */
abstract class BasicFFA extends FFAMode {

    private GameSession $session;

    /**
     * BasicFFA constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        $this->session = $session;
    }

    /**
     * @return Position
     */
    public function getPos(): Position {
        $spawns = $this->getSession()->getData()->getSpawns();

        return $spawns[array_rand($spawns)]->setLevel($this->getSession()->getLevel());
    }

    /**
     * @return GameSession
     */
    public function getSession(): GameSession {
        return $this->session;
    }

    /**
     * @return array
     */
    public function getItems(): array {
        return [];
    }

    /**
     * @return array
     */
    public function getArmor(): array {
        return [];
    }

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

                $player->sendTip(TextFormat::colorize("&fУбийств: &c$kills &8| &fСмертей: &e$deaths &8| &fK/S: &b$ks &8| &fK/D: &a$kd &8| &fMax K/S: &2$maxKs"));
            }
        }
    }
}