<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\GameSession;
use grpe\pvp\game\Mode;

/**
 * Class StickDuels
 * @package grpe\pvp\game\mode
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class StickDuels extends Mode {

    private GameSession $session;
    private array $teams = [0 => [], 1 => []];

    /**
     * StickDuels constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        $this->session = $session;
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
    public function onChangeStage(int $stageId): void {
        if ($stageId === GameSession::RUNNING_STAGE) {
            foreach ($this->getSession()->getPlayers() as $player) {
                for ($id = 0; $id < 2; $id++) {
                    if (count($this->teams[$id]) < 2) {
                        $this->teams[$id][] = $player;
                        break;
                    }
                }
            }
        }
    }

    public function resetMap(): void {
        //когда кровать сломали
    }
}