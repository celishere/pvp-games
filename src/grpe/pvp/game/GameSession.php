<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use grpe\pvp\Main;

use grpe\pvp\game\mode\Mode;
use grpe\pvp\game\mode\FFAMode;
use grpe\pvp\game\mode\BasicDuels;

use grpe\pvp\game\stages\RunningStage;

use grpe\pvp\player\PlayerData;

use grpe\pvp\event\PvPJoinEvent;
use grpe\pvp\event\PvPQuitEvent;

use grpe\pvp\utils\Utils;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;

use pocketmine\level\Level;
use pocketmine\level\Location;

use pocketmine\utils\TextFormat;

/**
 * Class GameManager
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
final class GameSession {

    /** @var Player[] */
    protected array $players = [];

    protected $data;
    protected Stage $stage;

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

        $player->sendMessage(TextFormat::colorize("&aПрисоединяемся к арене &e". $this->getData()->getName() ."&a..."));

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
            $player->teleport($this->getData()->getWaitingRoom());

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
                    $players->sendMessage(TextFormat::colorize('&b' . $player->getName() . ' &fубит.'));
                }
            }

            if ($mode instanceof FFAMode) {
                $mode->respawnPlayer($player);
                return;
            } else {
                unset($this->players[$player->getLowerCaseName()]);

                $player->setGamemode(3);
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
                                $message = '&f' . (count($team->getPlayers()) > 1 ? 'Победители' : 'Победитель') . ': &7' . implode('&8, &7', array_map(function ($player): string {
                                        return $player->getName();
                                    }, $team->getPlayers()));
                            }

                            foreach ($this->getPlayers() as $players) {
                                $players->sendTitle(TextFormat::RED . 'Игра окончена.');

                                if ($message != null) {
                                    $players->sendMessage(TextFormat::colorize($message));
                                }
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

    public function reset(): void {
        foreach ($this->getPlayers() as $player) {
            $this->removePlayer($player);
        }

        $this->players = [];

        $this->setStage(Stage::WAITING);

        if ($this->getLevel()->unload()) {
            if (!Server::getInstance()->loadLevel($this->getData()->getWorld())) {
                Main::getGameManager()->killGame($this->getData());
            }
        }
    }

    public function tick(): void {
        if ($this->getMode() instanceof FFAMode) {
            $this->getMode()->tick();
        } else {
            $this->getStage()->onTick();
        }
    }
}