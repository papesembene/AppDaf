<?php 

namespace App\Seeders;
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\DataBase;
use App\Translate\Fr\ErrorEnum;
use App\Translate\Fr\SuccessEnum;
use App\Translate\Fr\TextEnum;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use App\Services\CloudinaryUploaderService;

class Seeder
{
    private \PDO $pdo;
    private DataBase $database;
    private ?Cloudinary $cloudinary = null; // Ajout de la propriété
    private ?CloudinaryUploaderService $uploader = null;

    public function __construct()
    {
        $this->database = DataBase::getInstance();
    }

    public function run(): bool
    {
        if (!$this->checkDatabaseExists()) {
            echo ErrorEnum::ECHEC_CONNEXION->value . ErrorEnum::DATABASE_INEXISTANTE->value . "\n";
            return false;
        }

        try {
            $this->pdo = $this->database->getConnection();
            $this->pdo->beginTransaction(); 

            // Chargement de la configuration Cloudinary depuis .env
            $cloud_name = $_ENV['CLOUDINARY_NAME'] ?? '';
            $api_key = $_ENV['CLOUDINARY_KEY'] ?? '';
            $api_secret = $_ENV['CLOUDINARY_SECRET'] ?? '';

            Configuration::instance([
                'cloud' => [
                    'cloud_name' => $cloud_name,
                    'api_key' => $api_key,
                    'api_secret' => $api_secret,
                ],
                'url' => ['secure' => true]
            ]);
            $this->cloudinary = new Cloudinary(Configuration::instance());
            $this->uploader = new CloudinaryUploaderService($this->cloudinary);

            $this->seedCitoyens();
            $this->seedJournal();

            $this->pdo->commit();
            echo SuccessEnum::MIGRATION_SUCCESS->value . SuccessEnum::SUCCESS_INSERTION->value . "\n";
            return true;

        } catch (\PDOException $e) 
        {
            if (isset($this->pdo)) {
                $this->pdo->rollBack();
            }
            error_log(ErrorEnum::ERROR_SEEDING->value . $e->getMessage());
            echo ErrorEnum::ECHEC_CREATION_TABLE->value . " Erreur : " . $e->getMessage() . "\n";
            return false;
        }
    }

    private function checkDatabaseExists(): bool
    {
        try {
            // Essayer de se connecter à la base de données
            $this->pdo = $this->database->getConnection();
            
            // Vérifier si les tables existent
            $tables = ['citoyens', 'journal'];
            foreach ($tables as $table) {
                $result = $this->pdo->query("SELECT to_regclass('public.$table')");
                if ($result->fetchColumn() === null) {
                    echo ErrorEnum::TABLE_INEXISTANTE->value . "\n";
                    return false;
                }
            }
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    private function seedCitoyens(): void
    {
        echo TextEnum::INSERTION_DATA->value . "\n";

        $citoyens = [
            [
                'nci' => '1234567890123',
                'nom' => 'DIOP',
                'prenom' => 'Mamadou',
                'date_naissance' => '1990-01-01',
                'lieu_naissance' => 'Dakar',
                'url_recto' => 'https://senego.com/wp-content/uploads/2016/10/carte-biometrique.jpg',
                'url_verso' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT-5_n4r1OyfSOeb__RoAw6jDJbFxWfWee5uFP4Gvzg90Y8MRd-NXoetZz1UTqbdwyA-ks&usqp=CAU'
            ],
            [
                'nci' => '9876543210987',
                'nom' => 'FALL',
                'prenom' => 'Fatou',
                'date_naissance' => '1995-05-15',
                'lieu_naissance' => 'Saint-Louis',
                'url_recto' => 'https://www.divancitoyen.com/wp-content/uploads/2016/10/Rue-72.jpg',
                'url_verso' => 'https://dimelo-answers-production.s3.eu-west-1.amazonaws.com/268/a8c54ccc3a2074ae/img_20180720_163522_979_original.jpg?c43e482'
            ],
            [
                'nci' => '5555666677778',
                'nom' => 'NDIAYE',
                'prenom' => 'Aminata',
                'date_naissance' => '1988-12-25',
                'lieu_naissance' => 'Thiès',
                'url_recto' => 'https://www.divancitoyen.com/wp-content/uploads/2016/10/Rue-72.jpg',
                'url_verso' => 'https://dimelo-answers-production.s3.eu-west-1.amazonaws.com/268/a8c54ccc3a2074ae/img_20180720_163522_979_original.jpg?c43e482'
            ]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO citoyens (nci, nom, prenom, date_naissance, lieu_naissance, url_recto, url_verso)
            VALUES (:nci, :nom, :prenom, :date_naissance, :lieu_naissance, :url_recto, :url_verso)
        ");

        $updateStmt = $this->pdo->prepare("
            UPDATE citoyens SET url_recto = :url_recto, url_verso = :url_verso WHERE nci = :nci
        ");

        foreach ($citoyens as $citoyen) {
            try {
                $stmt->execute([
                    'nci' => $citoyen['nci'],
                    'nom' => $citoyen['nom'],
                    'prenom' => $citoyen['prenom'],
                    'date_naissance' => $citoyen['date_naissance'],
                    'lieu_naissance' => $citoyen['lieu_naissance'],
                    'url_recto' => $citoyen['url_recto'],
                    'url_verso' => $citoyen['url_verso']
                ]);

                // Upload via le service
                $urlRecto = $this->uploader->upload($citoyen['url_recto'], 'cni/recto');
                $urlVerso = $this->uploader->upload($citoyen['url_verso'], 'cni/verso');

                $updateStmt->execute([
                    'url_recto' => $urlRecto,
                    'url_verso' => $urlVerso,
                    'nci' => $citoyen['nci']
                ]);
            } catch (\Exception $e) {
                echo ErrorEnum::ERROR_UPLOAD_IMAGE->value . $e->getMessage() . "\n";
            }
        }

        echo count($citoyens) . " " . SuccessEnum::INSERTION_CITOYENS->value . "\n";
    }

    private function seedJournal(): void
    {
        echo SuccessEnum::INSERTION_JOURNAL->value . "\n";

        $journaux = [
            [
                'nci_recherche' => '1234567890123',
                'ip' => '192.168.1.1',
                'localisation' => 'Dakar, Sénégal',
                'statut' => 'success',
                'message' => SuccessEnum::SEARCH_SUCCESS->value
            ],
            [
                'nci_recherche' => '9999999999999',
                'ip' => '192.168.1.2',
                'localisation' => 'Thiès, Sénégal',
                'statut' => 'error',
                'message' => 'Erreur : ' . ErrorEnum::NCI_NOT_FOUND->value
            ],
            [
                'nci_recherche' => '2191020123456',
                'ip' => '192.168.1.3',
                'localisation' => 'Mbour, Sénégal',
                'statut' => 'success',
                'message' => SuccessEnum::SEARCH_SUCCESS->value
            ],
            [
                'nci_recherche' => '9876543210987',
                'ip' => '10.0.0.1',
                'localisation' => 'Saint-Louis, Sénégal',
                'statut' => 'success',
                'message' => SuccessEnum::CONSULTATION_SUCCESS->value
            ]
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO journal (nci_recherche, ip, localisation, statut, message)
            VALUES (:nci_recherche, :ip, :localisation, :statut, :message)
        ");

        foreach ($journaux as $journal) {
            $stmt->execute($journal);
        }

        echo count($journaux) . " " . SuccessEnum::INSERTION_JOURNAL_SUCCESS->value . "\n";
    }
}

// Point d'entrée
(new Seeder())->run();