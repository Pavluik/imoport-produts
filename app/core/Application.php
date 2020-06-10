<?php

namespace app\core;

use app\core\uploader\CsvFileUploader;
use app\core\storage\MysqlStorage;
use app\core\storage\StorageInterface;
use app\core\uploader\UploaderInterface;
use Exception;

class Application
{
    private UploaderInterface $uploader;
    private StorageInterface $storage;
    private string $errorMessage = '';

    public function __construct()
    {
        $config = require_once __DIR__.'/../config.php';

        try {
            $this->uploader = new CsvFileUploader();
            $this->storage = new MysqlStorage($config);
        } catch (Exception $exception) {
            $this->errorMessage = $exception->getMessage();
        }
    }

    public function run(): string
    {
        if ($this->errorMessage) {
            return $this->errorMessage;
        }

        $this->uploader->parseResource();

        return $this->storage->save(
            new ProductCollection($this->uploader->extractData())
        );
    }
}