<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use grpe\pvp\Main;

use grpe\pvp\game\stages\CountdownStage;
use grpe\pvp\game\stages\WaitingStage;
use grpe\pvp\game\stages\RunningStage;
use grpe\pvp\game\stages\EndingStage;

use grpe\pvp\game\mode\Mode;
use grpe\pvp\game\mode\FFAMode;
use grpe\pvp\game\mode\BasicDuels;

use grpe\pvp\game\mode\modes\StickDuels;
use grpe\pvp\game\mode\modes\ClassicDuels;
use grpe\pvp\game\mode\modes\SumoDuels;
use grpe\pvp\game\mode\modes\FFA;

use grpe\pvp\player\PlayerData;

use grpe\pvp\event\PvPJoinEvent;

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

    protected GameData $data;
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
        $this->mode = $this->getModeById($gameData->getMode());

        if ($gameData->getMode() !== 'ffa') {
            $this->setStage(self::WAITING_STAGE);
        }
    }

    /**
     * @return GameData
     */
    public function getData(): GameData {
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

        $this->stage = $this->getStageById($stage);
    }

    /**
     * @return Stage
     */
    public function getStage(): Stage {
        return $this->stage;
    }

    /**
     * @param int $id
     *
     * @return Stage
     */
    public function getStageById(int $id): Stage {
        switch ($id) {
            default:
            case 0:
                return new WaitingStage($this);
            case 1:
                return new CountdownStage($this);
            case 2:
                return new RunningStage($this);
            case 3:
                return new EndingStage($this);
        }
    }

    /**
     * @return Mode|FFAMode
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * @param string $id
     *
     * @return Mode|FFAMode
     */
    public function getModeById(string $id) {
        switch ($id) {
            default:
            case 'stick':
                return new StickDuels($this);
            case 'classic':
                return new ClassicDuels($this);
            case 'sumo':
                return new SumoDuels($this);
            case 'ffa':
                return new FFA($this);
        }
    }

    /**
     * @param Player $player
     */
    public function addPlayer(Player $player): void {
        $this->players[$player->getLowerCaseName()] = $player;

        $manager = Main::getPlayerDataManager();
        $data = $manager->getPlayerData($player);

        if (!$data instanceof PlayerData) {
            $data = $manager->registerPlayer($player);
        }

        $data->setSession($this);

        /** Я без понятия, если дать заранее локацию, то после сбросы арены игрока перестанет переносить */
        $w = $this->getData()->getWaitingRoom();
        $pos = new Location($w->getX(), $w->getY(), $w->getZ(), 0.0, 0.0, $this->getLevel());
        $player->teleport($pos);

        $player->sendMessage(TextFormat::GREEN. 'Присоединился.');

        Main::getInstance()->getServer()->getPluginManager()->callEvent(new PvPJoinEvent($player, $this->mode));
    }

    /**
     * @param Player $player
     * @param bool $killed
     */
    public function removePlayer(Player $player, bool $killed = false): void {
        if (!$killed) {
            if ($player->isOnline()) {
                $player->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
            }

            foreach ($this->getPlayers() as $players) {
                $players->sendMessage(TextFormat::colorize('&b'. $player->getName() .' &fвышел.'));
            }
        } else {
            foreach ($this->getPlayers() as $players) {
                $players->sendMessage(TextFormat::colorize('&b'. $player->getName() .' &fубит.'));
            }

            $player->setGamemode(3);
        }

        unset($this->players[$player->getLowerCaseName()]);

        Main::getPlayerDataManager()->unregisterPlayer($player);

        $mode = $this->getMode();

        if ($this->getStage() instanceof RunningStage) {
            if ($mode instanceof BasicDuels) {
                $teamId = $mode->getPlayerTeam($player);

                if ($teamId !== null) {
                    unset($mode->getTeams()[$teamId][$player->getLowerCaseName()]);

                    if (count($mode->getTeams()[$teamId]) < 1) {
                        $this->setStage(self::ENDING_STAGE);

                        foreach ($this->getPlayers() as $players) {
                            $players->sendMessage('Игра окончена.');
                        }
                    }
                }
            }
        } else if ($mode instanceof FFAMode) {
            $player->teleport($mode->getPos());
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
        if (!$this->getMode() instanceof FFAMode) {
            $this->getStage()->onTick();

            if ($this->getMode() instanceof SumoDuels) {
                foreach ($this->getPlayers() as $player) {
                    if ($player->getY() < 0) { //задать значение в конфиге?
                        $this->removePlayer($player, true);
                    }
                }
            }
        }
    }
}