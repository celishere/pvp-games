<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\item\Item;
use pocketmine\math\Vector3;

/**
 * Class FFAMode
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
abstract class FFAMode {

    protected array $kills = [];

    /**
     * @return GameSession
     */
    abstract public function getSession(): GameSession;

    /**
     * @return Vector3
     */
    abstract public function getPos(): Vector3;

    /**
     * @return Item[]
     */
    abstract public function getItems(): array;
}