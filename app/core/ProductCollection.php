<?php

namespace app\core;

use app\core\storage\Importable;
use Exception;

class ProductCollection implements Importable, Validatable
{
    public ?array $brokenItems = [];
    public ?array $filteredItems = [];
    public array $items = [];

    private string $table = 'products';

    private array $attributes = [
        'SKU',
        'description',
        'normal_price',
        'special_price',
    ];


    public function __construct(array $data)
    {
        $this->items = $data;
        $this->validate();
    }

    public function validate(): void
    {
        if (empty($this->items)) {
            throw new Exception("Empty source.");
        }

        foreach ($this->items as $item) {
            $product = $this->prepareProduct($item);

            if ($product && $this->checkProduct($product)) {
                $this->filteredItems[] = $product;
            } else {
                $this->brokenItems[] = $product;
            }
        }
    }

    private function checkProduct(array $productData): bool
    {
        return !empty($productData['SKU'])
            && !empty($productData['description'])
            && !empty($productData['normal_price'])
            && $productData['normal_price'] > 0
            && $productData['special_price'] < $productData['normal_price'];
    }

    private function prepareProduct(array $productData): array
    {
        $product = array_combine($this->attributes, $productData);
        $product['normal_price'] = (float) $product['normal_price'];
        $product['special_price'] = (float) $product['special_price'] ?: null;
        return $product;
    }

    public function getImportableData(): array
    {
        return $this->filteredItems;
    }

    public function getNonImportableData(): array
    {
        return $this->brokenItems;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getUniqueKey(): string
    {
        return 'SKU';
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getSqlDescription(): string
    {
        return "`SKU` varchar(16) UNIQUE NOT NULL,
                `description` text NOT NULL,
                `normal_price` float NOT NULL,
                `special_price` float NULL";
    }
}