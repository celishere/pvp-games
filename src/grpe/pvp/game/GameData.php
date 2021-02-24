<?php

declare(strict_types=1);

namespace grpe\pvp\game;

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

    private int $maxPlayers;
    private int $minPlayers;

    /**
     * GameData constructor.
     * @param string $name
     * @param string $mode
     * @param string $world
     * @param int $maxPlayers
     * @param int $minPlayers
     */
    public function __construct(string $name, string $mode, string $world, int $maxPlayers, int $minPlayers) {
        $this->name = $name;
        $this->mode = $mode;
        $this->world = $world;

        $this->minPlayers = $minPlayers;
        $this->maxPlayers = $maxPlayers;
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
}