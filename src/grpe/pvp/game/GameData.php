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
    private string $platform;

    private bool $team;

    private int $countdown;
    private int $maxPlayers;
    private int $minPlayers;

    private Vector3 $waitingRoom;
    private Vector3 $pos1;
    private Vector3 $pos2;

    /**
     * GameData constructor.
     * @param string $name
     * @param string $mode
     * @param string $world
     * @param bool $team
     * @param string $platform
     * @param int $countdown
     * @param int $maxPlayers
     * @param int $minPlayers
     * @param Vector3 $waitingRoom
     * @param Vector3 $pos1
     * @param Vector3 $pos2
     */
    public function __construct(string $name, string $mode, string $world, bool $team, string $platform, int $countdown, int $maxPlayers, int $minPlayers, Vector3 $waitingRoom, Vector3 $pos1, Vector3 $pos2) {
        $this->name = $name;
        $this->mode = $mode;
        $this->world = $world;

        $this->team = $team;

        $this->platform = $platform;

        $this->countdown = $countdown;

        $this->minPlayers = $minPlayers;
        $this->maxPlayers = $maxPlayers;

        $this->waitingRoom = $waitingRoom;

        $this->pos1 = $pos1;
        $this->pos2 = $pos2;
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
     * @return string
     */
    public function getPlatform(): string {
        return $this->platform;
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

    /**
     * @return Vector3
     */
    public function getPos1(): Vector3 {
        return $this->pos1;
    }

    /**
     * @return Vector3
     */
    public function getPos2(): Vector3 {
        return $this->pos2;
    }
}