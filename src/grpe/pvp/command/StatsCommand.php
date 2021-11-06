<?php

declare(strict_types=1);

namespace grpe\pvp\command;

use grpe\pvp\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\Player;

/**
 * Class StatsCommand
 * @package grpe\pvp\command
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.2
 * @since   1.0.0
 */
class StatsCommand extends Command {

    /**
     * StatsCommand constructor.
     * @param string $name
     * @param string $description
     */
    public function __construct(string $name, string $description = "") {
        parent::__construct($name, $description, '', []);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            $playerSession = Main::getSessionManager()->getSession($sender);

            $sender->sendMessage("Игр: ". $playerSession->getGames());
            $sender->sendMessage("Побед: ". $playerSession->getWins());
            $sender->sendMessage("Убийств: ". $playerSession->getKills());
            $sender->sendMessage("Смертей: ". $playerSession->getDeath());
            return true;
        }

        return false;
    }
}