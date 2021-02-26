<?php

declare(strict_types=1);

namespace grpe\pvp\game\stages;

use grpe\pvp\game\GameSession;
use grpe\pvp\game\Stage;

/**
 * Class EndingStage
 * @package grpe\pvp\game\stages
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class EndingStage extends Stage {

    /**
     * EndingStage constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        parent::__construct($session);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return GameSession::ENDING_STAGE;
    }

    public function onTick(): void {
        $session = $this->getSession();

        foreach ($session->getPlayers() as $player) {
            $session->removePlayer($player);
        }
    }
}