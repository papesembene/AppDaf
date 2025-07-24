<?php 

namespace App\Translate\Fr;

enum SuccessEnum:string {
    case MIGRATION_SUCCESS = 'Les migrations ont été exécutées avec succès dans la base';
    case SUCCESS_CREATE_DATABASE = 'Base de données créée avec succès';
    case SUCCESS_CONNECTION = 'Connexion réussie à la base de données';
    case SUCCESS_INSERTION = 'Données insérées avec succès.';
    case INSERTION_CITOYENS = 'citoyens insérés avec succès.';
    case INSERTION_JOURNAL = 'Insertion des données dans la table journal...';
    case SEARCH_SUCCESS = 'Recherche réussie.';
    case CONSULTATION_SUCCESS = 'Consultation réussie.';
    case INSERTION_JOURNAL_SUCCESS = 'Entrées de journal insérées avec succès.';
    case SUCCESS = 'Succès';
}

