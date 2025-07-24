<?php
namespace App\Migrations;

use PDO;
use PDOException;
use Exception;

class PgsqlMigrationDriver implements IMigrationDriver
{
    private PDO $pdo;
    private string $dbName;
    private string $user;
    private string $password;

    public function __construct(PDO $pdo, string $dbName, string $user, string $password)
    {
        $this->pdo = $pdo;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
    }

    public function createDatabase(): void
    {
        try
         {
           
            $pdoAdmin = new PDO(
                "pgsql:host=127.0.0.1;dbname=postgres;port=5432",
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

           
            $stmt = $pdoAdmin->prepare("SELECT 1 FROM pg_database WHERE datname = :db_name");
            $stmt->execute([':db_name' => $this->dbName]);

            if ($stmt->fetch()) {
                echo "ℹ La base de données PostgreSQL '{$this->dbName}' existe déjà.\n";
            } else {
                $pdoAdmin->exec("CREATE DATABASE {$this->dbName}");
                echo "Base de données PostgreSQL '{$this->dbName}' créée avec succès.\n";
            }

            // Reconnexion à la base nouvellement créée
            $this->pdo = new PDO(
                "pgsql:host=127.0.0.1;dbname={$this->dbName};port=5432",
                $this->user,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de la base PostgreSQL : " . $e->getMessage());
        }
    }

    public function createTables(): void
    {
        // Vérifier si la table existe déjà
        $stmt = $this->pdo->prepare("SELECT to_regclass('public.citoyens') AS table_name");
        $stmt->execute();
        $result = $stmt->fetch();
        if (!empty($result['table_name'])) {
            echo "ℹ Les tables existent déjà. Aucune création nécessaire.\n";
            return;
        }

        $sql = file_get_contents(__DIR__ . '/../databases/script_create_pgsql.sql');
        if ($this->pdo->exec($sql) === false) {
            throw new Exception("Échec lors de l'exécution du script SQL PostgreSQL.");
        }
        echo "Tables PostgreSQL créées avec succès.\n";
    }
}