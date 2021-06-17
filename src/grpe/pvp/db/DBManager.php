<?php

declare(strict_types=1);

namespace grpe\pvp\db;

use SQLite3;

/**
 * Class DBManager
 * @package grpe\pvp\db
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class DBManager {

    private SQLite3 $database;

    /**
     * @param string $path
     */
    public function init(string $path): void {
        $db = new SQLite3($path . 'database.db');
        $db->query("CREATE TABLE IF NOT EXISTS `pvp` (`id` INT NOT NULL AUTO_INCREMENT, `username` VARCHAR(16) NOT NULL, `games` INT NOT NULL DEFAULT 0, `wins` INT NOT NULL DEFAULT 0, `kills` INT NOT NULL DEFAULT 0, `deaths` INT NOT NULL DEFAULT 0, PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;");

        $this->database = $db;
    }

    /**
     * @return SQLite3
     */
    public function getDatabase(): SQLite3 {
        return $this->database;
    }
}