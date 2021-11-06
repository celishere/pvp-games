<?php

declare(strict_types=1);

namespace grpe\pvp\player\sessions;

use grpe\pvp\Main;
use grpe\pvp\db\models\Model;

use pocketmine\Player;

/**
 * Class Session
 * @package grpe\pvp\player\sessions
 *
 * @version 1.0.2
 * @since   1.0.0
 */
class Session {

    private string $username;
    private bool $isDirty = false;

    private int $osId = 0;

    private Model $model;

    /**
     * Session constructor.
     * @param Player $player
     */
    public function __construct(Player $player) {
        $this->username = $player->getLowerCaseName();

        $db = Main::getDataBaseManager()->getConnection()->getDB();

        $prepare = $db->prepare('SELECT * FROM `pvp` WHERE username = :username');
        $prepare->bindValue('username', $this->username);

        $res = $prepare->execute()->fetchArray(SQLITE3_ASSOC);

        $model = new Model();
        $model->username = $this->username;

        if (is_array($res)) {
            $model->id = $res['id'];

            unset($res['id'], $res['username']);

            foreach ($res as $name => $data) {
                $model->{$name} = $data;
            }

            $model->created = true;
        }

        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @param int $id
     */
    public function setOsId(int $id): void {
        $this->osId = $id;
    }

    /**
     * @return int
     */
    public function getOsId(): int {
        return $this->osId;
    }

    /**
     * @param int $value
     */
    public function addGames(int $value): void {
        $old = $this->model->games;
        
        $this->model->games = ($old != null ? $value + $old : $value);
        $this->isDirty = true;
    }

    /**
     * @return int
     */
    public function getGames(): int {
        return $this->model->games ?? 0;
    }

    /**
     * @param int $value
     */
    public function addWins(int $value): void {
        $old = $this->model->wins;

        $this->model->wins = ($old != null ? $value + $old : $value);
        $this->isDirty = true;
    }

    /**
     * @return int
     */
    public function getWins(): int {
        return $this->model->wins ?? 0;
    }

    /**
     * @param int $value
     */
    public function addKills(int $value): void {
        $old = $this->model->kills;

        $this->model->kills = ($old != null ? $value + $old : $value);
        $this->isDirty = true;
    }

    /**
     * @return int
     */
    public function getKills(): int {
        return $this->model->kills ?? 0;
    }

    /**
     * @param int $value
     */
    public function addDeath(int $value): void {
        $old = $this->model->deaths;

        $this->model->deaths = ($old != null ? $value + $old : $value);
        $this->isDirty = true;
    }

    /**
     * @return int
     */
    public function getDeath(): int {
        return $this->model->deaths ?? 0;
    }

    public function onSave(): void {
        if ($this->isDirty) {
            $this->model->save();
        }
    }
}