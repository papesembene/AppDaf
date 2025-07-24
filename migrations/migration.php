<?php
namespace App\Migrations;
require_once __DIR__ . '/../vendor/autoload.php';

use PDO;
use App\Core\DataBase;
use App\Translate\Fr\ErrorEnum;
use App\Translate\Fr\SuccessEnum;
use App\Translate\Fr\TextEnum;

class Migration
{
    private string $dbName;
    private DataBase $database;
    private string $driver;

    public function __construct()
    {
        $this->database = DataBase::getInstance();
        $this->dbName = $_ENV['DB_NAME'];
        $this->driver = strtolower($_ENV['DB_DRIVER']);
    }

    public function run(): void
    {
        echo "--- Lancement de la migration AppDAF ---\n\n";
        $this->checkOrCreateDatabase();
        $this->migrateTables();
    }

    private function checkOrCreateDatabase(): void
    {
        $pdo = $this->database->getServerConnection();
        try {
            if ($this->driver === 'pgsql') {
                $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '{$this->dbName}'");
                $exists = $stmt->fetch();
            } else { // mysql
                $stmt = $pdo->query("SHOW DATABASES LIKE '{$this->dbName}'");
                $exists = $stmt->fetch();
            }
            if (!$exists) {
                echo ErrorEnum::DATABASE_INEXISTANTE->value . "\n";
                $reponse = readline(TextEnum::QUESTION_CREATION_BASE->value);
                if (strtoupper(trim($reponse)) === 'O') {
                    if ($this->driver === 'pgsql') {
                        $pdo->exec("CREATE DATABASE \"{$this->dbName}\"");
                    } else {
                        $pdo->exec("CREATE DATABASE `{$this->dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    }
                    echo SuccessEnum::SUCCESS_CREATE_DATABASE->value . " '{$this->dbName}'.\n";
                } else {
                    echo TextEnum::ARRET_PROGRAMME->value . "\n";
                    exit;
                }
            }
        } catch (\PDOException $e) {
            exit(ErrorEnum::ECHEC_CREATE_DATABASE->value . $e->getMessage() . "\n");
        }
        $this->database->setDatabaseName($this->dbName);
    }

    private function migrateTables(): void
    {
        $pdo = $this->database->getConnection();
        try {
            $this->createCitoyensTable($pdo);
            $this->createJournalTable($pdo);
            echo SuccessEnum::MIGRATION_SUCCESS->value . " '{$this->dbName}'.\n\n";
        } catch (\PDOException $e) {
            exit(ErrorEnum::ECHEC_CREATION_TABLE->value . $e->getMessage() . "\n");
        }
    }

    private function createCitoyensTable(PDO $pdo): void
    {
        if ($this->driver === 'pgsql') {
            $sql = "
                CREATE TABLE IF NOT EXISTS citoyens (
                    id SERIAL PRIMARY KEY,
                    nci VARCHAR(20) UNIQUE NOT NULL,
                    nom VARCHAR(100) NOT NULL,
                    prenom VARCHAR(100) NOT NULL,
                    date_naissance DATE NOT NULL,
                    lieu_naissance VARCHAR(255) NOT NULL,
                    url_recto TEXT NOT NULL,
                    url_verso TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );
            ";
        } else { // mysql
            $sql = "
                CREATE TABLE IF NOT EXISTS citoyens (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nci VARCHAR(20) UNIQUE NOT NULL,
                    nom VARCHAR(100) NOT NULL,
                    prenom VARCHAR(100) NOT NULL,
                    date_naissance DATE NOT NULL,
                    lieu_naissance VARCHAR(255) NOT NULL,
                    url_recto TEXT NOT NULL,
                    url_verso TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";
        }
        $pdo->exec($sql);
    }

    private function createJournalTable(PDO $pdo): void
    {
        if ($this->driver === 'pgsql') {
            $sql = "
                CREATE TABLE IF NOT EXISTS journal (
                    id SERIAL PRIMARY KEY,
                    nci_recherche VARCHAR(20) NOT NULL,
                    ip VARCHAR(45) NOT NULL,
                    localisation VARCHAR(255),
                    statut VARCHAR(20) NOT NULL CHECK (statut IN ('success', 'error')),
                    message TEXT,
                    date_recherche TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );
            ";
        } else { // mysql
            $sql = "
                CREATE TABLE IF NOT EXISTS journal (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nci_recherche VARCHAR(20) NOT NULL,
                    ip VARCHAR(45) NOT NULL,
                    localisation VARCHAR(255),
                    statut ENUM('success', 'error') NOT NULL,
                    message TEXT,
                    date_recherche TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";
        }
        $pdo->exec($sql);
    }
}

// Point d'entrée
(new Migration())->run();