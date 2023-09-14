<?php

declare(strict_types=1);

use App\Console\Commands\UploadUserCommand;
use PHPUnit\Framework\TestCase;

final class UploadUserCommandTest extends TestCase
{
    public function testCanThrowExceptionOnMissingFile(): void
    {
        $command = new UploadUserCommand();

        $this->expectOutputString('Missing file name' . PHP_EOL);

        $command->run();
    }
}
