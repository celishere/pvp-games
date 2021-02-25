<?php

declare(strict_types=1);

namespace grpe\pvp\game\task;

use grpe\pvp\game\GameSession;

use pocketmine\scheduler\Task;

/**
 * Class GameSessionTask
 * @package grpe\pvp\game\task
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class GameSessionTask extends Task {

    private GameSession $session;

    /**
     * GameSessionTask constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        $this->session = $session;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick): void {
        $this->session->tick();
    }
}