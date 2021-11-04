<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\ffa;

use grpe\pvp\game\mode\BasicFFA;

use pocketmine\item\Item;

/**
 * Class FistFFA
 * @package grpe\pvp\game\mode\ffa
 *
 * @version 1.0.1
 * @since   1.0.0
 */
class FistFFA extends BasicFFA {

    /**
     * @return array
     */
    public function getItems(): array {
        return [Item::get(Item::STEAK, 0, 64)];
    }
}