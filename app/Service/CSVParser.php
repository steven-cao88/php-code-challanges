<?php

declare(strict_types=1);

namespace App\Service;

use Exception;

class CSVParser
{
    public function __construct(private string $file)
    {
        //
    }

    public function readFile(): array
    {
        $data = [];

        if (!file_exists($this->file)) {
            throw new Exception('File ' . $this->file . ' does not exist in current directory');
        }

        $handle = fopen($this->file, "r");

        if ($handle === false) {
            throw new Exception('Cannot open file ' . $this->file . ' in read mode');
        }

        $row = null;

        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            $data[] = $row;
        }

        fclose($handle);

        return $data;
    }
}
