<?php

namespace App\Core\Storage;

use Exception;

abstract class DatabaseStorage implements StorageInterface
{
    protected $connection;

    /**
     * DatabaseStorage constructor
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config)
    {
        if (!isset($config['storage'])) {
            throw new Exception("No storage config.");
        }
    }

    /** @inheritDoc */
    public function save(ImportableInterface $collection): array
    {
        $stats = $this->saveOrUpdate($collection);

        return $stats + ['errors' => count($collection->getNonImportableData())];
    }
}