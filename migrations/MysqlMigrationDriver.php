<?php

namespace App\Migrations;

use PDO;
use Exception;


class MysqlMigrationDriver implements IMigrationDriver
{
    private PDO $pdo;
    private string $dbName;

    public function __construct(PDO $pdo, string $dbName)
    {
        $this->pdo = $pdo;
        $this->dbName = $dbName;
    }

    public function createDatabase(): void
    {
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS {$this->dbName}");
        $this->pdo->exec("USE {$this->dbName}");
        echo "Base MySQL '{$this->dbName}' prête.\n";
    }

    public function createTables(string $sqlFile): void
    {
        $sql = file_get_contents($sqlFile);
        if ($this->pdo->exec($sql) === false) {
            throw new Exception("Échec lors de l'exécution du script SQL MySQL.");
        }
        echo "Tables MySQL créées (ou déjà existantes) avec succès.\n";
    }
}