<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use grpe\pvp\Main;
use grpe\pvp\game\stage\Stage;
use grpe\pvp\game\task\GameSessionTask;

use pocketmine\Player;
use pocketmine\scheduler\TaskHandler;

/**
 * Class GameManager
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
final class GameSession {

    /** @var Player[] */
    protected array $players = [];
    protected array $stages  = [];

    protected TaskHandler $sessionTask;
    protected GameData $data;
    protected Stage $stage;

    public const WAITING_STAGE = 0;
    public const COUNTDOWN_STAGE = 1;
    public const RUNNING_STAGE = 2;
    public const ENDING_STAGE = 3;

    /**
     * Game constructor.
     * @param GameData $gameData
     */
    public function __construct(GameData $gameData) {
        $this->data = $gameData;

        $this->sessionTask = Main::getInstance()->getScheduler()->scheduleRepeatingTask(new GameSessionTask($this), 20);
    }

    /**
     * @return GameData
     */
    public function getData(): GameData {
        return $this->data;
    }

    /**
     * @param int $stage
     */
    public function setStage(int $stage): void {
        $this->stage = $this->getStageById($stage);
    }

    /**
     * @return Stage
     */
    public function getStage(): Stage {
        return $this->stage;
    }

    /**
     * @param int $id
     *
     * @return Stage
     */
    public function getStageById(int $id): Stage {
        return $this->stages[$id];
    }

    /**
     * @return Player[]
     */
    public function getPlayers(): array {
        return $this->players;
    }

    public function tick(): void {
        //stage->tick()
    }
}