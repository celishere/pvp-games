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
     */
    public function registerPlayer(Player $player): void {
        $this->playersData[$player->getUniqueId()->toString()] = new PlayerData();
    }

    /**
     * @param Player $player
     */
    public function unregisterPlayer(Player $player): void {
        unset($this->playersData[$player->getUniqueId()->toString()]);
    }

    public function getPlayerData(Player $player): ?PlayerData {
        return isset($this->playersData[$player->getUniqueId()->toString()]) ? $this->playersData[$player->getUniqueId()->toString()] : null;
    }

}