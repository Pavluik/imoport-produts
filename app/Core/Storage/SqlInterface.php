<?php

namespace App\Core\Storage;

interface SqlInterface
{
    /**
     * Returns table name related
     * @return string
     */
    public function getTable(): string;

    /**
     * Returns unique key
     * @return string
     */
    public function getUniqueKey(): string;

    /**
     * Array of entity attributes
     * @return array
     */
    public function getAttributes(): array;
}