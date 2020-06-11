<?php

namespace App\Core;

use App\Core\Storage\SqlInterface;

class Product implements SqlInterface
{
    private string $table = 'products';

    private array $attributes = [
        'SKU',
        'description',
        'normal_price',
        'special_price',
    ];

    /** @inheritDoc */
    public function getTable(): string
    {
        return $this->table;
    }

    /** @inheritDoc */
    public function getUniqueKey(): string
    {
        return 'SKU';
    }

    /** @inheritDoc */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}