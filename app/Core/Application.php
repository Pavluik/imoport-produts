<?php

namespace App\Core;

use App\Core\Uploader\CsvFileUploader;
use App\Core\Storage\MysqlStorage;
use App\Core\Storage\StorageInterface;
use App\Core\Uploader\UploaderInterface;
use Exception;

class Application
{
    private UploaderInterface $uploader;
    private StorageInterface $storage;
    private string $errorMessage = '';

    /**
     * Application constructor.
     * @param array $config
     * @param array $file
     */
    public function __construct(array $config, array $file)
    {
        try {
            $this->uploader = new CsvFileUploader($file);
            $this->storage = new MysqlStorage($config);
        } catch (Exception $exception) {
            $this->errorMessage = $exception->getMessage();
        }
    }

    /**
     * Perform the import and return the result
     * @return string|array
     * @throws Exception
     */
    public function run()
    {
        if ($this->errorMessage) {
            return $this->errorMessage;
        }

        return $this->storage->save(
            new ProductCollection($this->uploader->parseResource()->extractData())
        );
    }
}