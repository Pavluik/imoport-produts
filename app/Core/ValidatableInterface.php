<?php

namespace App\Core;

use Exception;

interface ValidatableInterface
{
    /** @throws Exception */
    public function validate(): void;
}