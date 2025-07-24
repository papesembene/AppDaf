<?php 

namespace App\Translate\Fr;


enum ErrorEnum:string {

    case ECHEC_CONNEXION = 'Connexion au serveur PostgreSQL échouée :';
    case ECHEC_CREATE_DATABASE = 'Erreur lors de la création de la base :';
    case ECHEC_CONNEXION_BASE = 'Connexion à la base échouée :';
    case ECHEC_CREATION_TABLE = 'Erreur lors de la création de la table :';
    case ECHEC_INSERTION = 'Erreur lors de l\'insertion des données :';
    case DATABASE_INEXISTANTE = 'La base de données n\'existe pas.';
    case ERROR_SEEDING = 'Erreur lors du seeding.';
    case TABLE_INEXISTANTE = 'La table n\'existe pas.';
    case ERROR_UPLOAD_IMAGE = 'Erreur lors du téléchargement de l\'image :';
    case NCI_NOT_FOUND = 'NCI non trouvé.';
    case ERROR = 'Erreur :';



}