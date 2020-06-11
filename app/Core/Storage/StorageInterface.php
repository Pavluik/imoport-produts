<?php

namespace App\Core\Storage;

interface StorageInterface
{
    /**
     * Save necessary data into database
     * @param ImportableInterface $collection
     * @return array
     */
    public function save(ImportableInterface $collection): array;
}