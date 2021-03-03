<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\GameSession;
use grpe\pvp\game\TeamMode;

use pocketmine\Player;
use pocketmine\math\Vector3;

/**
 * Class StickDuels
 * @package grpe\pvp\game\mode
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class StickDuels extends TeamMode {

    private GameSession $session;

    private array $teams = [0 => [], 1 => []];
    private array $scores = [0 => 0, 1 => 0];

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
     * @return array|array[]
     */
    public function getTeams(): array {
        return $this->teams;
    }

    /**
     * @return array|array[]
     */
    public function getScores(): array {
        return $this->scores;
    }

    /**
     * @param Player $player
     * @return int|null
     */
    public function getPlayerTeam(Player $player): ?int {
        for ($id = 0; $id < 2; $id++) {
            if (isset($this->teams[$id][$player->getUniqueId()->toString()])) {
                return $id;
            }
        }

        return null;
    }

    /**
     * @param int $teamId
     */
    public function addScore(int $teamId): void {
        $this->scores[$teamId]++;

        if ($this->scores[$teamId] >= 5) {
            $this->getSession()->setStage(GameSession::ENDING_STAGE);
            $this->resetMap();
        }
    }

    /**
     * @param Player $player
     * @return Player[]
     */
    public function getOpponent(Player $player): array {
        $opponentId = $this->getPlayerTeam($player) === 1 ? 0 : 1;
        $opponents = [];

        /** @var Player $teamPlayers */
        foreach ($this->teams[$opponentId] as $teamPlayers) {
            $opponents[] = $teamPlayers->getName();
        }

        return $opponents;
    }

    /**
     * @param Player $player
     * @return Vector3
     */
    public function getPos(Player $player): Vector3 {
        $data = $this->getSession()->getData();
        $pos1 = $data->getPos1();

        if (($teamId = $this->getPlayerTeam($player)) !== null) {
            if ($teamId === 1) {
                return $data->getPos2();
            }
        }

        return $pos1;
    }

    /**
     * @param int $stageId
     */
    public function onStageChange(int $stageId): void {
        if ($stageId === GameSession::RUNNING_STAGE) {
            $maxSlots = $this->getSession()->getData()->isTeam() ? 2 : 1;

            foreach ($this->getSession()->getPlayers() as $player) {
                for ($id = 0; $id < 2; $id++) {
                    if (count($this->teams[$id]) < $maxSlots) {
                        $this->teams[$id][$player->getUniqueId()->toString()] = $player;
                        break;
                    }
                }
            }
        }
    }

    public function resetMap(): void {
        $session = $this->getSession();

        $session->getLevel()->unloadChunks(true);

        foreach ($session->getLevel()->getChunks() as $chunk) {
            $chunk->onUnload();
        }

        foreach ($session->getPlayers() as $player) {
            $player->teleport($session->getData()->getWaitingRoom());
        }
    }
}