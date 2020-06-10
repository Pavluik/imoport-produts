<?php


namespace app\core\uploader;

use app\core\Validatable;
use Exception;

abstract class FileUploader implements UploaderInterface, Validatable
{
    protected $file;
    protected string $fileType;
    protected ?array $data = [];
    protected string $attribute = 'file';

    /** @throws Exception */
    public function __construct()
    {
        $this->file = $_FILES[$this->attribute] ?? null;
        $this->validate();
    }

    /** @throws Exception */
    public function validate(): void
    {
        if (!$this->file || $this->file['error']) {
            throw new Exception("Can't upload a file", 422);
        }
    }

    protected function appendData(array $data)
    {
        $this->data[] = $data;
    }
}