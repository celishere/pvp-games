<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\ffa;

use pocketmine\item\Item;

/**
 * Class NodebuffFFA
 * @package grpe\pvp\game\mode\ffa
 *
 * @version 1.0.1
 * @since   1.0.0
 */
class NodebuffFFA extends ClassicFFA {

    /**
     * @return array
     */
    public function getItems(): array {
        $contents = [Item::get(Item::IRON_SWORD), Item::get(Item::ENDER_PEARL, 0, 16)];

        for ($i = 2; $i < 36; $i++) {
            $contents[] = Item::get(Item::SPLASH_POTION, 22);
        }

        return $contents;
    }

    /**
     * @return array
     */
    public function getArmor(): array {
        return [Item::get(Item::DIAMOND_HELMET), Item::get(Item::DIAMOND_CHESTPLATE), Item::get(Item::DIAMOND_LEGGINGS), Item::get(Item::DIAMOND_BOOTS)];
    }
}