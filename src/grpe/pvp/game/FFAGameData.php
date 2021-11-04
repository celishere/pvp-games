<?php

declare(strict_types=1);

namespace grpe\pvp\game;

/**
 * Class FFAGameData
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.1
 * @since   1.0.0
 */
final class FFAGameData {

    private string $name;
    private string $mode;
    private string $world;

    private string $platform;

    private array $spawns;

    /**
     * FFAGameData constructor.
     *
     * @param string $name
     * @param string $mode
     * @param string $world
     * @param string $platform
     * @param array  $spawns
     */
    public function __construct(
        string $name,
        string $mode,
        string $world,

        string $platform,

        array $spawns
    ) {
        $this->name = $name;
        $this->mode = $mode;
        $this->world = $world;

        $this->platform = $platform;

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
     * @return array
     */
    public function getSpawns(): array {
        return $this->spawns;
    }
}