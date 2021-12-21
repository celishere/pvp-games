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
 * @version 1.0.1
 * @since   1.0.0
 */
final class GameData {

    /** @var string */
    private $name, $mode, $world, $platform;

    /** @var bool */
    private $team;

    /** @var int  */
    private $countdown, $gameTime, $maxPlayers;

    /** @var Location */
    private $waitingRoom;

    /** @var Location[] */
    private $spawns;

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
     * @param array    $spawns
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

        array $spawns
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

        $this->spawns = $spawns;
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
     * @return array
     */
    public function getSpawns(): array {
        return $this->spawns;
    }
}