<?php

require_once __DIR__ . '/../vendor/autoload.php';


if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

require_once __DIR__ . '/../App/config/bootstrap.php';
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
  
    $code = (int)$e->getCode();
    if ($code < 100 || $code > 599) {
        $code = 500;
    }
    http_response_code($code);
    echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => $code,
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
}


