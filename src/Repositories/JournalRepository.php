<?php
namespace App\Repositories;
use App\Core\abstract;

class JournalRepository extends AbstractRepository {

    private string $table='journal';
    private static ?JournalRepository $instance = null ;
    public static function getInstance(): JournalRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new JournalRepository();
        }
        return self::$instance;
    }
    
    private function __construct()
    {
        parent::construct();
    }

     public function insertLog( string $nci_recherche, string $localisation, string $ip): ?JournalRepository
    {
            $query = "Insert INTO $this->table (nci_recherche, ip, localisation,  statut, date_recherche) 
                     VALUES  (:nci_recherche, :ip, :localisation, :statut, :date_recherche)";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([

                'nci_recherche' => $nci_recherche,
                'ip' => $ip,
                'localisation' => $localisation,
                "statut"=>'en Cours',
                ':date'=> date("Y-m-d H:i:s"),
            ]
            );
               

    }


}

