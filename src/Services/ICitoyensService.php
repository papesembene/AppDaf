<?php

namespace App\Services;

use App\Entities\CitoyensEntity;

interface  ICitoyensService
{

    /**
     * Récupère un citoyen par son numéro de carte d'identité nationale.
     *
     * @param string $numcni Le numéro de carte d'identité nationale.
     * @return CitoyensEntity|null Retourne l'entité citoyen ou null si non trouvé.
     */
    public function getCitoyenByNumCni(string $numcni): ?CitoyensEntity;
   



}

    




