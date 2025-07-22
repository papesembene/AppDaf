<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\DataBase;
use App\Migrations\Migration;
use App\Seeders\Seeder;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = DataBase::getInstance()->getConnection();

$migration = new Migration($db);
$migration->run();

$seeder = new Seeder();
$seeder->run();