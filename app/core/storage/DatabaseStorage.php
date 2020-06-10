<?php

namespace app\core\storage;

use Exception;

abstract class DatabaseStorage implements StorageInterface
{
    protected $connection;

    /**
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config)
    {
        if (!isset($config['storage'])) {
            throw new Exception("No storage config.");
        }
    }

    public function save(Importable $collection): string
    {
        $stats = $this->saveOrUpdate($collection);

        return $stats . " Errors: ".count($collection->getNonImportableData());
    }
}