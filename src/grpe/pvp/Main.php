<?php

declare(strict_types=1);

namespace grpe\pvp;

use grpe\pvp\game\GameManager;

use grpe\pvp\utils\Utils;

use pocketmine\plugin\PluginBase;

/**
 * Class Main
 * @package grpe\pvp
 *
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class Main extends PluginBase {

    private static Main $instance;

    public function onLoad(): void {
        self::$instance = $this;
        self::$manager  = new GameManager();

        Utils::createDirectory($this->getDataFolder(), 'arenas/');
    }

    public function onEnable(): void {
        self::getManager()->loadArenas();
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
    public static function getManager(): GameManager {
        return self::$manager;
    }
}