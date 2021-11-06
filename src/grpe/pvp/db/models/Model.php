<?php

declare(strict_types=1);

namespace grpe\pvp\db\models;

use grpe\pvp\Main;

/**
 * Class Model
 *
 * @package grpe\pvp\db\models
 * @author celis <celispost@icloud.com>
 *
 * @version 1.0.2
 * @since   1.0.2
 *
 * @property string username
 * @property int games
 * @property int wins
 * @property int kills
 * @property int deaths
 */
class Model {

    protected static string $table = 'pvp';

    /**
     * @var string[]
     */
    protected array $fillable = [
        'username', 'games', 'wins', 'kills', 'deaths'
    ];

    protected array $data = [];

    protected array $dirtyData = [];

    protected bool $dirty = false;

    public bool $created = false;

    public int $id;

    /**
     * @return string
     */
    public static function getTable(): string{
        return static::$table;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name) {
        if (!isset($this->data[$name])) {
            return null;
        }

        return $this->data[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value): void {
        if (in_array($name, $this->fillable) && (!isset($this->data[$name]) || $this->data[$name] !== $value)) {
            $this->dirty = true;
            $this->dirtyData[$name] = $value;
        }

        $this->data[$name] = $value;
    }

    /**
     * @return true
     */
    public function save(): bool {
        if (!$this->dirty || count($this->dirtyData) == 0) {
            return true;
        }

        $db = Main::getDataBaseManager()->getConnection();

        if ($this->created) {
            return $db->saveModel($this);
        } else {
            $insertId = $db->createModel($this);

            if ($insertId > 0) {
                $this->id = $insertId;
                $this->created = true;
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getDirtyData(): array{
        $this->dirty = false;

        $dirtyData = $this->dirtyData;
        $this->dirtyData = [];

        return $dirtyData;
    }

    /**
     * @return array
     */
    public function toArray(): array{
        return $this->data;
    }
}