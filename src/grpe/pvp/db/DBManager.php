<?php

declare(strict_types=1);

namespace grpe\pvp\db;

use grpe\pvp\db\provider\SQLite3Provider;

/**
 * Class DBManager
 * @package grpe\pvp\db
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.2
 * @since   1.0.0
 */
class DBManager {

    /** @var SQLite3Provider */
    private $connection;

    /**
     * @param string $path
     */
    public function init(string $path): void {
        $this->connection = new SQLite3Provider($path . 'database.db');
        $this->connection->getDB()->query(
            'CREATE TABLE IF NOT EXISTS `pvp` (`id` INTEGER PRIMARY KEY AUTOINCREMENT, `username` VARCHAR(16) NOT NULL, `games` INT NOT NULL DEFAULT 0, `wins` INT NOT NULL DEFAULT 0, `kills` INT NOT NULL DEFAULT 0, `deaths` INT NOT NULL DEFAULT 0)'
        );
    }

    /**
     * @return SQLite3Provider
     */
    public function getConnection(): SQLite3Provider {
        return $this->connection;
    }
}