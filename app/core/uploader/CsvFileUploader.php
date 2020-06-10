<?php

namespace app\core\uploader;


use Exception;

class CsvFileUploader extends FileUploader
{
    protected string $fileType = 'text/csv';
    protected string $attribute = 'file-csv';

    public function parseResource(): CsvFileUploader
    {
        if (($handle = fopen($this->file["tmp_name"], "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $this->appendData($data);
            }
            fclose($handle);
        }

        return $this;
    }

    public function extractData(): array
    {
        return $this->data;
    }

    public function validate(): void
    {
        parent::validate();
        if ($this->file['type'] !== $this->fileType) {
            throw new Exception("Can't load a file with a type ". $this->file['type'], 422);
        }
    }

}