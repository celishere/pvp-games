<?php

declare(strict_types=1);

namespace grpe\pvp\player;

use pocketmine\Player;

/**
 * Class PlayerDataManager
 * @package grpe\pvp\player
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class PlayerDataManager {

    /** @var PlayerData[] */
    private array $playersData = [];

    /**
     * @param Player $player
     * @return PlayerData
     */
    public function registerPlayer(Player $player): PlayerData {
        return $this->playersData[$player->getLowerCaseName()] = new PlayerData();
    }

    /**
     * @param Player $player
     */
    public function unregisterPlayer(Player $player): void {
        //save to db

        unset($this->playersData[$player->getLowerCaseName()]);
    }

    /**
     * @param Player $player
     * @return PlayerData|null
     */
    public function getPlayerData(Player $player): ?PlayerData {
        return $this->playersData[$player->getLowerCaseName()] ?? null;
    }
}