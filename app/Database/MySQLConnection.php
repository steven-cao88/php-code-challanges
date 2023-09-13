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
}
