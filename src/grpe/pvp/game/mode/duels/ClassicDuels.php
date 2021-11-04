<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\duels;

use grpe\pvp\game\GameSession;

use grpe\pvp\game\mode\BasicDuels;

use pocketmine\item\Item as I;

/**
 * Class ClassicDuels
 * @package grpe\pvp\game\mode\duels
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.1
 * @since   1.0.0
 */
class ClassicDuels extends BasicDuels {

    /**
     * ClassicDuels constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        parent::__construct($session);
    }

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