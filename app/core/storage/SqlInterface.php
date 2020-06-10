<?php

namespace app\core\storage;

interface SqlInterface
{
    public function getTable(): string;

    public function getAttributes(): array;

    public function getSqlDescription(): string;
}