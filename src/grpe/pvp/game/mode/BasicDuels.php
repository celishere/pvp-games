<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\GameSession;
use grpe\pvp\game\Team;

use pocketmine\math\Vector3;

use pocketmine\Player;

/**
 * Class BasicDuels
 * @package grpe\pvp\game
 *
 * @version 1.0.0
 * @since   1.0.0
 */
abstract class BasicDuels extends Mode {

    private GameSession $session;

    /**
     * BasicDuels constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        $this->session = $session;
    }

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
                }, $team->getPlayers());
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

        if ($team != null and $team->getId() === 1) {
            return $data->getPos2();
        }

        return $data->getPos1();
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

    /**
     * @return GameSession
     */
    public function getSession(): GameSession {
        return $this->session;
    }

    /**
     * @param int $stageId
     */
    public function onStageChange(int $stageId): void {
        if ($stageId === GameSession::RUNNING_STAGE) {
            foreach ($this->getSession()->getPlayers() as $player) {
                $team = $this->pickTeam();
                $team->addPlayer($player);
            }
        }
    }
}