<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Console;
use Exception;

abstract class AbstractCommand implements CommandInterface
{
    protected Console $console;
    protected array $options = [];

    protected const HELP_OPTION = 'help';

    public function __construct()
    {
        $this->console = new Console();

        $this->setOptions();
    }

    public function run(): int
    {
        if ($this->askForHelp()) {
            $this->printHelp();

            return 0;
        }

        try {
            $this->validateOptions();
        } catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;

            exit(1);
        }

        return $this->process();
    }

    /**
     * Go through command logic defined in extended classes
     */
    abstract protected function process(): int;

    abstract protected function getShortOptionDefinitions(): string;

    abstract protected function getLongOptionDefinitions(): array;

    abstract protected function getHelpGuide(): string|array;

    protected function setOptions(): void
    {
        $this->options = $this->console->getOptions(
            shortOptions: $this->getShortOptionDefinitions(),
            longOptions: $this->getLongOptionDefinitionsWithHelp(),
        );
    }

    protected function getRequiredOptions(): array
    {
        return [];
    }

    protected function validateOptions(): void
    {
        $options = $this->options;

        foreach ($this->getRequiredOptions() as $requiredOption => $optionName) {
            if (!array_key_exists($requiredOption, $options) || ($options[$requiredOption] ?? false) === false) {
                throw new Exception('Missing ' . $optionName);
            }
        }
    }

    private function getLongOptionDefinitionsWithHelp(): array
    {
        $options = $this->getLongOptionDefinitions();

        if (!array_key_exists(static::HELP_OPTION, $options)) {
            $options[] = static::HELP_OPTION;
        }

        return $options;
    }

    private function askForHelp(): bool
    {
        return array_key_exists(static::HELP_OPTION, $this->options);
    }

    private function printHelp(): void
    {
        $this->console->lines($this->getHelpGuide());
    }
}
