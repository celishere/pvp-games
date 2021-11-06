<?php

declare(strict_types=1);

namespace grpe\pvp\db\provider;

use grpe\pvp\db\models\Model;

/**
 * Class SQLite3Provider
 *
 * @package grpe\pvp\db\provider
 * @author celis <celispost@icloud.com>
 *
 * @version 1.0.2
 * @since   1.0.2
 */
class SQLite3Provider {

    private \SQLite3 $connection;

    /**
     * @param string $filename
     */
    public function __construct(string $filename) {
        $this->connection = new \SQLite3($filename);
    }

    /**
     * @return \SQLite3
     */
    public function getDB(): \SQLite3 {
        return $this->connection;
    }

    /**
     * @param Model $model
     *
     * @return bool
     */
    public function saveModel(Model $model): bool {
        $data = $model->getDirtyData();

        $queryData = [];
        $values = [];
        $columns = [];

        $types = '';

        foreach ($data as $key => $value) {
            $columns[] = $key;
            $queryData[] = sprintf('`%s` = :%s', $key, $key);
            $values[] = $value ?? 'null';
            $types .= $this->getVarType($value);
        }

        $query = sprintf('UPDATE `%s` SET %s WHERE `id` = %d', $model::getTable(), join(', ', $queryData), $model->id);

        $stmt = $this->getDB()->prepare($query);

        foreach ($columns as $id => $column) {
            $var = $values[$id] ?? null;

            $stmt->bindValue($column, $var, $this->getVarType($var));
        }

        return $stmt->execute() !== false;
    }

    /**
     * @param Model $model
     *
     * @return int
     */
    public function createModel(Model $model): int {
        $data = $model->toArray();

        $columns = [];
        $values = [];
        $realValues = [];

        $types = '';

        foreach ($data as $key => $value) {
            $columns[] = $key;
            $values[] = ':'. $key;
            $realValues[] = $value ?? 'null';

            $types .= $this->getVarType($value);
        }

        $query = sprintf('INSERT INTO `%s`(%s) VALUES(%s)', $model::getTable(), join(', ', $columns), join(', ', $values));
        $stmt = $this->getDB()->prepare($query);

        foreach ($columns as $id => $column) {
            $var = $realValues[$id] ?? null;

            $stmt->bindValue($column, $var, $this->getVarType($var));
        }

        $ok = $stmt->execute();

        if (!$ok) {
            return -1;
        }

        return $this->connection->lastInsertRowID();
    }

    /**
     * @param $val
     *
     * @return int
     */
    protected function getVarType($val): int {
        if (is_float($val)) {
            return SQLITE3_FLOAT;
        }

        if (is_int($val)) {
            return SQLITE3_INTEGER;
        }

        if (is_string($val)) {
            return SQLITE3_TEXT;
        }

        return SQLITE3_BLOB;
    }
}