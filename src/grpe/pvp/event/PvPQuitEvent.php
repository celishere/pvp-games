<?php

namespace grpe\pvp\event;

use grpe\pvp\game\mode\Mode;
use grpe\pvp\game\mode\FFAMode;

use pocketmine\Player;

use pocketmine\event\Event;

/**
 * Class PvPQuitEvent
 * @package grpe\pvp\event
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class PvPQuitEvent extends Event {

    private Player $player;

    /** @var FFAMode|Mode */
    private $mode;

    /**
     * PvPQuitEvent constructor.
     * @param Player $player
     * @param Mode|FFAMode $mode
     */
    public function __construct(Player $player, $mode) {
        $this->player = $player;
        $this->mode = $mode;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player {
        return $this->player;
    }

    /**
     * @return Mode|FFAMode
     */
    public function getMode() {
        return $this->mode;
    }
}