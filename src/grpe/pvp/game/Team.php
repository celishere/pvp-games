<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\Player;

/**
 * Class Team
 *
 * @package grpe\pvp\game
 * @author celis <celispost@icloud.com>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class Team {

    /** @var Player[] */
    private array $players = [];

    private int $id;
    private array $spawns = []; //todo

    /**
     * @param int $teamId
     */
    public function __construct(int $teamId) {
        $this->id = $teamId;
    }

    /**
     * @param Player $player
     */
    public function addPlayer(Player $player): void {
        $this->players[$player->getId()] = $player;
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
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }
}