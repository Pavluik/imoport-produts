<?php

namespace app\core\storage;

interface Importable extends SqlInterface
{
    public function getUniqueKey(): string;

    public function getImportableData(): array;

    public function getNonImportableData(): array;
}