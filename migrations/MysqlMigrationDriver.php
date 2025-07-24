<?php

namespace App\Migrations;

use PDO;
use Exception;

class MysqlMigrationDriver implements ImigrationDriver
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

    public function createTables(): void
    {
        // Vérifier si la table existe déjà
        $stmt = $this->pdo->prepare("SHOW TABLES LIKE 'citoyens'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "ℹ Les tables existent déjà. Aucune création nécessaire.\n";
            return;
        }

        $sql = file_get_contents(__DIR__ . '/../databases/script_create_mysql.sql');
        if ($this->pdo->exec($sql) === false) {
            throw new Exception("Échec lors de l'exécution du script SQL MySQL.");
        }
        echo "Tables MySQL créées avec succès.\n";
    }
}