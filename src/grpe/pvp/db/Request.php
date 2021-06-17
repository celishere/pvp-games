<?php

declare(strict_types=1);

namespace grpe\pvp\db;

use grpe\pvp\Main;

/**
 * Class Request
 * @package grpe\pvp\db
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class Request {

    private string $username;
    private string $type;

    /**
     * Request constructor.
     *
     * @param string $username
     * @param string $type
     */
    public function __construct(string $username, string $type) {
        $this->username = $username;
        $this->type = $type;
    }

    /**
     * @param int $value
     */
    public function set(int $value): void {
        $type = $this->type;

        $prepare = Main::getDataBaseManager()->getDatabase()->prepare("INSERT INTO `pvp` (`username`, `$type`) VALUES (:username, :value)");

        if ($prepare instanceof \SQLite3Stmt) {
            $prepare->bindValue("username", $this->username);
            $prepare->bindValue("value", $value);
            $prepare->execute();
        }
    }

    /**
     * @return int
     */
    public function get(): int {
        $prepare = Main::getDataBaseManager()->getDatabase()->prepare("SELECT * FROM `pvp` WHERE `username` = :username");

        if ($prepare instanceof \SQLite3Stmt) {
            $prepare->bindValue("username", $this->username);

            $result = $prepare->execute()->fetchArray(SQLITE3_ASSOC);

            if (isset($result[$this->type])) {
                return $result[$this->type];
            }
        }

        return 0;
    }
}