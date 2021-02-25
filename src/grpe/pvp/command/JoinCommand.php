<?php

declare(strict_types=1);

namespace grpe\pvp\command;

use grpe\pvp\game\GameSession;
use grpe\pvp\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class JoinCommand
 * @package grpe\pvp\command
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class JoinCommand extends Command {

    /**
     * JoinCommand constructor.
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
            $gameSession = Main::getGameManager()->findGameByMode('test');

            if ($gameSession instanceof GameSession) {
                $gameSession->addPlayer($sender);
                return true;
            }

            $sender->sendMessage(TextFormat::RED. 'Не удалось найти игру.');
        }
        return false;
    }
}