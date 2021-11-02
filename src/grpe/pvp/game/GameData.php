<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\level\Location;

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
    private int $gameTime;

    private int $maxPlayers;

    private Location $waitingRoom;
    private Location $pos1;
    private Location $pos2;

    /**
     * GameData constructor.
     *
     * @param string   $name
     * @param string   $mode
     * @param string   $world
     * @param bool     $team
     * @param string   $platform
     * @param int      $countdown
     * @param int      $gameTime
     * @param int      $maxPlayers
     * @param Location $waitingRoom
     * @param Location $pos1
     * @param Location $pos2
     */
    public function __construct(
        string $name,
        string $mode,
        string $world,

        bool $team,

        string $platform,

        int $countdown,
        int $gameTime,
        int $maxPlayers,

        Location $waitingRoom,
        Location $pos1,
        Location $pos2
    ) {
        $this->name = $name;
        $this->mode = $mode;
        $this->world = $world;

        $this->team = $team;

        $this->platform = $platform;

        $this->countdown = $countdown;
        $this->gameTime = $gameTime;

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
    public function getGameTime(): int {
        return $this->gameTime;
    }

    /**
     * @return int
     */
    public function getMaxPlayers(): int {
        return $this->maxPlayers;
    }

    /**
     * @return Location
     */
    public function getWaitingRoom(): Location {
        return $this->waitingRoom;
    }

    /**
     * @return Location
     */
    public function getPos1(): Location {
        return $this->pos1;
    }

    /**
     * @return Location
     */
    public function getPos2(): Location {
        return $this->pos2;
    }
}