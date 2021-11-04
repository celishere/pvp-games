<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\ffa;

use pocketmine\item\Item;

/**
 * Class ResistanceFFA
 * @package grpe\pvp\game\mode\ffa
 *
 * @version 1.0.1
 * @since   1.0.0
 */
class ResistanceFFA extends ClassicFFA {

    /**
     * @return array
     */
    public function getItems(): array {
        return [1 => Item::get(Item::STEAK, 0, 64)]; //2 слот
    }
}