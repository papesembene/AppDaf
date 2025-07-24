<?php
namespace App\Migrations;

use Exception;
use PDO;
use PDOException;

class Migration
{
    private IMigrationDriver $driver;
    

    public function __construct(IMigrationDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Exécute les migrations pour créer la base de données et les tables.
     *
     * @throws Exception Si une erreur survient lors de la création de la base ou des tables.
     */
    public function run(): void
    {
        $this->driver->createDatabase();
        $this->driver->createTables();
      
    }


}
