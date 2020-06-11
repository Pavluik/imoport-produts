<?php

namespace App\Core\Storage;

use Exception;
use mysqli;

class MysqlStorage extends DatabaseStorage
{
    /**
     * MysqlStorage constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $config = $config['storage']['mysql'] ?? false;
        if (!$config || !isset($config['host'], $config['user'], $config['password'], $config['dbname'], $config['port'])) {
            throw new Exception("MySQL config failed.");
        }

        $this->connection = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname'], $config['port']);
    }

    /**
     * Checks the collection for an existing at DB and creates if necessary
     * @param ImportableInterface $collection
     * @return array Statistics
     */
    public function saveOrUpdate(ImportableInterface $collection): array
    {
        $table = $collection->model->getTable();
        $uniqueKey = $collection->model->getUniqueKey();
        $items = $collection->getImportableData();

        $items = $this->updateOldRecords($table, $items, $uniqueKey);

        $inserted = $this->massInsert($table, $items);

        return ['created' => $inserted, 'updated' => (count($items) - $inserted)];

    }

    /**
     * @param string $table
     * @param array $columns
     * @param string $where
     * @return mixed
     */
    private function select(string $table, array $columns, string $where)
    {
        $columns = implode(',', $columns);
        $query = $this->connection->prepare("SELECT $columns FROM $table WHERE $where;");

        $query->execute();
        $query->bind_result($result);
        $query->close();
        return $result;

    }

    /**
     * Update entity by unique key
     * @param string $table
     * @param array $attributes
     * @param string $where
     * @return mixed
     */
    private function update(string $table, array $attributes, string $where)
    {
        $keys = implode(',', array_keys($attributes));
        $values = implode(',', array_values($attributes));

            $query = $this->connection->prepare("UPDATE $table SET $keys) VALUES (?) WHERE $where;");

        $query->bind_param("s", $values);

        $query->execute();
        $query->bind_result($result);
        $query->close();
        return $result;
    }

    /**
     * Update entities by unique keys
     * @param string $table
     * @param array $items
     * @param string $uniqueKey
     * @return array
     */
    private function updateOldRecords(string $table, array $items, string $uniqueKey): array
    {
        $keys = array_column($items, $uniqueKey);

        $updateKeys = $this->select(
            $table,
            [$uniqueKey],
            $uniqueKey." IN ('".implode("','", $keys)."')"
        );

        if (empty($updateKeys)) {
            return $items;
        }

        foreach ($items as $k => $item) {
            if ($updateKeys && in_array($item[$uniqueKey], $updateKeys)) {
                $this->update($table, $item, $item[$uniqueKey]);
                unset($items[$k]);
            }
        }

        return $items;
    }

    /**
     * Insert an array of entities
     * @param string $table
     * @param array $attributes
     * @return int
     */
    private function massInsert(string $table, array $attributes): int
    {
        if (empty($attributes)) {
            return 0;
        }

        $values = '';
        $keys = implode(',', array_keys($attributes[0]));

        foreach ($attributes as $attribute) {
            $values .= "('".implode("','", array_values($attribute))."'),";
        }

        $values = substr($values, 0, strlen($values) - 1);

        $query = $this->connection->prepare("INSERT INTO $table ($keys) VALUES $values;");

        $query->execute();
        $rowsAffected = $query->affected_rows;
        $query->close();
        return $rowsAffected > 0 ? $rowsAffected : 0;
    }

}