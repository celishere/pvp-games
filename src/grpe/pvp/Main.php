<?php

declare(strict_types=1);

namespace grpe\pvp;

use grpe\pvp\db\DBManager;

use grpe\pvp\game\GameLoader;
use grpe\pvp\game\GameManager;

use grpe\pvp\game\task\GameSessionsTask;

use grpe\pvp\command\JoinCommand;
use grpe\pvp\command\QuitCommand;
use grpe\pvp\command\StatsCommand;

use grpe\pvp\listener\PvPListener;
use grpe\pvp\listener\ServiceListener;

use grpe\pvp\player\PlayerDataManager;
use grpe\pvp\player\sessions\SessionManager;

use grpe\pvp\lang\LanguageFactory;

use grpe\pvp\utils\Utils;

use pocketmine\plugin\PluginBase;

/**
 * Class Main
 * @package grpe\pvp
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.2
 * @since   1.0.0
 */
class Main extends PluginBase {

    private static Main $instance;
    private static DBManager $dbManager;
    private static GameManager $gameManager;
    private static SessionManager $sessionManager;
    private static PlayerDataManager $playerDataManager;

    public function onLoad(): void {
        self::$instance           = $this;
        self::$dbManager          = new DBManager();
        self::$gameManager        = new GameManager();
        self::$sessionManager     = new SessionManager();
        self::$playerDataManager  = new PlayerDataManager();

        new LanguageFactory();

        Utils::createDirectory($this->getDataFolder(), 'arenas/');

        self::getDataBaseManager()->init($this->getDataFolder());

        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->register('join', new JoinCommand('join', 'присоединиться к игре.'));
        $commandMap->register('quit', new QuitCommand('quit', 'выйти из игры.'));
        $commandMap->register('stats', new StatsCommand('stats', 'твоя статистика.'));
    }

    public function onEnable(): void {
        GameLoader::loadArenas();

        $this->getServer()->getPluginManager()->registerEvents(new PvPListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ServiceListener(), $this);

        $this->getScheduler()->scheduleRepeatingTask(new GameSessionsTask(self::getGameManager()), 20);
    }

    /**
     * @return Main
     */
    public static function getInstance(): Main {
        return self::$instance;
    }

    /**
     * @return DBManager
     */
    public static function getDataBaseManager(): DBManager {
        return self::$dbManager;
    }

    /**
     * @return GameManager
     */
    public static function getGameManager(): GameManager {
        return self::$gameManager;
    }

    /**
     * @return SessionManager
     */
    public static function getSessionManager(): SessionManager {
        return self::$sessionManager ?? new SessionManager();
    }

    /**
     * @return PlayerDataManager
     */
    public static function getPlayerDataManager(): PlayerDataManager {
        return self::$playerDataManager;
    }
}