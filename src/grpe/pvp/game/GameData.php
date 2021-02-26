<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\math\Vector3;

/**
 * Class GameData
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
final class GameData {

    private string $name;
    private string $mode;
    private string $world;

    private bool $team;

    private int $countdown;
    private int $maxPlayers;
    private int $minPlayers;

    private Vector3 $waitingRoom;

    /**
     * GameData constructor.
     * @param string $name
     * @param string $mode
     * @param string $world
     * @param bool $team
     * @param int $countdown
     * @param int $maxPlayers
     * @param int $minPlayers
     * @param Vector3 $waitingRoom
     */
    public function __construct(string $name, string $mode, string $world, bool $team, int $countdown, int $maxPlayers, int $minPlayers, Vector3 $waitingRoom) {
        $this->name = $name;
        $this->mode = $mode;
        $this->world = $world;

        $this->team = $team;

        $this->countdown = $countdown;

        $this->minPlayers = $minPlayers;
        $this->maxPlayers = $maxPlayers;

        $this->waitingRoom = $waitingRoom;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMode(): string {
        return $this->mode;
    }

    /**
     * @return string
     */
    public function getWorld(): string {
        return $this->world;
    }

    /**
     * @return bool
     */
    public function isTeam(): bool {
        return $this->team;
    }

    /**
     * @return int
     */
    public function getCountdown(): int {
        return $this->countdown;
    }

    /**
     * @return int
     */
    public function getMaxPlayers(): int {
        return $this->maxPlayers;
    }

    /**
     * @return int
     */
    public function getMinPlayers(): int {
        return $this->minPlayers;
    }

    /**
     * @return Vector3
     */
    public function getWaitingRoom(): Vector3 {
        return $this->waitingRoom;
    }
}