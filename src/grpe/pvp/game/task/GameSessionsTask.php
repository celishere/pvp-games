<?php

declare(strict_types=1);

namespace grpe\pvp\game\task;

use grpe\pvp\game\GameManager;

use pocketmine\scheduler\Task;

/**
 * Class GameSessionsTask
 * @package grpe\pvp\game\task
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class GameSessionsTask extends Task {

    private GameManager $manager;

    /**
     * GameSessionsTask constructor.
     * @param GameManager $gameManager
     */
    public function __construct(GameManager $gameManager) {
        $this->manager = $gameManager;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick): void {
        foreach ($this->manager->getGames() as $game) {
            $game->tick();
        }
    }
}