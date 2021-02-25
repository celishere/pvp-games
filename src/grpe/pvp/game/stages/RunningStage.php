<?php

declare(strict_types=1);

namespace grpe\pvp\game\stages;

use grpe\pvp\game\GameSession;
use grpe\pvp\game\Stage;

/**
 * Class RunningStage
 * @package grpe\pvp\game\stages
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class RunningStage extends Stage {

    /**
     * RunningStage constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        parent::__construct($session);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return GameSession::RUNNING_STAGE;
    }

    public function onTick(): void {
        // TODO: Implement onTick() method.
    }
}