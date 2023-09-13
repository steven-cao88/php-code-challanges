<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Service\CSVParser;
use Exception;

class UploadUserCommand extends AbstractCommand
{
    private array $userData;

    protected function process(): int
    {
        // logic to be implemented here
        $this->loadUsersFromFile();

        $this->console->line('done');

        return 0;
    }

    protected function getShortOptionDefinitions(): string
    {
        return 'u:p:h:';
    }

    protected function getLongOptionDefinitions(): array
    {
        return [
            'file:',
            'create_table',
            'dry_run',
        ];
    }

    protected function getHelpGuide(): array
    {
        return [
            'This Command will import user data from provided csv file',
            'It should include these command line options (directives):',
            '--file [csv file name] – this is the name of the CSV to be parsed',
            '--create_table – this will cause the MySQL users table to be built (and no further action will be taken)',
            '--dry_run – this will be used with the --file directive in case we want to run the script but not insert into the DB.',
            'All other functions will be executed, but the database won\'t be altered',
            '-u – MySQL username',
            '-p – MySQL password',
            '-h – MySQL host',
            '--help – which will output the above list of directives with details.'
        ];
    }

    protected function getRequiredOptions(): array
    {
        return [
            'file' => 'file name',
            'u' => 'MySQL username',
            'p' => 'MySQL password',
            'h' => 'MySQL host',
        ];
    }

    private function loadUsersFromFile(): void
    {
        $csvParser = new CSVParser($this->options['file']);

        try {
            $this->userData = $csvParser->readFile();
        } catch (Exception $e) {
            $this->console->line($e->getMessage());

            exit(1);
        }
    }
}
