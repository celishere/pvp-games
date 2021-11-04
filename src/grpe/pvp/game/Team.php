<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\Player;

use pocketmine\level\Location;

/**
 * Class Team
 *
 * @package grpe\pvp\game
 * @author celis <celispost@icloud.com>
 *
 * @version 1.0.1
 * @since   1.0.0
 */
class Team {

    /** @var Player[] */
    private array $players = [];

    private int $id;

    private array $playerSpawns = [];
    private array $spawns;

    /**
     * @param int   $teamId
     * @param array $spawns
     */
    public function __construct(int $teamId, array $spawns) {
        $this->id = $teamId;
        $this->spawns = $spawns;
    }

    /**
     * @param Player $player
     */
    public function addPlayer(Player $player): void {
        $this->players[$player->getId()] = $player;
        $this->playerSpawns[$player->getId()] = $this->pickSpawn();
    }

    /**
     * @param Player $player
     */
    public function removePlayer(Player $player): void {
        unset($this->players[$player->getId()]);
    }

    /**
     * @param int $id
     *
     * @return Player|null
     */
    public function findPlayer(int $id): ?Player {
        return $this->players[$id] ?? null;
    }

    /**
     * @return Player[]
     */
    public function getPlayers(): array {
        return $this->players;
    }

    /**
     * @param Player $player
     *
     * @return Location|null
     */
    public function getPlayerSpawn(Player $player): ?Location {
        return $this->playerSpawns[$player->getId()] ?? null;
    }

    /**
     * @return Location|null
     */
    public function pickSpawn(): ?Location {
        return !empty($this->spawns) ? array_pop($this->spawns) : null;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }
}