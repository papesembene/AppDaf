<?php

namespace App\Migrations;

use PDO;
use PDOException;
use App\Src\Enums\ErrorEnum;
use App\Src\Enums\SuccessEnum;

class Migration
{
    private PDO $pdo;
    private string $dbName;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->dbName = $_ENV['DB_NAME'];
    }

    public function run(): void
    {
        echo "--- Lancement de la migration AppDAF ---\n\n";

        try {
            $this->migrateTables();
        } catch (PDOException $e) {
            exit(ErrorEnum::ECHEC_CREATION_TABLE->value . $e->getMessage() . "\n");
        }
    }

    private function migrateTables(): void
    {
        $this->pdo->beginTransaction();

        try {
            $this->createCitoyensTable();
            $this->createJournalTable();

            $this->pdo->commit();
            echo SuccessEnum::MIGRATION_SUCCESS->value . " '{$this->dbName}'.\n\n";
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function createCitoyensTable(): void
    {
        $this->pdo->exec("
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
            )
        ");
    }

    private function createJournalTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS journal (
                id SERIAL PRIMARY KEY,
                nci_recherche VARCHAR(20) NOT NULL,
                ip VARCHAR(45) NOT NULL,
                localisation VARCHAR(255),
                statut VARCHAR(20) NOT NULL CHECK (statut IN ('success', 'error')),
                message TEXT,
                date_recherche TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
}
