<?php

declare(strict_types=1);

namespace grpe\pvp\game\stages;

use grpe\pvp\game\Stage;
use grpe\pvp\game\GameSession;

/**
 * Class CountdownStage
 * @package grpe\pvp\game\stages
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class CountdownStage extends Stage {

    /**
     * CountdownStage constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        $this->setTime($session->getData()->getCountdown());

        parent::__construct($session);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return GameSession::COUNTDOWN_STAGE;
    }

    public function onTick(): void {
        $session = $this->getSession();

        if ($this->getTime() > 1) {
            if ($session->getPlayersCount() >= $session->getData()->getMinPlayers()) {
                $this->setTime($this->getTime() - 1);

                foreach ($session->getPlayers() as $player) {
                    $player->sendPopup('Time: ' . $this->getTime());
                }
            } else {
                $session->setStage(GameSession::WAITING_STAGE);
            }
        } else {
            $session->setStage(GameSession::RUNNING_STAGE);
        }
    }
}