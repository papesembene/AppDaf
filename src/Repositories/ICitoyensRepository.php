<?php
namespace App\Repositories;
use App\Entities\CitoyensEntity;
interface ICitoyensRepository
{
    /**
     * Récupère un citoyen par son numéro de carte d'identité nationale (NCI).
     *
     * @param string $nci Le numéro de carte d'identité nationale.
     * @return mixed Retourne l'entité citoyen ou null si non trouvé.
     */

    public function selectBynum(string $nci):? CitoyensEntity;
    
    /**
     * Vérifie si une valeur est unique dans une colonne spécifique de la table citoyens.
     *
     * @param string $column Le nom de la colonne à vérifier.
     * @param string $value La valeur à vérifier pour l'unicité.
     * @return bool Retourne true si la valeur est unique, false sinon.
     */

    public function isUnique(string $column, string $value): bool;
    
    
}