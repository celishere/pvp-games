<?php

declare(strict_types=1);

namespace grpe\pvp\game\stages;

use grpe\pvp\game\Stage;
use grpe\pvp\game\GameSession;

use pocketmine\utils\TextFormat;

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
        $this->setTime($session->getData()->getCountdown() + 1);

        parent::__construct($session);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return Stage::COUNTDOWN;
    }

    public function onTick(): void {
        $session = $this->getSession();

        $this->setTime($this->getTime() - 1);

        if ($this->getTime() > 0) {
            if ($session->getPlayersCount() >= $session->getData()->getMaxPlayers()) {
                foreach ($session->getPlayers() as $player) {
                    $player->sendPopup(TextFormat::colorize('&aИгра начнется через &e' . $this->getTime() . ' &aс.'));
                }
            } else {
                $session->setStage(Stage::WAITING);
            }
        } else {
            $session->setStage(Stage::RUNNING);
        }
    }
}