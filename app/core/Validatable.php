<?php

namespace app\core;

use Exception;

interface Validatable
{
    /** @throws Exception */
    public function validate(): void;
}