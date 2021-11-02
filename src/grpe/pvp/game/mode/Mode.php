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
     * @return Team[]
     */
    public function getTeams(): array {
        return $this->teams;
    }

    /**
     * @param Player $player
     *
     * @return Team|null
     */
    public function getPlayerTeam(Player $player): ?Team {
        foreach ($this->getTeams() as $team) {
            if ($team->getPlayerID($player) != null) {
                return $team;
            }
        }

        return null;
    }

    /**
     * @param int $id
     *
     * @return Team|null
     */
    public function getTeam(int $id): ?Team {
        return $this->teams[$id];
    }

    /**
     * @param Player $player
     * @return Player[]
     */
    public function getOpponent(Player $player): array {
        $team = $this->getPlayerTeam($player);

        if ($team != null) {
            $opponentId = $team->getId() === 2 ? 1 : 2;
            $opponentTeam = $this->getTeam($opponentId);

            if ($opponentTeam != null) {
                return array_map(function ($player): string {
                    return $player->getName();
                }, $opponentTeam->getPlayers());
            }
        }

        return [];
    }

    /**
     * @param Player $player
     * @return Vector3
     */
    public function getPos(Player $player): Vector3 {
        $data = $this->getSession()->getData();
        $team = $this->getPlayerTeam($player);

        if ($team != null and $team->getId() === 2) {
            return $data->getPos2();
        }

        return $data->getPos1();
    }

    /**
     * @param int $stageId
     */
    abstract public function onStageChange(int $stageId): void;

    /**
     * @param Team|null $team
     */
    public function checkTeam(?Team $team): void {
        if ($team != null) {
            if (count($team->getPlayers()) === 0) {
                unset($this->teams[$team->getId()]);
            }
        }
    }

    /**
     * @return Team
     */
    public function pickTeam(): Team {
        $selectedTeam = null;

        foreach ($this->getTeams() as $team) {
            if ($selectedTeam === null) {
                $selectedTeam = $team;
                continue;
            }

            if (count($team->getPlayers()) < count($selectedTeam->getPlayers())) {
                $selectedTeam = $team;
            }
        }

        return $selectedTeam;
    }
}