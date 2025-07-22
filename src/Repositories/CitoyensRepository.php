<?php
namespace App\Repositories;
use App\Core\Abstract\AbstractRepository;
use App\Entities\CitoyensEntity;

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
        parent::__construct();
    }

     public function findByNum( string $numCni): ?CitoyensEntity
    {
            $query = "SELECT * 
            FROM citoyens c
            WHERE c.nci = :nci";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':nci' , $numCni);
            $stmt->execute();
            $array = $stmt->fetch(\PDO::FETCH_ASSOC);
           
            if (empty($array)) 
            {
                return null;
            }
        
            $citoyens = CitoyensEntity::toObject($array);
            return $citoyens ?: null;

    }
   
    public function isUnique(string $column, string $value): bool
    {
        $sql = "SELECT COUNT(*) FROM citoyens WHERE $column = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':value' => $value]);
        return $stmt->fetchColumn() == 0;
    }


}

