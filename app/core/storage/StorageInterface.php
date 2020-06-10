<?php

namespace app\core\storage;

interface StorageInterface
{
    public function save(Importable $collection): string;
}