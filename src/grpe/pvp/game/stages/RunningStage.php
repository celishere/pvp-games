<?php

declare(strict_types=1);

namespace grpe\pvp\game\stages;

use grpe\pvp\game\Stage;
use grpe\pvp\game\GameSession;

use grpe\pvp\utils\Utils;

use grpe\pvp\game\mode\duels\StickDuels;

use pocketmine\utils\TextFormat;

/**
 * Class RunningStage
 * @package grpe\pvp\game\stages
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.1
 * @since   1.0.0
 */
class RunningStage extends Stage {

    /**
     * RunningStage constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        $this->setTime($session->getData()->getGameTime() + 1);

        foreach ($session->getPlayers() as $player) {
            $enemy = implode("&7, &c", $session->getMode()->getOpponent($player));

            Utils::reset($player);

            $player->getInventory()->setContents($session->getMode()->getItems());
            $player->getArmorInventory()->setContents($session->getMode()->getArmor()); //нет в 1.1

            $player->teleport($session->getMode()->getSpawn($player));

            $player->sendMessage(TextFormat::GREEN. 'Игра началась.');
            $player->sendMessage(TextFormat::colorize(($session->getData()->isTeam() ? 'Оппоненты' : 'Оппонент') . '&7: &c'. $enemy));
        }

        parent::__construct($session);
    }

    /**
     * @return int
     */
    public function getId(): int {
        return Stage::RUNNING;
    }

    public function onTick(): void {
        $session = $this->getSession();

        $this->setTime($this->getTime() - 1);

        if ($this->getTime() > 0) {
            $mode = $session->getMode();
            $message = TextFormat::colorize("&eИгра закончится через &b" . Utils::convertTime($this->getTime()));

            if ($mode instanceof StickDuels) {
                foreach ($mode->getScores() as $teamId => $score) {
                    $message .= TextFormat::colorize("\n&fКоманда &e". $teamId ." &7- &b". $score ." &fочков.");
                }
            }

            foreach ($session->getPlayers() as $player) {
                $player->sendPopup($message);
            }
        } else {
            foreach ($session->getPlayers() as $player) {
                $player->sendTitle(TextFormat::YELLOW . "Ничья!");
                
                Utils::reset($player);
            }

            $session->setStage(Stage::ENDING);
        }
    }
}