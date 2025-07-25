<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../App/config/bootstrap.php';
use Dotenv\Dotenv;

$dotenvPath = realpath(__DIR__ . '/..');
if (!$dotenvPath) {
    die('Impossible de résoudre le chemin racine de l\'application');
}
$dotenv = Dotenv::createUnsafeImmutable($dotenvPath);
try {
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // En production, utilisez les variables d'environnement du système
    if (getenv('APP_ENV') !== 'production') {
        throw $e;
    }
}

use App\Router\Router;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$routes = require __DIR__ . '/../routes/route.api.php';


try {
    Router::resolve($routes);
} catch (\Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => $e->getCode() ?: 500,
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
}


