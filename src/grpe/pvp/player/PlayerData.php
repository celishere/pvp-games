<?php

declare(strict_types=1);

namespace grpe\pvp\player;

use grpe\pvp\game\GameSession;

/**
 * Class PlayerData
 * @package grpe\pvp\player
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class PlayerData {

    private ?GameSession $gameSession = null;

    private int $kills = 0;

    /**
     * @param GameSession $gameSession
     */
    public function setSession(GameSession $gameSession): void {
        $this->gameSession = $gameSession;
    }

    /**
     * @return GameSession
     */
    public function getSession(): GameSession {
        return $this->gameSession;
    }

    /**
     * @return int
     */
    public function getKills(): int {
        return $this->kills;
    }
}