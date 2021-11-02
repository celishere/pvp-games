<?php

declare(strict_types=1);

namespace grpe\pvp\listener;

use grpe\pvp\Main;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\network\mcpe\protocol\LoginPacket;

/**
 * Class ServiceListener
 * @package grpe\pvp\listener
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class ServiceListener implements Listener {

    /** @var int[] */
    private array $osCache = [];

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        $session = Main::getSessionManager()->getSession($player);
        $session->setOsId($this->osCache[$player->getLowerCaseName()] ?? 0);

        unset($this->osCache[$player->getLowerCaseName()]);
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();

        Main::getSessionManager()->removeSession($player);
        unset($this->osCache[$player->getLowerCaseName()]);
    }

    /**
     * @param DataPacketReceiveEvent $event
     */
    public function onPacket(DataPacketReceiveEvent $event): void {
        $packet = $event->getPacket();

        if ($packet instanceof LoginPacket) {
            $this->osCache[strtolower($packet->username)] = $packet->clientData['DeviceOS'] ?? 0;
        }
    }
}