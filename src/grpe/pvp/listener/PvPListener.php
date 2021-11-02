<?php

declare(strict_types=1);

namespace grpe\pvp\listener;

use grpe\pvp\game\mode\BasicDuels;
use grpe\pvp\game\Stage;
use grpe\pvp\Main;

use grpe\pvp\game\GameSession;

use grpe\pvp\game\stages\RunningStage;

use grpe\pvp\game\mode\FFAMode;
use grpe\pvp\game\mode\modes\duels\StickDuels;
use grpe\pvp\game\mode\modes\duels\ClassicDuels;
use grpe\pvp\game\mode\modes\duels\SumoDuels;
use grpe\pvp\game\mode\modes\ffa\ResistanceFFA;

use grpe\pvp\player\PlayerData;

use grpe\pvp\utils\Utils;

use pocketmine\block\Bed;
use pocketmine\block\Block;

use pocketmine\event\Event;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\tile\Bed as TileBed;

use pocketmine\event\Listener;

use pocketmine\event\inventory\InventoryTransactionEvent;

use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;

use pocketmine\inventory\PlayerInventory;

use pocketmine\level\particle\DestroyBlockParticle;

use pocketmine\nbt\tag\NamedTag;

use pocketmine\utils\TextFormat;

use pocketmine\Player;
use pocketmine\Server;

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
        $player = $event->getPlayer();

        Utils::reset($player);

        $player->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
        $player->sendMessage(TextFormat::colorize("&aДобро пожаловать!"));

        Server::getInstance()->dispatchCommand($event->getPlayer(), 'join classic');
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event): void {
        Server::getInstance()->dispatchCommand($event->getPlayer(), 'quit');
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event): void {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($item->getNamedTagEntry('quit') instanceof NamedTag) {
            Server::getInstance()->dispatchCommand($player, 'quit');
        }
    }

    /**
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        $gameSession = Main::getGameManager()->getPlayerSession($player);

        if ($gameSession instanceof GameSession) {
            $mode = $gameSession->getMode();

            if ($mode instanceof StickDuels) {
                if (!$mode->isBlockCached($block->getX(), $block->getY(), $block->getZ())) {
                    $mode->addCachedBlock($block->getX(), $block->getY(), $block->getZ(), $block->getId(), $block->getDamage());
                }
            } else {
                $event->setCancelled();
            }
        }
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        $gameSession = Main::getGameManager()->getPlayerSession($player);

        if ($gameSession instanceof GameSession) {
            $mode = $gameSession->getMode();

            if ($mode instanceof StickDuels and $gameSession->getStage()->getId() === Stage::RUNNING) {
                if ($block instanceof Bed) {
                    $event->setCancelled();
                    $level = $block->getLevel();

                    if ($block->isHeadPart()) {
                        $bed = $level->getTile($block);
                    } else {
                        $bed = $level->getTile($block->getOtherHalf());
                    }

                    /** @var TileBed $bed */
                    $bedColorId = $bed->getColor();
                    $team = $mode->getPlayerTeam($player);

                    if ($team != null) {
                        if ($team->getId() === $bedColorId) {
                            foreach ($gameSession->getPlayers() as $sessionPlayers) {
                                $sessionPlayers->sendMessage(TextFormat::colorize('&a' . $player->getName() . ' &fсломал кровать вражеской команды.'));
                            }

                            $mode->addScore($team->getId());
                        }
                    }
                } else {
                    if ($mode->isBlockCached($block->getX(), $block->getY(), $block->getZ())) {
                        $mode->removeCachedBlock($block->getX(), $block->getY(), $block->getZ());
                    } else {
                        $event->setCancelled();
                    }
                }
            } else {
                $event->setCancelled();
            }
        }
    }

    /**
     * @param BlockUpdateEvent $event
     */
    public function onUpdate(BlockUpdateEvent $event): void {
        $event->setCancelled();
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event): void {
        $entity = $event->getEntity();

        if ($entity instanceof Player) {
            $entitySession = Main::getGameManager()->getPlayerSession($entity);

            if ($entitySession instanceof GameSession) {
                $mode = $entitySession->getMode();

                if ($event instanceof EntityDamageByEntityEvent) {
                    $damager = $event->getDamager();

                    if ($damager instanceof Player) {
                        $damagerSession = Main::getGameManager()->getPlayerSession($damager);

                        if ($damagerSession instanceof GameSession) {
                            if ($mode instanceof SumoDuels or $mode instanceof ResistanceFFA) {
                                $entity->setHealth($entity->getMaxHealth());
                                return;
                            }

                            if (!$mode instanceof FFAMode) {
                                $t1 = $mode->getPlayerTeam($damager);
                                $t2 = $mode->getPlayerTeam($entity);

                                if ($t1 != null and $t2 != null) {
                                    if ($t1->getId() === $t2->getId()) {
                                        $event->setCancelled();
                                        return;
                                    }
                                }
                            }

                            if ($mode instanceof ClassicDuels or $mode instanceof FFAMode) {
                                if (($entity->getHealth() - $event->getFinalDamage()) <= 0) {
                                    $event->setCancelled();

                                    $entity->getLevel()->addParticle(new DestroyBlockParticle($entity, Block::get(Block::REDSTONE_BLOCK)));

                                    $damager->sendMessage(TextFormat::RED. 'Kill!');

                                    $deathMessage = '&b'. $entity->getName() . ' &fбыл убит &e'. $damager->getName();
                                    $entitySession->removePlayer($entity, true, $deathMessage);

                                    $manager = Main::getPlayerDataManager();

                                    if (($entitySession = $manager->getPlayerData($entity)) instanceof PlayerData) {
                                        $entitySession->addDeath();
                                    }

                                    if (($damagerSession = $manager->getPlayerData($damager)) instanceof PlayerData) {
                                        $damagerSession->addKill();
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $manager = Main::getPlayerDataManager();

                    if ($mode instanceof SumoDuels) {
                        if ($event->getCause() === EntityDamageEvent::CAUSE_VOID) {
                            $event->setCancelled();

                            if (($entSession = $manager->getPlayerData($entity)) instanceof PlayerData) {
                                $entSession->addDeath();
                            }

                            $entitySession->removePlayer($entity, true);

                            return;
                        }
                    }

                    if ($mode instanceof FFAMode) {
                        $event->setCancelled();
                        return;
                    }
                    
                    if (($entity->getHealth() - $event->getFinalDamage()) <= 0) {
                        $event->setCancelled();

                        if (($entSession = $manager->getPlayerData($entity)) instanceof PlayerData) {
                            $entSession->addDeath();
                        }

                        if ($mode instanceof StickDuels) {
                            $entity->teleport($mode->getPos($entity));
                        } else {
                            if ($mode instanceof BasicDuels) {
                                if ($entitySession->getStage()->getId() !== Stage::RUNNING) {
                                    return;
                                }
                            }

                            $entitySession->removePlayer($entity, true);
                        }
                    }
                }
            } else {
                $event->setCancelled();
            }
        }
    }

    /**
     * @param PlayerDropItemEvent $event
     */
    public function onDrop(PlayerDropItemEvent $event): void {
        $event->setCancelled();
    }

    /**
     * @param PlayerMoveEvent $event
     */
    public function onMove(PlayerMoveEvent $event): void {
        //$event->getPlayer()->sendPopup($event->getPlayer()->asLocation()->__toString());
    }

    /**
     * @param InventoryTransactionEvent $event
     */
    public function onTransaction(InventoryTransactionEvent $event): void {
        $player = null;

        foreach ($event->getTransaction()->getInventories() as $inventory) {
            if ($inventory instanceof PlayerInventory) {
                $player = $inventory->getHolder();
            }
        }

        if ($player instanceof Player) {
            self::checkPlayer($player, $event);
        }
    }

    /**
     * @param PlayerExhaustEvent $event
     */
    public function onExhaust(PlayerExhaustEvent $event): void {
        $player = $event->getPlayer();

        if ($player instanceof Player) {
            self::checkPlayer($player, $event);
        }
    }

    /**
     * @param Player $player
     * @param Event  $event
     */
    private static function checkPlayer(Player $player, Event $event): void {
        $game = Main::getSessionManager()->getSession($player);

        if ($game instanceof GameSession) {
            if (!$game->getStage() instanceof RunningStage) {
                $event->setCancelled();
            } else {
                $mode = $game->getMode();

                if (!$mode instanceof ClassicDuels) {
                    $event->setCancelled();
                }
            }
        } else {
            $event->setCancelled();
        }
    }
}