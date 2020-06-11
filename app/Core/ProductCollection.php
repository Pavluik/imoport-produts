<?php

namespace App\Core;

use App\Core\Storage\ImportableInterface;
use App\Core\Storage\SqlInterface;
use Exception;

class ProductCollection implements ImportableInterface, ValidatableInterface
{
    public ?array $brokenItems = [];
    public ?array $filteredItems = [];
    public array $items = [];
    public SqlInterface $model;

    /**
     * ProductCollection constructor.
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->items = $data;
        $this->model = new Product();
        $this->validate();
    }

    /** @inheritDoc */
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

    /**
     * Retrieves whether product is valid or not
     * @param array $productData
     * @return bool
     */
    private function checkProduct(array $productData): bool
    {
        return !empty($productData['SKU'])
            && !empty($productData['description'])
            && !empty($productData['normal_price'])
            && $productData['normal_price'] > 0
            && $productData['special_price'] < $productData['normal_price'];
    }

    /**
     * Normalizing product attributes
     * @param array $productData
     * @return array
     */
    private function prepareProduct(array $productData): array
    {
        $product = array_combine($this->model->getAttributes(), $productData);
        $product['normal_price'] = (float) $product['normal_price'];
        $product['special_price'] = (float) $product['special_price'] ?: null;
        return $product;
    }

    /** @inheritDoc */
    public function getImportableData(): array
    {
        return $this->filteredItems;
    }

    /** @inheritDoc */
    public function getNonImportableData(): array
    {
        return $this->brokenItems;
    }
}