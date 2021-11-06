<?php

declare(strict_types=1);

namespace grpe\pvp\player;

use grpe\pvp\Main;
use grpe\pvp\game\GameSession;

use pocketmine\Player;

/**
 * Class PlayerData
 * @package grpe\pvp\player
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.2
 * @since   1.0.0
 */
class PlayerData {

    private Player $owner;

    private ?GameSession $gameSession = null;

    private int $kills = 0;
    private int $deaths = 0;

    private int $kill_streak = 0;
    private int $max_kill_streak = 0;

    /**
     * PlayerData constructor.
     * @param Player $owner
     */
    public function __construct(Player $owner) {
        $this->owner = $owner;
    }

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

    public function addKill(): void {
        $this->kills++;
        $this->kill_streak++;

        if ($this->kill_streak > $this->max_kill_streak) {
            $this->max_kill_streak = $this->kill_streak;
        }
    }

    /**
     * @return int
     */
    public function getKills(): int {
        return $this->kills;
    }

    public function addDeath(): void {
        $this->deaths++;

        $this->kill_streak = 0;
    }

    /**
     * @return int
     */
    public function getDeaths(): int {
        return $this->deaths;
    }

    /**
     * @return int
     */
    public function getKillStreak(): int {
        return $this->kill_streak;
    }

    /**
     * @return int
     */
    public function getMaxKillStreak(): int {
        return $this->max_kill_streak;
    }

    /**
     * @return string
     */
    public function getKillDeath(): string {
        $kd = (float) $this->kills;

        if ($this->deaths > 0) {
            $kd = $kd - $this->deaths;
        }

        return number_format($kd, 2);
    }

    public function save(): void {
        $session = Main::getSessionManager()->getSession($this->owner);

        $session->addKills($this->kills);
        $session->addDeath($this->deaths);
    }
}