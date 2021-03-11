<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\Player;

/**
 * Class GameManager
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
final class GameManager {

    /** @var GameSession[] */
    private array $games = [];

    /**
     * @param GameData $gameData
     */
    public function addGame(GameData $gameData): void {
        $this->games[spl_object_id($gameData)] = new GameSession($gameData);
    }

    /**
     * @param GameData $gameData
     */
    public function killGame(GameData $gameData): void {
        unset($this->games[spl_object_id($gameData)]);
    }

    /**
     * @return GameSession[]
     */
    public function getGames(): array {
        return $this->games;
    }

    /**
     * @param Player $player
     * @return GameSession|null
     */
    public function getPlayerSession(Player $player): ?GameSession {
        $name = $player->getLowerCaseName();

        foreach ($this->games as $gameSession) {
            if (isset($gameSession->getPlayers()[$name])) {
                return $gameSession;
            }
        }

        return null;
    }

    /**
     * @param string $mode
     * @return GameSession|null
     */
    public function findGameByMode(string $mode): ?GameSession {
        foreach ($this->games as $game) {
            $data = $game->getData();

            if ($data->getMode() === $mode) {
                $stageId = $game->getStage()->getId();

                if ($stageId === GameSession::WAITING_STAGE or $stageId === GameSession::COUNTDOWN_STAGE) {
                    if ($game->getPlayersCount() < $data->getMaxPlayers()) {
                        return $game;
                    }
                }
            }
        }

        return null;
    }
}