<?php
namespace App\Core;

use PDO;
use App\Src\Enums\SuccessEnum;

class DataBase
{
    private static ?DataBase $instance = null;
    private ?PDO $pdo = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->initializeConnection();
    }

    private function initializeConnection(): void
    {
        $this->pdo = new PDO(
            $_ENV['DSN'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo SuccessEnum::SUCCESS_CONNECTION->value . "\n";
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}