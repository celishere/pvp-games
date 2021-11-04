<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\ffa;

use grpe\pvp\game\mode\BasicFFA;

use pocketmine\item\Item;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

/**
 * Class GappleFFA
 * @package grpe\pvp\game\mode\ffa
 *
 * @version 1.0.1
 * @since   1.0.0
 */
class GappleFFA extends BasicFFA {

    /**
     * @return array
     */
    public function getArmor(): array {
        $items = [];

        for ($id = Item::DIAMOND_HELMET; $id <= Item::DIAMOND_BOOTS; $id++) {
            $item = Item::get($id);
            $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 3));

            $items[] = $item;
        }

        return $items;
    }

    /**
     * @return array
     */
    public function getItems(): array {
        $weapon = Item::get(Item::DIAMOND_SWORD);
        $weapon->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 2));

        return [$weapon, Item::get(Item::GOLDEN_APPLE, 0, 64)];
    }
}