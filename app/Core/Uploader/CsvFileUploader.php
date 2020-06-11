<?php

namespace App\Core\Uploader;


use Exception;

class CsvFileUploader extends FileUploader
{
    protected string $fileType = 'text/csv';

    /** @inheritDoc */
    public function parseResource(): self
    {
        if (($handle = fopen($this->file["tmp_name"], "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $this->appendData($data);
            }
            fclose($handle);
        }

        return $this;
    }

    /** @inheritDoc */
    public function extractData(): array
    {
        return $this->data;
    }

    /** @inheritDoc */
    public function validate(): void
    {
        parent::validate();
        if ($this->file['type'] !== $this->fileType) {
            throw new Exception("Can't load a file with a type ". $this->file['type'], 422);
        }
    }

}