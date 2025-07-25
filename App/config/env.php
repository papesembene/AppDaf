<?php



use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__,2));
$dotenv->load();

// Définition des constantes avec valeurs par défaut si non définies
define('DB_DRIVER', $_ENV['DB_DRIVER'] ?? 'pgsql');
define('DB_NAME', $_ENV['DB_NAME'] ?? $_ENV['DB_NAME_POSTGRES'] ?? 'TEST');

// Construction des DSN
$DB_DSN_POSTGRES = sprintf(
    "%s:host=%s;port=%s;dbname=%s",
    $_ENV['DB_DRIVER'],
    $_ENV['DB_HOST_POSTGRES'],
    $_ENV['DB_PORT_POSTGRES'],
    DB_NAME
);

define('DB_DSN_POSTGRES', $DB_DSN_POSTGRES);

 /// LES NOM DES  BASE DE DNS ;
$BASE_DSN_MYSQL="{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST_MYSQL']};port={$_ENV['DB_PORT_MYSQL']}";

$BASE_DSN_POSTGRES="{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST_POSTGRES']};port={$_ENV['DB_PORT_POSTGRES']}";

 define('BASE_DSN_POSTGRES', $BASE_DSN_POSTGRES);
 
 define('BASE_DSN_MYSQL', $BASE_DSN_MYSQL);
  



 if (DB_DRIVER==='pgsql')
 {
    define('DB_USER_POSTGRES', $_ENV['DB_USER_POSTGRES']);
    define('DB_PASS_POSTGRES', $_ENV['DB_PASS_POSTGRES']);


 }
elseif(DB_DRIVER==='mysql')
{
    
define('DB_USER_MYSQL', $_ENV['DB_USER_MYSQL']);
define('DB_PASS_MYSQL', $_ENV['DB_PASS_MYSQL']);

}
