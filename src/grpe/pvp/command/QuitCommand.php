<?php

declare(strict_types=1);

namespace grpe\pvp\command;

use grpe\pvp\game\GameSession;
use grpe\pvp\Main;
use grpe\pvp\player\PlayerData;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\Player;

/**
 * Class QuitCommand
 * @package grpe\pvp\command
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class QuitCommand extends Command {

    /**
     * QuitCommand constructor.
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
            $playerData = Main::getPlayerDataManager()->getPlayerData($sender);

            if ($playerData instanceof PlayerData) {
                $gameSession = $playerData->getSession();

                if ($gameSession instanceof GameSession) {
                    $gameSession->removePlayer($sender);
                    return true;
                }
            }
        }

        return false;
    }
}