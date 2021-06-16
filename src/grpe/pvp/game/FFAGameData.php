<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\math\Vector3;

/**
 * Class FFAGameData
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
final class FFAGameData {

    private string $name;
    private string $mode;
    private string $world;

    private Vector3 $pos1;
    private Vector3 $pos2;

    /**
     * FFAGameData constructor.
     * @param string $name
     * @param string $mode
     * @param string $world
     * @param Vector3 $pos1
     * @param Vector3 $pos2
     */
    public function __construct(string $name, string $mode, string $world, Vector3 $pos1, Vector3 $pos2) {
        $this->name = $name;
        $this->mode = $mode;
        $this->world = $world;

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

    /**
     * @return string
     */
    public function getWorld(): string {
        return $this->world;
    }
}