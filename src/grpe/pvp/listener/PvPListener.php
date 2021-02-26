<?php

declare(strict_types=1);

namespace grpe\pvp\listener;

use grpe\pvp\Main;
use grpe\pvp\game\GameSession;
use grpe\pvp\game\mode\StickDuels;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\block\Bed;
use pocketmine\tile\Bed as TileBed;

use pocketmine\Server;

use pocketmine\utils\TextFormat;

/**
 * Class PvPListener
 * @package grpe\pvp\listener
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class PvPListener implements Listener {

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event): void {
        Server::getInstance()->dispatchCommand($event->getPlayer(), 'join');
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event): void {
        Server::getInstance()->dispatchCommand($event->getPlayer(), 'quit');
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        if ($block instanceof Bed) {
            $gameSession = Main::getGameManager()->getPlayerSession($player);

            if ($gameSession instanceof GameSession) {
                $event->setCancelled();
                $mode = $gameSession->getMode();

                if ($mode instanceof StickDuels) {
                    $level = $block->getLevelNonNull(); // $level = $player->getLevel();

                    if ($block->isHeadPart()) {
                        $bed = $level->getTile($block);
                    } else {
                        $bed = $level->getTile($block->getOtherHalf());
                    }

                    /** @var TileBed $bed */
                    $bedTeamId = $bed->getColor();
                    $teamId = $mode->getPlayerTeam($player);

                    if ($teamId === $bedTeamId) {
                        foreach ($gameSession->getPlayers() as $sessionPlayers) {
                            $sessionPlayers->sendMessage(TextFormat::colorize('&a'. $player->getName() .' &fсломал кровать вражеской команды.'));
                        }

                        $mode->addScore($teamId);
                        $mode->resetMap();
                    }
                }
            }
        }
    }
}
