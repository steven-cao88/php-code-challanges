<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

class MySQLConnection
{
    private PDO $pdo;

    public function __construct(string $host, string $db, string $user, string $password, string $charset = 'utf8mb4')
    {
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->pdo = new PDO($dsn, $user, $password, $options);
    }

    public function createUserTable(): void
    {
        $dropTableCommand = 'DROP TABLE IF EXISTS users';

        $this->pdo->exec($dropTableCommand);

        $createTableCommand = <<<SQL
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                surname VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT NOW(),
                updated_at DATETIME,
                deleted_at DATETIME,
                UNIQUE KEY(email)
            );
        SQL;

        $this->pdo->exec($createTableCommand);
    }
}
