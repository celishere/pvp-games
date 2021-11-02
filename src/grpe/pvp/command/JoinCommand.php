<?php

declare(strict_types=1);

namespace grpe\pvp\command;

use grpe\pvp\Main;
use grpe\pvp\game\GameSession;

use grpe\pvp\player\PlayerData;
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
            $mode = $args[0] ?? 'sumo';

            $playerSession = Main::getSessionManager()->getSession($sender);
            $gameSession = Main::getGameManager()->findGame($mode, $playerSession->getOsId());

            if ($gameSession instanceof GameSession) {
                $playerData = Main::getPlayerDataManager()->getPlayerData($sender);

                if ($playerData instanceof PlayerData) {
                    $oldSession = $playerData->getSession();

                    if ($oldSession instanceof GameSession) {
                        $oldSession->removePlayer($sender);
                    }
                }

                $gameSession->addPlayer($sender);
                return true;
            }

            $sender->sendMessage(TextFormat::RED. 'Не удалось найти игру.');
        }

        return false;
    }
}