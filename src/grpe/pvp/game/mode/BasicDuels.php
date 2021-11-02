<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\GameSession;
use grpe\pvp\game\Stage;

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

        $this->initTeams();
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
        if ($stageId === Stage::RUNNING) {
            foreach ($this->getSession()->getPlayers() as $player) {
                $team = $this->pickTeam();
                $team->addPlayer($player);
            }
        }
    }
}