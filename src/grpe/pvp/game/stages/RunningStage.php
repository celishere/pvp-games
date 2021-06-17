<?php

declare(strict_types=1);

namespace grpe\pvp\game\stages;

use grpe\pvp\game\Stage;
use grpe\pvp\game\GameSession;

use grpe\pvp\game\mode\modes\duels\StickDuels;

use pocketmine\utils\TextFormat;

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
        $this->setTime($session->getData()->getGameTime());

        foreach ($session->getPlayers() as $player) {
            $enemy = implode("&7, &c", $session->getMode()->getOpponent($player));

            $player->teleport($session->getMode()->getPos($player));

            $player->sendMessage(TextFormat::GREEN. 'Игра началась.');
            $player->sendMessage(TextFormat::colorize('Оппонент&7: &c'. $enemy));
        }

        parent::__construct($session);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return GameSession::RUNNING_STAGE;
    }

    public function onTick(): void {
        $session = $this->getSession();

        if ($this->getTime() > 1) {
            $mode = $session->getMode();
            $message = "Time: ". $this->getTime();

            if ($mode instanceof StickDuels) {
                foreach ($mode->getScores() as $teamId => $score) {
                    $message .= "\n#". $teamId. " team: " .$score;
                }
            }

            $this->setTime($this->getTime() - 1);

            foreach ($session->getPlayers() as $player) {
                $player->sendPopup($message);
            }
        } else {
            $session->setStage(GameSession::ENDING_STAGE);
        }
    }
}