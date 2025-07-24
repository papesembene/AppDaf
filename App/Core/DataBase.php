<?php
namespace App\Core;

use PDO;
use PDOException;
use Dotenv\Dotenv;
use App\Translate\Fr\ErrorEnum;
use App\Translate\Fr\SuccessEnum;

class DataBase
{
    private static ?DataBase $instance = null;
    private ?PDO $pdo = null;
    private ?PDO $pdoServer = null;
    private array $config;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->loadEnvironment();
        $this->initializeDatabaseParams();
    }

    private function loadEnvironment(): void
{
    if (!isset($_ENV['DB_HOST'])) {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); 
        $dotenv->load();
    }
}

    private function initializeDatabaseParams(): void
    {
        $this->config = [
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'driver' => $_ENV['DB_DRIVER'],
            'dbname' => $_ENV['DB_NAME'] ?? 'postgres'
        ];
    }

    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            $this->pdo = $this->createConnection($this->config['dbname']);
        }
        return $this->pdo;
    }

    public function getServerConnection(): PDO
    {
        if ($this->pdoServer === null) {
            $this->pdoServer = $this->createConnection('postgres');
        }
        return $this->pdoServer;
    }

    private function createConnection(string $dbname): PDO
    {
        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s',
            $this->config['driver'],
            $this->config['host'],
            $this->config['port'],
            $dbname
        );

        try {
            $pdo = new PDO($dsn, $this->config['user'], $this->config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo SuccessEnum::SUCCESS_CONNECTION->value . " ($dbname)\n";
            return $pdo;
        } catch (PDOException $e) {
            exit(ErrorEnum::ECHEC_CONNEXION->value . $e->getMessage() . "\n");
        }
    }

    public function setDatabaseName(string $dbname): void
    {
        $this->config['dbname'] = $dbname;
        $this->pdo = null; 
    }
}