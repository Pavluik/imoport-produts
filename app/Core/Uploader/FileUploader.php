<?php


namespace App\Core\Uploader;

use App\Core\ValidatableInterface;
use Exception;

abstract class FileUploader implements UploaderInterface, ValidatableInterface
{
    protected array $file;
    protected string $fileType;
    protected ?array $data = [];

    /**
     * FileUploader constructor.
     * @param array $file uploaded file from $_FILES
     * @throws Exception
     */
    public function __construct(array $file)
    {
        $this->file = $file;
        $this->validate();
    }

    /**
     * File existing and possible errors checking
     * @throws Exception
     */
    public function validate(): void
    {
        if (!$this->file || $this->file['error']) {
            throw new Exception("Can't upload a file", 422);
        }
    }


    /**
     * Add entity to the collection
     * @param array $data
     */
    protected function appendData(array $data)
    {
        $this->data[] = $data;
    }
}