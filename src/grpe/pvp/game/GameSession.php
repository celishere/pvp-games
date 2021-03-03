<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use grpe\pvp\Main;

use grpe\pvp\game\stages\CountdownStage;
use grpe\pvp\game\stages\WaitingStage;
use grpe\pvp\game\stages\RunningStage;
use grpe\pvp\game\stages\EndingStage;

use grpe\pvp\game\mode\StickDuels;
use grpe\pvp\game\mode\ClassicDuels;

use grpe\pvp\player\PlayerData;

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
    protected Mode $mode;

    public const WAITING_STAGE = 0;
    public const COUNTDOWN_STAGE = 1;
    public const RUNNING_STAGE = 2;
    public const ENDING_STAGE = 3;

    /**
     * Game constructor.
     * @param GameData $gameData
     */
    public function __construct(GameData $gameData) {
        $this->data = $gameData;
        $this->mode = $this->getModeById($gameData->getMode());

        $this->setStage(self::WAITING_STAGE);
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
     * @return Mode
     */
    public function getMode(): Mode {
        return $this->mode;
    }

    /**
     * @param string $id
     *
     * @return Mode
     */
    public function getModeById(string $id): Mode {
        switch ($id) {
            default:
            case 'stick':
                return new StickDuels($this);
            case 'classic':
                return new ClassicDuels($this);
        }
    }

    /**
     * @param Player $player
     */
    public function addPlayer(Player $player): void {
        $this->players[$player->getUniqueId()->toString()] = $player;

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
                $players->sendMessage($player->getName() .' вышел.');
            }
        } else {
            foreach ($this->getPlayers() as $players) {
                $players->sendMessage($player->getName() .' убит.');
            }

            $player->setGamemode(3);
        }

        unset($this->players[$player->getUniqueId()->toString()]);

        Main::getPlayerDataManager()->unregisterPlayer($player);

        if ($this->getStage() instanceof RunningStage) {
            $mode = $this->getMode();

            if ($mode instanceof TeamMode) {
                $teamId = $mode->getPlayerTeam($player);

                if ($teamId !== null) {
                    unset($mode->getTeams()[$teamId][$player->getUniqueId()->toString()]);

                    if (count($mode->getTeams()[$teamId]) < 1) {
                        $this->setStage(self::ENDING_STAGE);

                        foreach ($this->getPlayers() as $players) {
                            $players->sendMessage('Игра окончена.');
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
            if (Server::getInstance()->loadLevel($this->getData()->getWorld())) {
                var_dump(2);
            }
        }
    }

    public function tick(): void {
        $this->getStage()->onTick();
    }
}
