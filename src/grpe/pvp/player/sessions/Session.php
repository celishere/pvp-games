<?php

declare(strict_types=1);

namespace grpe\pvp\player\sessions;

use pocketmine\Player;

/**
 * Class Session
 * @package grpe\pvp\player\sessions
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class Session {

    private string $username;

    /**
     * Session constructor.
     * @param Player $player
     */
    public function __construct(Player $player) {
        $this->username = $player->getLowerCaseName();
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }
}