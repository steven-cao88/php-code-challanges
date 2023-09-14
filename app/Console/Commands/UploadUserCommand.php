<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Database\MySQLConnection;
use App\Service\CSVParser;
use Exception;
use PDOException;

class UploadUserCommand extends AbstractCommand
{
    private array $userData;

    private MySQLConnection $db;

    protected function process(): int
    {
        $this->loadUsersFromFile();

        $errors = $this->validateUsers();

        if (!empty($errors)) {
            $this->console->lines($errors);
        }

        if ($this->isDryRun()) {
            $this->console->line('Dry run mode detected. No change will be made to the database.' . PHP_EOL);

            return 0;
        }

        $this->setupDatabaseConnection();

        if ($this->isCreateTableMode()) {
            $this->console->line('Create/rebuild users table mode detected. No further change after creating/rebuilding table.' . PHP_EOL);

            $this->createTable();

            return 0;
        }

        $this->formatUsers();

        $this->addUsers();

        $this->console->line('Added ' . count($this->userData) . ' users');

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
            '--file [csv file name] - this is the name of the CSV to be parsed',
            '--create_table - this will cause the MySQL users table to be built (and no further action will be taken)',
            '--dry_run - this will be used with the --file directive in case we want to run the script but not insert into the DB.',
            'All other functions will be executed, but the database won\'t be altered',
            '-u - MySQL username',
            '-p - MySQL password',
            '-h - MySQL host',
            '--help - which will output the above list of directives with details.'
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

    private function validateUsers(): array
    {
        $errors = [];
        $validatedUsers = [];

        foreach ($this->userData as $index => $userData) {
            $email = strtolower(trim($userData[2] ?? ''));

            // skip header row
            if ($email === 'email') {
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // row in csv file starts at 1 while in php, it starts at 0
                $errors[] = 'Email: ' . $email . ' at row ' . ($index + 1) . ' has invalid format.';

                continue;
            }

            $validatedUsers[] = $userData;
        }

        $this->userData = $validatedUsers;

        return $errors;
    }

    private function formatUsers(): void
    {
        $formattedUsers = [];

        foreach ($this->userData as $index => $userData) {
            // skip header row
            if (strtolower(trim($userData[0] ?? '')) === 'name') {
                continue;
            }

            $name = ucfirst(strtolower(trim($userData[0] ?? '')));
            if (empty($name)) {
                continue;
            }

            $surname = ucfirst(strtolower(trim($userData[1] ?? '')));
            if (empty($surname)) {
                continue;
            }

            $email = strtolower(trim($userData[2] ?? ''));
            if (empty($email)) {
                continue;
            }

            $formattedUsers[] = [
                $name,
                $surname,
                $email,
            ];
        }

        $this->userData = $formattedUsers;
    }

    private function isDryRun(): bool
    {
        return array_key_exists('dry_run', $this->options);
    }

    private function setupDatabaseConnection(): void
    {
        try {
            $this->db = new MySQLConnection(
                host: $this->options['host'] ?? '',
                db: 'test', // this is an assumption as it is not mentioned in specs
                user: $this->options['u'] ?? '',
                password: $this->options['p'] ?? '',
            );
        } catch (PDOException $e) {
            $this->console->line($e->getMessage());
            exit(1);
        }
    }

    private function isCreateTableMode(): bool
    {
        return array_key_exists('create_table', $this->options);
    }

    private function createTable(): void
    {
        try {
            $this->db->createUsersTable();
        } catch (PDOException $e) {
            $this->console->line($e->getMessage());
            exit(1);
        } finally {
            exit(0);
        }
    }

    private function addUsers(): void
    {
        try {
            $this->db->insertIntoUsersTable($this->userData);
        } catch (PDOException $e) {
            $this->console->line($e->getMessage());
            exit(1);
        }
    }
}
