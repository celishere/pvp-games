<?php

declare(strict_types=1);

namespace grpe\pvp;

use grpe\pvp\command\JoinCommand;
use grpe\pvp\command\QuitCommand;
use grpe\pvp\command\StatsCommand;
use grpe\pvp\game\GameLoader;
use grpe\pvp\game\GameManager;

use grpe\pvp\game\task\GameSessionsTask;
use grpe\pvp\listener\PvPListener;
use grpe\pvp\player\PlayerDataManager;
use grpe\pvp\utils\Utils;

use pocketmine\plugin\PluginBase;

/**
 * Class Main
 * @package grpe\pvp
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class Main extends PluginBase {

    private static Main $instance;
    private static GameManager $gameManager;
    private static PlayerDataManager $playerDataManager;

    public function onLoad(): void {
        self::$instance           = $this;
        self::$gameManager        = new GameManager();
        self::$playerDataManager  = new PlayerDataManager();

        Utils::createDirectory($this->getDataFolder(), 'arenas/');

        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->register('join', new JoinCommand('join', 'присоединиться к игре.'));
        $commandMap->register('quit', new QuitCommand('quit'));
        $commandMap->register('stats', new StatsCommand('quit'));
    }

    public function onEnable(): void {
        GameLoader::loadArenas();

        $this->getServer()->getPluginManager()->registerEvents(new PvPListener(), $this);
        $this->getScheduler()->scheduleRepeatingTask(new GameSessionsTask(self::getGameManager()), 20);
    }

    /**
     * @return Main
     */
    public static function getInstance(): Main {
        return self::$instance;
    }

    /**
     * @return GameManager
     */
    public static function getGameManager(): GameManager {
        return self::$gameManager;
    }

    /**
     * @return PlayerDataManager
     */
    public static function getPlayerDataManager(): PlayerDataManager {
        return self::$playerDataManager;
    }
}