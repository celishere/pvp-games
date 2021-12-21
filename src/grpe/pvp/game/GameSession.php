<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use grpe\pvp\Main;

use grpe\pvp\game\mode\Mode;
use grpe\pvp\game\mode\FFAMode;
use grpe\pvp\game\mode\BasicDuels;
use grpe\pvp\game\mode\duels\StickDuels;

use grpe\pvp\game\stages\RunningStage;

use grpe\pvp\player\PlayerData;

use grpe\pvp\event\PvPJoinEvent;

use grpe\pvp\utils\TeamData;
use grpe\pvp\utils\Utils;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\item\Item;
use pocketmine\level\Level;

use pocketmine\utils\TextFormat;

/**
 * Class GameManager
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.2
 * @since   1.0.0
 */
final class GameSession {

    /** @var Player[] */
    protected $players = [];

    /** @var Player[] */
    protected $spectators = [];

    /** @var FFAGameData|GameData */
    protected $data;
    /** @var Stage */
    protected $stage;

    /** @var FFAMode|Mode */
    protected $mode;

    /**
     * Game constructor.
     * @param GameData|FFAGameData $gameData
     */
    public function __construct($gameData) {
        $this->data = $gameData;
        $this->mode = Utils::getModeById($gameData->getMode(), $this);

        if (!$gameData instanceof FFAGameData) {
            $this->setStage(Stage::WAITING);
        }
    }

    /**
     * @return GameData|FFAGameData
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return Level
     */
    public function getLevel(): Level {
        return Server::getInstance()->getLevelByName($this->data->getWorld());
    }

    /**
     * @param int $stage
     */
    public function setStage(int $stage): void {
        $this->getMode()->onStageChange($stage);

        $this->stage = Utils::getStageById($stage, $this);
    }

    /**
     * @return Stage
     */
    public function getStage(): Stage {
        return $this->stage;
    }

    /**
     * @return Mode|FFAMode
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * @return string
     */
    public function getPlatform(): string {
        return $this->getData()->getPlatform();
    }

    /**
     * @param Player $player
     */
    public function addPlayer(Player $player): void {
        $this->players[$player->getLowerCaseName()] = $player;

        foreach ($this->getAllPlayers() as $p2) {
            $p2->showPlayer($player);
        }

        $player->sendMessage(TextFormat::colorize("&aПрисоединяемся к арене &e" . $this->getData()->getName() . "&a..."));

        $manager = Main::getPlayerDataManager();
        $data = $manager->getPlayerData($player);

        if (!$data instanceof PlayerData) {
            $data = $manager->registerPlayer($player);
        }

        $data->setSession($this);

        Utils::reset($player);

        $now = count($this->players);

        if ($this->getMode() instanceof FFAMode) {
            $this->getMode()->respawnPlayer($player);

            foreach ($this->getPlayers() as $arenaPlayer) {
                $arenaPlayer->sendMessage(TextFormat::colorize('&b' . $player->getName() . ' &fприсоединился. Игроков: &a'. $now));
            }
        } else {
            $player->teleport($this->getData()->getWaitingRoom()->setLevel($this->getLevel()));

            $player->getInventory()->setItem(8, Utils::createNamedTagItem(Item::get(Item::BED, 14), 'Выход', 'quit'));

            $gameData = $this->getData();
            $max = $gameData->getMaxPlayers();

            foreach ($this->getPlayers() as $arenaPlayer) {
                $arenaPlayer->sendMessage(TextFormat::colorize('&b' . $player->getName() . ' &fприсоединился. &7(&e'. $now .'&8/&e' . $max . '&7)'));
            }
        }

        Main::getInstance()->getServer()->getPluginManager()->callEvent(new PvPJoinEvent($player, $this->mode));
    }

    /**
     * @param Player $player
     * @param bool $killed
     * @param string|null $deathMessage
     */
    public function removePlayer(Player $player, bool $killed = false, string $deathMessage = null): void {
        $mode = $this->getMode();
        
        $player->setHealth(20);
        $player->setFood(20);

        if (!$killed) {
            unset($this->players[$player->getLowerCaseName()]);

            Main::getPlayerDataManager()->unregisterPlayer($player);
            Utils::reset($player);

            if ($player->isOnline()) {
                foreach($this->getAllPlayers() as $p2) {
                    $p2->showPlayer($player);
                }

                $player->setNameTag(TextFormat::YELLOW . $player->getName()); //todo
                $player->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
            }

            $now = count($this->players);

            if ($this->getMode() instanceof FFAMode) {
                foreach ($this->getPlayers() as $players) {
                    $players->sendMessage(TextFormat::colorize('&b' . $player->getName() . ' &fвышел. Игроков: &a'. $now));
                }
            } else {
                $gameData = $this->getData();

                $max = $gameData->getMaxPlayers();

                foreach ($this->getPlayers() as $players) {
                    $players->sendMessage(TextFormat::colorize('&b' . $player->getName() . ' &fвышел. &7(&e'. $now .'&8/&e' . $max . '&7)'));
                }
            }
        } else {
            foreach ($this->getPlayers() as $players) {
                if ($deathMessage !== null) {
                    $players->sendMessage(TextFormat::colorize($deathMessage));
                } else {
                    $team = $mode->getPlayerTeam($player);

                    $players->sendMessage(TextFormat::colorize(TeamData::COLORS[$team->getId()] . $player->getName() . ' &fубит.'));
                }
            }

            if ($mode instanceof FFAMode) {
                $mode->respawnPlayer($player);
                return;
            } else {
                if (!$mode instanceof StickDuels) {
                    $this->addSpectator($player);
                }
            }
        }

        if ($mode instanceof Mode) {
            $stage = $this->getStage();

            if ($stage instanceof RunningStage) {
                if ($mode instanceof BasicDuels) {
                    $team = $mode->getPlayerTeam($player);

                    if ($team !== null) {
                        $team->removePlayer($player);

                        if (count($team->getPlayers()) < 1) {
                            $mode->checkTeam($team);

                            $this->setStage(Stage::ENDING);

                            $message = null;

                            foreach ($mode->getTeams() as $team) {
                                $message = '&f' . (count($team->getPlayers()) > 1 ? 'Победители' : 'Победитель') . ': '. TeamData::COLORS[$team->getId()] . implode('&8, '. TeamData::COLORS[$team->getId()], array_map(function ($player): string {
                                        return $player->getName();
                                    }, $team->getPlayers()));
                            }

                            foreach ($this->getAllPlayers() as $players) {
                                $players->sendTitle(TextFormat::RED . 'Игра окончена.');

                                if ($message != null) {
                                    $players->sendMessage(TextFormat::colorize($message));
                                }
                            }

                            foreach ($team->getPlayers() as $player) {
                                $playerSession = Main::getSessionManager()->getSession($player);
                                $playerSession->addWins(1);

                                $player->sendTitle(TextFormat::GREEN . "Победа!");
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @return Player[]
     */
    public function getPlayers(): array {
        return $this->players;
    }

    /**
     * @return int
     */
    public function getPlayersCount(): int {
        return count($this->players);
    }

    /**
     * @param Player $player
     */
    public function addSpectator(Player $player): void {
        unset($this->players[$player->getLowerCaseName()]);

        $this->spectators[$player->getLowerCaseName()] = $player;

        foreach ($this->getAllPlayers() as $p2) {
            $p2->hidePlayer($player);
        }
        
        Utils::reset($player);
        
        $player->getInventory()->setHeldItemIndex(4);
        $player->getInventory()->setItem(8, Utils::createNamedTagItem(Item::get(Item::BED, 14), 'Выход', 'quit'));

        $player->setGamemode(Player::SPECTATOR);
        $player->teleport($this->getData()->getWaitingRoom()->setLevel($this->getLevel()));
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isSpectator(Player $player): bool {
        return in_array($player, $this->spectators, true);
    }

    /**
     * @return Player[]
     */
    public function getAllPlayers(): array {
        return array_merge($this->players, $this->spectators);
    }

    public function reset(): void {
        foreach ($this->getAllPlayers() as $player) {
            $this->removePlayer($player);
        }

        $data = $this->getData();
        $world = $data->getWorld();

        $this->getLevel()->unload(true);
        Server::getInstance()->loadLevel($world);

        $level = Server::getInstance()->getLevelByName($world);
        $level->setAutoSave(false);
        
        $this->setStage(Stage::WAITING);
        $this->getMode()->initTeams();
    }

    public function tick(): void {
        if ($this->getMode() instanceof FFAMode) {
            $this->getMode()->tick();
        } else {
            $this->getStage()->onTick();
        }
    }
}