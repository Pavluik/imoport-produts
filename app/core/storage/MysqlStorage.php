<?php

namespace app\core\storage;

use Exception;
use mysqli;

class MysqlStorage extends DatabaseStorage
{
    public function __construct(array $config)
    {
        parent::__construct($config);

        $config = $config['storage']['mysql'] ?? false;
        if (!$config || !isset($config['host'], $config['user'], $config['password'], $config['dbname'], $config['port'])) {
            throw new Exception("MySQL config failed.");
        }

        $this->connection = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname'], $config['port']);
    }

    public function saveOrUpdate(Importable $collection): string
    {
        $this->checkTable($collection);

        $table = $collection->getTable();
        $uniqueKey = $collection->getUniqueKey();
        $items = $collection->getImportableData();

        $items = $this->updateOldRecords($table, $items, $uniqueKey);

        $inserted = $this->massInsert($table, $items);

        return "Created: $inserted Updated: ".(count($items) - $inserted);

    }

    private function select(string $table, array $columns, string $where)
    {
        $columns = implode(',', $columns);
        $query = $this->connection->prepare("SELECT $columns FROM $table WHERE $where;");

        $query->execute();
        $query->bind_result($result);
        $query->close();
        return $result;

    }

    private function update(string $table, array $attributes, string $where)
    {
        $keys = implode(',', array_keys($attributes));
        $values = implode(',', array_values($attributes));

            $query = $this->connection->prepare("UPDATE $table SET $keys) VALUES (?) WHERE $where;");

        $query->bind_param("si", $values);

        $query->execute();
        $query->bind_result($result);
        $query->close();
        return $result;
    }

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

    private function massInsert(string $table, array $attributes)
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

    public function checkTable(Importable $importable)
    {
        $table = $importable->getTable();
        $columns = $importable->getSqlDescription();
        $query = $this->connection->prepare("CREATE TABLE IF NOT EXISTS $table ($columns);");

        $query->execute();
        $query->close();
    }

}