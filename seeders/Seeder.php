<?php
namespace App\Seeders;


use PDO;
use Exception;
use PDOException;
use Cloudinary\Cloudinary;

class Seeder
{
    private  PDO $pdo;
    private string $driver;
    private Cloudinary $cloudinary;

    public  function __construct(PDO $pdo)
    {
        $this->pdo=$pdo;
        $this->driver=$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        // Initialisation Cloudinary avec les variables d'env
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
                'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
            ],
        ]);
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
        // Exemple : upload d'une image locale ou distante et insertion
        $citoyens = [
            [
                'nci' => '1234567890123',
                'nom' => 'NDIAYE',
                'prenom' => 'Mamadou',
                'date_naissance' => '1990-05-15',
                'lieu_naissance' => 'Dakar',
                'url_recto' => 'https://static.pratique.fr/images/unsized/de/demande-carte-nationale-identite-cni.jpg',
                'url_verso' => 'https://www.roissyenbrie77.fr/wp-content/uploads/2022/02/identite-esa-scaled.jpg',
            ],
            
        ];

        foreach ($citoyens as $citoyen) {
            // Upload recto
            $uploadRecto = $this->cloudinary->uploadApi()->upload($citoyen['url_recto'], [
                'folder' => 'appdaf/citoyens'
            ]);
            $urlRectoCloudinary = $uploadRecto['secure_url'] ?? $citoyen['url_recto'];

            // Upload verso
            $uploadVerso = $this->cloudinary->uploadApi()->upload($citoyen['url_verso'], [
                'folder' => 'appdaf/citoyens'
            ]);
            $urlVersoCloudinary = $uploadVerso['secure_url'] ?? $citoyen['url_verso'];

            // Insertion en base
            $sql = "INSERT INTO citoyens (nci, nom, prenom, date_naissance, lieu_naissance, url_recto, url_verso)
                    VALUES (:nci, :nom, :prenom, :date_naissance, :lieu_naissance, :url_recto, :url_verso)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nci' => $citoyen['nci'],
                'nom' => $citoyen['nom'],
                'prenom' => $citoyen['prenom'],
                'date_naissance' => $citoyen['date_naissance'],
                'lieu_naissance' => $citoyen['lieu_naissance'],
                'url_recto' => $urlRectoCloudinary,
                'url_verso' => $urlVersoCloudinary,
            ]);
        }

        echo "Insertion des citoyens avec images Cloudinary réussie.\n";
    }
}