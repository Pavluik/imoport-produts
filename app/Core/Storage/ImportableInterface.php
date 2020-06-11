<?php

namespace App\Core\Storage;

interface ImportableInterface
{
    /**
     * The array of data that should be imported
     * @return array
     */
    public function getImportableData(): array;

    /**
     * The array of inconsistent data
     * @return array
     */
    public function getNonImportableData(): array;
}