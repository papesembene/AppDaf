<?php

namespace App\Translate\Fr;


enum TextEnum:string {
    case QUESTION_BASE_EXISTANTE = 'Avez-vous une base de données existante ? (O/N) : ';
    case QUESTION_NOM_BASE = 'Entrez le nom de la base de données : ';
    case READLINE_NOM_NEW_BASE = 'Entrez le nom de la nouvelle base à créer : ';
    case INSERTION_DATA = 'Insertion des données dans la base de données...';
    case ARRET_PROGRAMME = 'Arrêt du programme. La base de données est requise.';
    case QUESTION_CREATION_BASE = 'Voulez-vous la créer ? (O/N) : ';
}