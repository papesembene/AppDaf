<?php
namespace App\Controllers;

use App\Core\Abstract\AbstractController;

class CitoyensController extends AbstractController
{
    // Simulation d'une base de données de citoyens
    private $citoyens = [
        '1234567890123' => [
            'nci' => '1234567890123',
            'nom' => 'Diallo',
            'prenom' => 'Amadou',
            'date' => '1990-05-15',
            'lieu' => 'Dakar',
            'carte_identite_url' => 'https://cloud-storage.example.com/cartes/1234567890123.jpg'
        ],
        '9876543210987' => [
            'nci' => '9876543210987',
            'nom' => 'Ndiaye',
            'prenom' => 'Fatou',
            'date' => '1985-12-03',
            'lieu' => 'Saint-Louis',
            'carte_identite_url' => 'https://cloud-storage.example.com/cartes/9876543210987.jpg'
        ],
        '5555666677778' => [
            'nci' => '5555666677778',
            'nom' => 'Sow',
            'prenom' => 'Ibrahima',
            'date' => '1992-08-20',
            'lieu' => 'Thiès',
            'carte_identite_url' => 'https://cloud-storage.example.com/cartes/5555666677778.jpg'
        ]
    ];

    public function index() {
        $this->logRequest('GET', '/citoyens/nci', 'success');
        
        return $this->renderJson([
            'data' => array_values($this->citoyens),
            'statut' => 'success',
            'code' => 200,
            'message' => 'Liste des citoyens récupérée avec succès'
        ]);
    }

    public function findByNci($nci) {  
        if (!$nci) {
            return $this->renderJson([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Le paramètre NCI est requis'
            ], 400);
        }

        $statut = isset($this->citoyens[$nci]) ? 'success' : 'error';
        $this->logRequest('GET', "/citoyens/nci/{$nci}", $statut);

        if (isset($this->citoyens[$nci])) {
            return $this->renderJson([
                'data' => $this->citoyens[$nci],
                'statut' => 'success',
                'code' => 200,
                'message' => 'Le numéro de carte d\'identité a été retrouvé'
            ]);
        }

        return $this->renderJson([
            'data' => null,
            'statut' => 'error',
            'code' => 404,
            'message' => 'Le numéro de carte d\'identité non retrouvé'
        ], 404);
    }

    /**
     * Journalise les demandes de recherche
     * Critère d'acceptation: Toutes les demandes sont journalisées
     */
    private function logRequest($method, $endpoint, $statut) 
    {
        $logData = [
            'date' => date('Y-m-d'),
            'heure' => date('H:i:s'),
            'method' => $method,
            'endpoint' => $endpoint,
            'statut' => ucfirst($statut), // Success|Échec
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Simulation de l'écriture en log (en production, écrire dans un fichier ou BD)
        error_log("AppDAF Log: " . json_encode($logData, JSON_UNESCAPED_UNICODE));
    }


}
