<?php
namespace App\Repositories;
use App\Core\abstract;

class CitoyensRepository extends AbstractRepository {

    private static ?CitoyensRepository $instance = null ;
    public static function getInstance(): CitoyensRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CitoyensRepository();
        }
        return self::$instance;
    }
    
    private function __construct()
    {
        parent::construct();
    }

     public function findByNum( string $numCni): ?CitoyensRepository
    {
            $query = "SELECT * 
            FROM citoyens c
            WHERE c.nci = :nci";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':nci' , $numCni);
            $stmt->execute();
            $array = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
            if (empty($array)) 
            {
                return null;
            }
        
            $citoyens = CitoyensEntity::toObject($array);
            return $citoyens ?: null;

    }


}

