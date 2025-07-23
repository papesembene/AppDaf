<?php
namespace AApp\Repositories;

use  App\Entities\JournalEntity;


interface IJournalInterface
{
    /**
     * Insère une nouvelle entrée de journal dans la base de données.
     * @param JournalEntity $journalEntity L'entité journal à insérer.
     * @return int Retourne l'ID de l'entrée de journal insérée.
     */
    public function insert(JournalEntity $journal): int;

}

 

