<?php

use Dotenv\Dotenv;

// Charger le .env seulement en local
$dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__, 2));
$dotenv->safeLoad(); // safeLoad pour éviter erreur si pas de fichier .env

// Si DATABASE_URL est défini (Render), on le parse
if (isset($_ENV['DATABASE_URL'])) {
    $url = parse_url($_ENV['DATABASE_URL']);

    define('DB_DRIVER', 'pgsql');
    define('DB_NAME', ltrim($url['path'], '/'));
    define('DB_USER_POSTGRES', $url['user']);
    define('DB_PASS_POSTGRES', $url['pass']);

    define('DB_DSN_POSTGRES', sprintf(
        "pgsql:host=%s;port=%s;dbname=%s",
        $url['host'],
        $url['port'],
        ltrim($url['path'], '/')
    ));

    define('BASE_DSN_POSTGRES', sprintf(
        "pgsql:host=%s;port=%s",
        $url['host'],
        $url['port']
    ));

} else {
    // Local : lire depuis .env
    define('DB_DRIVER', $_ENV['DB_DRIVER']);
    define('DB_NAME', $_ENV['DB_NAME']);

    $DB_DSN_POSTGRES = "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST_POSTGRES']};port={$_ENV['DB_PORT_POSTGRES']};dbname={$_ENV['DB_NAME']}";
    $DSN_MYSQL = "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST_MYSQL']};port={$_ENV['DB_PORT_MYSQL']};dbname={$_ENV['DB_NAME']}";

    define('DB_DSN_POSTGRES', $DB_DSN_POSTGRES);
    define('DB_DSN_MYSQL', $DSN_MYSQL);

    $BASE_DSN_MYSQL = "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST_MYSQL']};port={$_ENV['DB_PORT_MYSQL']}";
    $BASE_DSN_POSTGRES = "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST_POSTGRES']};port={$_ENV['DB_PORT_POSTGRES']}";

    define('BASE_DSN_POSTGRES', $BASE_DSN_POSTGRES);
    define('BASE_DSN_MYSQL', $BASE_DSN_MYSQL);

    if (DB_DRIVER === 'pgsql') {
        define('DB_USER_POSTGRES', $_ENV['DB_USER_POSTGRES']);
        define('DB_PASS_POSTGRES', $_ENV['DB_PASS_POSTGRES']);
    } elseif (DB_DRIVER === 'mysql') {
        define('DB_USER_MYSQL', $_ENV['DB_USER_MYSQL']);
        define('DB_PASS_MYSQL', $_ENV['DB_PASS_MYSQL']);
    }
}
