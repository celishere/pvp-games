<?php

namespace grpe\pvp\event;

use grpe\pvp\game\Mode;

use pocketmine\Player;

use pocketmine\event\Event;

/**
 * Class PvPJoinEvent
 * @package grpe\pvp\event
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class PvPJoinEvent extends Event {

    private Player $player;
    private Mode $mode;

    /**
     * PvPJoinEvent constructor.
     * @param Player $player
     * @param Mode $mode
     */
    public function __construct(Player $player, Mode $mode) {
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
     * @return Mode
     */
    public function getMode(): Mode {
        return $this->mode;
    }
}