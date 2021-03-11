<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\BasicDuels;
use grpe\pvp\game\Mode;
use grpe\pvp\game\GameSession;

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
class StickDuels extends BasicDuels {

    private array $scores = [0 => 0, 1 => 0];

    /**
     * StickDuels constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        parent::__construct($session);
    }

    /**
     * @return array|int[]
     */
    public function getScores(): array {
        return $this->scores;
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