<?php

declare(strict_types=1);

namespace grpe\pvp\player\sessions;

use grpe\pvp\Main;
use grpe\pvp\db\Request;

use pocketmine\Player;

/**
 * Class Session
 * @package grpe\pvp\player\sessions
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class Session {

    private string $username;

    private int $games = 0;
    private int $wins = 0;
    private int $kills = 0;
    private int $deaths = 0;

    /**
     * Session constructor.
     * @param Player $player
     */
    public function __construct(Player $player) {
        $this->username = $player->getLowerCaseName();

        $db = Main::getDataBaseManager()->getDatabase();

        $prepare = $db->prepare("SELECT * FROM `pvp` WHERE username = :username");
        $prepare->bindValue('username', $this->username);

        $res = $prepare->execute()->fetchArray(SQLITE3_ASSOC);

        if (is_bool($res)) {
            $prep = $db->prepare("INSERT INTO `pvp` (username) VALUES (:username)");
            $prep->bindValue("username", $this->username);
            $prep->execute();
        }


        //todo переделать это

        foreach (['games', 'wins', 'kills', 'deaths'] as $item) {
            $request = new Request($this->getUsername(), $item);

            $this->{$item} = $request->get();
        }
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @param int $value
     */
    public function addGames(int $value): void {
        $this->games += $value;
    }

    /**
     * @return int
     */
    public function getGames(): int {
        return $this->games;
    }

    /**
     * @param int $value
     */
    public function addWins(int $value): void {
        $this->wins += $value;
    }

    /**
     * @return int
     */
    public function getWins(): int {
        return $this->wins;
    }

    /**
     * @param int $value
     */
    public function addKills(int $value): void {
        $this->kills += $value;
    }

    /**
     * @return int
     */
    public function getKills(): int {
        return $this->kills;
    }

    /**
     * @param int $value
     */
    public function addDeath(int $value): void {
        $this->deaths += $value;
    }

    /**
     * @return int
     */
    public function getDeath(): int {
        return $this->deaths;
    }

    public function onSave(): void {
        //todo переделать это
/*
        foreach ([['games' => $this->games], ['wins', $this->wins], ['kills', $this->kills], ['deaths', $this->deaths]] as $item) {
            $request = new Request($this->getUsername(), $item[0]);
            $request->set($item[1]);
        }*/
    }
}