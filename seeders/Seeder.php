<?php
namespace App\Seeders;


use PDO;
use Exception;
use PDOException;

class Seeder
{
    private  PDO $pdo;
    private string $driver;
    public  function __construct(PDO $pdo)
    {
        $this->pdo=$pdo;
        $this->driver=$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }
    /**
     * Exécute le processus de semis de la base de données.
     * * @throws Exception Si une erreur survient lors de l'insertion des données.
     * @return void
     */
     public function  run ()
     {
        try 
        {    
            $this->seedDatabase();
            echo ( "insertion faite avec  succes ");
        } catch ( PDOException $e) 
        {
                die("echec insertion des donnes   ".$e->getMessage());
        }
     }

    /**
     * Insère des données de test dans la base de données.
     * @throws Exception Si une erreur survient lors de l'insertion des données.
     * @return int Le nombre de lignes insérées.
     */
    public  function seedDatabase()
    {
        $sql=match ($this->driver) {
            "mysql" => file_get_contents(__DIR__.'/../databases/insert_mysql.sql') ,
            "pgsql" => file_get_contents(__DIR__.'/../databases/insert_pgsql.sql') ,
            default => throw new Exception("le driver  n' existe pas  "),
 
        };
         return $this->pdo->exec($sql);
        
    }

}