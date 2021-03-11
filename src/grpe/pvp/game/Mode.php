<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\Player;
use pocketmine\math\Vector3;

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

    protected array $teams = [0 => [], 1 => []];

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
     * @param Player $player
     * @return Vector3
     */
    abstract public function getPos(Player $player): Vector3;

    /**
     * @param int $stageId
     */
    abstract public function onStageChange(int $stageId): void;

    /**
     * @return array
     */
    abstract public function getTeams(): array;

    /**
     * @param Player $player
     * @return int|null
     */
    abstract public function getPlayerTeam(Player $player): ?int;
}