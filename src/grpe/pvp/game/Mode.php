<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\Player;

/**
 * Class Mode
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
abstract class Mode {

    /**
     * @return GameSession
     */
    abstract public function getSession(): GameSession;

    /**
     * @param Player $player
     * @return Player[]
     */
    abstract public function getOpponent(Player $player): array;

    /**
     * @param int $stageId
     */
    abstract public function onStageChange(int $stageId): void;
}