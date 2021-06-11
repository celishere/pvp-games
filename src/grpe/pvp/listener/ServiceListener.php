<?php

declare(strict_types=1);

namespace grpe\pvp\listener;

use grpe\pvp\Main;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

/**
 * Class ServiceListener
 * @package grpe\pvp\listener
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class ServiceListener implements Listener {

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        Main::getSessionManager()->createSession($player);
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();

        Main::getSessionManager()->removeSession($player);
    }
}