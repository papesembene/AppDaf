<?php

namespace App\Seeders;

use App\Core\DataBase;
use App\Src\Enums\ErrorEnum;
use App\Src\Enums\SuccessEnum;

class Seeder
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DataBase::getInstance()->getConnection(); 
    }

    public function run(): bool
    {
        try {
            $this->pdo->beginTransaction(); 

            $this->seedCitoyens();
            $this->seedJournal();

            $this->pdo->commit();
            echo SuccessEnum::MIGRATION_SUCCESS->value . " Données insérées avec succès.\n";
            return true;

        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur lors du seeding: " . $e->getMessage());
            echo ErrorEnum::ECHEC_CREATION_TABLE->value . " Erreur : " . $e->getMessage() . "\n";
            return false;
        }
    }

    private function seedCitoyens(): void
    {
        $count = $this->pdo->query("SELECT COUNT(*) FROM citoyens")->fetchColumn();
if ($count > 0) return;

        $citoyens = [
            [
                'nci' => '1234567890123',
                'nom' => 'DIOP',
                'prenom' => 'Mamadou',
                'date_naissance' => '1990-01-01',
                'lieu_naissance' => 'Dakar',
                'url_recto' => 'storage/images/1234567890123_recto.jpg',
                'url_verso' => 'storage/images/1234567890123_verso.jpg'
            ],
            [
                'nci' => '9876543210987',
                'nom' => 'FALL',
                'prenom' => 'Fatou',
                'date_naissance' => '1995-05-15',
                'lieu_naissance' => 'Saint-Louis',
                'url_recto' => 'storage/images/9876543210987_recto.jpg',
                'url_verso' => 'storage/images/9876543210987_verso.jpg'
            ]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO citoyens (nci, nom, prenom, date_naissance, lieu_naissance, url_recto, url_verso)
            VALUES (:nci, :nom, :prenom, :date_naissance, :lieu_naissance, :url_recto, :url_verso)
        ");

        foreach ($citoyens as $citoyen) {
            $stmt->execute($citoyen);
        }
    }

    private function seedJournal(): void
    {
        $journaux = [
            [
                'nci_recherche' => '1234567890123',
                'ip' => '192.168.1.1',
                'localisation' => 'Dakar, Sénégal',
                'statut' => 'success',
                'message' => 'Recherche réussie'
            ],
            [
                'nci_recherche' => '9999999999999',
                'ip' => '192.168.1.2',
                'localisation' => 'Thiès, Sénégal',
                'statut' => 'error',
                'message' => 'NCI non trouvé'
            ]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO journal (nci_recherche, ip, localisation, statut, message)
            VALUES (:nci_recherche, :ip, :localisation, :statut, :message)
        ");

        foreach ($journaux as $journal) {
            $stmt->execute($journal);
        }
    }
}
