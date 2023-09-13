<?php

declare(strict_types=1);

use App\Service\CSVParser;
use PHPUnit\Framework\TestCase;

final class CSVParserTest extends TestCase
{
    private const NON_EXIST_FILE = 'non-exist.csv';
    private const READABLE_FILE = 'readable.csv';

    public function testCanThrowExeptionOnNonExistFile(): void
    {
        $csvParser = new CSVParser(self::NON_EXIST_FILE);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('File ' . self::NON_EXIST_FILE . ' does not exist in current directory');

        $csvParser->readFile();
    }

    public function testCanReadFileProbably(): void
    {
        $this->createReadableFile();

        $csvParser = new CSVParser(self::READABLE_FILE);

        $data = $csvParser->readFile();

        $this->assertEqualsCanonicalizing(
            [
                ['name', 'surname', 'email'],
                ['John', 'smith', 'jsmith@gmail.com']
            ],
            $data,
        );

        $this->deleteReadableFile();
    }

    private function createReadableFile(): void
    {
        $readableFile = fopen(self::READABLE_FILE, "w");

        fwrite(
            $readableFile,
            'name,surname,email' . PHP_EOL .
                'John,smith,jsmith@gmail.com'
        );

        fclose($readableFile);
    }

    private function deleteReadableFile(): void
    {
        unlink(self::READABLE_FILE);
    }
}
