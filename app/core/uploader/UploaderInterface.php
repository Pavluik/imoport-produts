<?php

namespace app\core\uploader;

interface UploaderInterface
{
    public function parseResource(): UploaderInterface;

    public function extractData(): array;
}