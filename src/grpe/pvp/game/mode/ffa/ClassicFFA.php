<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\ffa;

use grpe\pvp\game\mode\BasicFFA;

use pocketmine\item\Item as I;

/**
 * Class ClassicFFA
 * @package grpe\pvp\game\mode\ffa
 *
 * @version 1.0.1
 * @since   1.0.0
 */
class ClassicFFA extends BasicFFA {

    /**
     * @return array
     */
    public function getItems(): array {
        return [I::get(I::IRON_SWORD), I::get(I::BOW), I::get(I::ARROW, 0, 32)];
    }

    /**
     * @return array
     */
    public function getArmor(): array {
        return [I::get(I::IRON_HELMET), I::get(I::IRON_CHESTPLATE), I::get(I::IRON_LEGGINGS), I::get(I::IRON_BOOTS)];
    }
}