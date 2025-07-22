<?php
namespace App\Migrations;

 require_once __DIR__ . '/../vendor/autoload.php';

use PDO;
use App\Core\DataBase;
use App\Src\Enums\ErrorEnum;
use App\Src\Enums\TextEnum;
use App\Src\Enums\SuccessEnum;

class Migration
{
    private string $dbName;
    private DataBase $database;

    public function __construct()
    {
        $this->database = DataBase::getInstance();
    }

    public function run(): void
    {
        
        echo "--- Lancement de la migration AppDAF ---\n\n";
        
        $this->handleDatabaseCreation();
        $this->migrateTables();
    }

    private function handleDatabaseCreation(): void
    {
        $reponse = strtolower(trim(readline(TextEnum::QUESTION_BASE_EXISTANTE->value)));

        if ($reponse === 'oui' || $reponse === 'o') {
            $this->dbName = trim(readline(TextEnum::QUESTION_NOM_BASE->value));
        } else {
            $this->dbName = trim(readline(TextEnum::READLINE_NOM_NEW_BASE->value));
            $this->createDatabase();
        }

        $this->database->setDatabaseName($this->dbName);
    }

    private function createDatabase(): void
    {
        $pdo = $this->database->getServerConnection();
        
        try {
            $pdo->exec("CREATE DATABASE \"{$this->dbName}\"");
            echo SuccessEnum::SUCCESS_CREATE_DATABASE->value . " '{$this->dbName}'.\n";
        } catch (\PDOException $e) {
            exit(ErrorEnum::ECHEC_CREATE_DATABASE->value . $e->getMessage() . "\n");
        }
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
        $pdo->exec("
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
        ");
    }

    private function createJournalTable(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS journal (
                id SERIAL PRIMARY KEY,
                nci_recherche VARCHAR(20) NOT NULL,
                ip VARCHAR(45) NOT NULL,
                localisation VARCHAR(255),
                statut VARCHAR(20) NOT NULL CHECK (statut IN ('success', 'error')),
                message TEXT,
                date_recherche TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");
    }
}

// Point d'entrée
(new Migration())->run();