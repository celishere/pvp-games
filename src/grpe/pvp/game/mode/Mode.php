<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\GameSession;
use grpe\pvp\game\Team;

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

    /** @var Team[] */
    protected array $teams = [];

    public function initTeams(): void {
        for ($teamId = 1; $teamId <= 2; $teamId++) {
            $this->teams[$teamId] = new Team($teamId);
        }
    }

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
     * @param array $teamsData
     */
    abstract public function setTeams(array $teamsData): void;

    /**
     * @return Team[]
     */
    abstract public function getTeams(): array;

    /**
     * @param Player $player
     * @return Team|null
     */
    abstract public function getPlayerTeam(Player $player): ?Team;
}