<?php

declare(strict_types=1);

namespace grpe\pvp\db;

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

    private \SQLite3 $database;

    /**
     * @param string $path
     */
    public function init(string $path): void {
        $db = new \SQLite3($path . 'database.db');
        $db->exec("CREATE TABLE IF NOT EXISTS `pvp` (`id` INT NOT NULL AUTO_INCREMENT, `username` VARCHAR(16) NOT NULL, `kills` INT NOT NULL)");

        $this->database = $db;
    }
}