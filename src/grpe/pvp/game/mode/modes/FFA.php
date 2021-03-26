<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\modes;

use grpe\pvp\game\mode\BasicFFA;
use pocketmine\Player;

/**
 * Class FFA
 * @package grpe\pvp\game\mode\modes
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class FFA extends BasicFFA {

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
}