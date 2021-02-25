<?php

declare(strict_types=1);

namespace grpe\pvp;

use grpe\pvp\game\GameLoader;
use grpe\pvp\game\GameManager;

use grpe\pvp\game\task\GameSessionsTask;
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
    private static GameManager $manager;

    public function onLoad(): void {
        self::$instance = $this;
        self::$manager  = new GameManager();

        Utils::createDirectory($this->getDataFolder(), 'arenas/');
    }

    public function onEnable(): void {
        GameLoader::loadArenas();

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
        return self::$manager;
    }
}