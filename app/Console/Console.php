<?php

declare(strict_types=1);

namespace App\Console;

class Console
{
    public function __construct()
    {
    }

    /**
     * Get option values from command
     */
    public function getOptions(string $shortOptions = '', array $longOptions = []): array
    {
        return getopt(
            short_options: $shortOptions,
            long_options: $longOptions,
        );
    }

    public function line(string $line): void
    {
        print $line;
    }

    public function lines(array|string $lines): void
    {
        $lines = is_array($lines) ? \implode(PHP_EOL, $lines) : $lines;

        $this->line($lines);

        $this->line(PHP_EOL);
    }
}
