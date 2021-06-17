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

    public const WAITING_STAGE = 0;
    public const COUNTDOWN_STAGE = 1;
    public const RUNNING_STAGE = 2;
    public const ENDING_STAGE = 3;

    /**
     * Game constructor.
     * @param GameData|FFAGameData $gameData
     */
    public function __construct($gameData) {
        $this->data = $gameData;
        $this->mode = Utils::getModeById($gameData->getMode(), $this);

        if (!$gameData instanceof FFAGameData) {
            $this->setStage(self::WAITING_STAGE);
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

        $player->sendMessage("Присоединяемся к арене ". $this->getData()->getName() ."...");

        $manager = Main::getPlayerDataManager();
        $data = $manager->getPlayerData($player);

        if (!$data instanceof PlayerData) {
            $data = $manager->registerPlayer($player);
        }

        $data->setSession($this);

        Utils::reset($player);

        $player->getInventory()->setItem(8, Utils::createNamedTagItem(Item::get(Item::BED, 14), 'Выход', 'quit'));

        if ($this->getMode() instanceof FFAMode) {
            $this->getMode()->respawnPlayer($player);
        } else {
            /** Я без понятия, если дать заранее локацию, то после сбросы арены игрока перестанет переносить */
            $w = $this->getData()->getWaitingRoom();

            $pos = new Location($w->getX(), $w->getY(), $w->getZ(), 0.0, 0.0, $this->getLevel());
            $player->teleport($pos);
        }

        $player->sendMessage(TextFormat::GREEN. 'Присоединился.');

        foreach ($this->getPlayers() as $arenaPlayer) {
            $arenaPlayer->sendMessage(TextFormat::colorize("&b". $player->getName() ." &fприсоединился."));
        }

        Main::getInstance()->getServer()->getPluginManager()->callEvent(new PvPJoinEvent($player, $this->mode));
    }

    /**
     * @param Player $player
     * @param bool $killed
     * @param string|null $deathMessage
     */
    public function removePlayer(Player $player, bool $killed = false, string $deathMessage = null): void {
        $player->setHealth(20);
        $player->setMaxHealth(20);

        var_dump($killed);

        if (!$killed) {
            Main::getPlayerDataManager()->unregisterPlayer($player);

            Utils::reset($player);

            if ($player->isOnline()) {
                $player->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
            }

            foreach ($this->getPlayers() as $players) {
                $players->sendMessage(TextFormat::colorize('&b' . $player->getName() . ' &fвышел.'));
            }

            Main::getInstance()->getServer()->getPluginManager()->callEvent(new PvPQuitEvent($player, $this->mode));
        } else {
            foreach ($this->getPlayers() as $players) {
                if ($deathMessage !== null) {
                    $players->sendMessage(TextFormat::colorize($deathMessage));
                } else {
                    $players->sendMessage(TextFormat::colorize('&b' . $player->getName() . ' &fубит.'));
                }
            }
        }

        $mode = $this->getMode();

        if ($mode instanceof FFAMode) {
            $mode->respawnPlayer($player);
        } else {
            $player->setGamemode(3);

            unset($this->players[$player->getLowerCaseName()]);

            Main::getPlayerDataManager()->unregisterPlayer($player);

            if ($this->getStage() instanceof RunningStage) {
                if ($mode instanceof BasicDuels) {
                    $teamId = $mode->getPlayerTeam($player);

                    if ($teamId !== null) {
                        $teamsData = $mode->getTeams();
                        unset($teamsData[$teamId][$player->getLowerCaseName()]);

                        $mode->setTeams($teamsData);

                        if (count($mode->getTeams()[$teamId]) < 1) {
                            $this->setStage(self::ENDING_STAGE);

                            $winners = [];

                            foreach ($mode->getTeams() as $team) {
                                foreach ($team as $name => $player) {
                                    $winners[] = $name;
                                }
                            }

                            foreach ($this->getPlayers() as $players) {
                                $players->sendMessage('Игра окончена.');

                                $players->sendMessage("Победители: ". implode("&7, &c", $winners));
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

        $this->setStage(self::WAITING_STAGE);

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