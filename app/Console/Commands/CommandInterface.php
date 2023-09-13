<?php

declare(strict_types=1);

namespace App\Console\Commands;

interface CommandInterface
{
    /**
     * Run the command
     */
    public function run(): int;
}
