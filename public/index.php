<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\DataBase;
use App\Migrations\Migration;
use App\Seeders\Seeder;


// Charger les variables d'environnement
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Connexion à la BDD
$db = DataBase::getInstance()->getConnection();

// Exécuter la migration
$migration = new Migration($db);
$migration->run();

$seeder = new Seeder();
$seeder->run();