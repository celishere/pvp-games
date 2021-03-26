<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\GameSession;

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
     * @return array|array[]
     */
    public function getTeams(): array {
        return $this->teams;
    }

    /**
     * @param Player $player
     * @return int|null
     */
    public function getPlayerTeam(Player $player): ?int {
        for ($id = 0; $id < 2; $id++) {
            if (isset($this->teams[$id][$player->getLowerCaseName()])) {
                return $id;
            }
        }

        return null;
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
            $maxSlots = $this->getSession()->getData()->isTeam() ? 2 : 1;

            foreach ($this->getSession()->getPlayers() as $player) {
                for ($id = 0; $id < 2; $id++) {
                    if (count($this->teams[$id]) < $maxSlots) {
                        $this->teams[$id][$player->getLowerCaseName()] = $player;
                        break;
                    }
                }
            }
        }
    }
}