<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use grpe\pvp\game\mode\Mode;
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
     * @param GameData|FFAGameData $gameData
     */
    public function addGame($gameData): void {
        $this->games[spl_object_id($gameData)] = new GameSession($gameData);
    }

    /**
     * @param GameData|FFAGameData $gameData
     */
    public function killGame($gameData): void {
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
     * @param int $platform
     * @return GameSession|null
     */
    public function findGame(string $mode, int $platform = 0): ?GameSession {
        foreach ($this->games as $game) {
            $data = $game->getData();

            if ($data->getMode() === $mode) {
                if ($game->getMode() instanceof Mode) {
                    $stageId = $game->getStage()->getId();

                    if ($stageId === GameSession::WAITING_STAGE or $stageId === GameSession::COUNTDOWN_STAGE) {
                        if ($game->getPlayersCount() < $data->getMaxPlayers()) {
                            if ($game->getPlatform() !== 'all') {
                                if ($game->getPlatform() != $platform) {
                                    break;
                                }
                            }

                            return $game;
                        }
                    }
                } else {
                    return $game;
                }
            }
        }

        return null;
    }

    /**
     * @param string $mode
     * @return int
     */
    public function getOnline(string $mode): int {
        $online = 0;

        foreach ($this->games as $game) {
            $data = $game->getData();

            if ($data->getMode() === $mode) {
                $online += $game->getPlayersCount();
            }
        }

        return $online;
    }
}