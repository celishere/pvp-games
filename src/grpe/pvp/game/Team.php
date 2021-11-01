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
     *
     * @return int
     */
    public function addPlayer(Player $player): int {
        $this->players[$id = $this->nextPlayerID()] = $player;

        return $id;
    }

    /**
     * @param Player $player
     */
    public function removePlayer(Player $player): void {
        $id = $this->getPlayerID($player);

        if ($id != null) {
            unset($this->players[$id]);
        }
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
     * @param Player $player
     *
     * @return int|null
     */
    public function getPlayerID(Player $player): ?int {
        foreach ($this->players as $id => $playerCache) {
            if ($player->getId() === $playerCache->getId()) {
                return $id;
            }
        }

        return null;
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

    /**
     * @return int
     */
    private function nextPlayerID(): int {
        return count($this->players) + 1;
    }
}