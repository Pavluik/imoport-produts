<?php

namespace App\Core\Uploader;

interface UploaderInterface
{
    /**
     * Parsing the information from resource
     * @return UploaderInterface
     */
    public function parseResource(): UploaderInterface;

    /**
     * Retrieves parsed data
     * @return array
     */
    public function extractData(): array;
}