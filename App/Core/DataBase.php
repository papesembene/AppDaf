<?php
namespace App\Core;

use PDO;
use PDOException;
use Dotenv\Dotenv;
use App\Src\Enums\SuccessEnum;
use App\Src\Enums\ErrorEnum;

class DataBase 
{
    private static ?DataBase $instance = null;
    private static ?PDO $pdo = null;
    private ?PDO $pdoServer = null;
    private static array $config=[];


      public static function getConnection(): PDO
    {
        if (self::$pdo === null) 
        {
            try {
                // 1. Cas Render (DATABASE_URL)
                $databaseUrl = getenv('DATABASE_URL') ?: ($_ENV['DATABASE_URL'] ?? null);
                if ($databaseUrl) {
                    $parts = parse_url($databaseUrl);
                    $driver = 'pgsql'; // Render fournit du PostgreSQL
                    $host = $parts['host'] ?? 'localhost';
                    $port = $parts['port'] ?? '5432'; 
                    $user = $parts['user'] ?? '';
                    $pass = $parts['pass'] ?? '';
                    $dbname = isset($parts['path']) ? ltrim($parts['path'], '/') : '';
                    $dsn = "$driver:host=$host;port=$port;dbname=$dbname";
                    self::$pdo = new PDO($dsn, $user, $pass);
                    self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return self::$pdo;
                }

                // 2. Cas local (variables d'environnement classiques)
                $driver = $_ENV['DB_DRIVER'];
                if ($driver === 'pgsql') {
                    $host = $_ENV['DB_HOST_POSTGRES'];
                    $port = $_ENV['DB_PORT_POSTGRES'];
                    $user = $_ENV['DB_USER_POSTGRES'];
                    $pass = $_ENV['DB_PASS_POSTGRES'];
                } elseif ($driver === 'mysql') {
                    $host = $_ENV['DB_HOST_MYSQL'];
                    $port = $_ENV['DB_PORT_MYSQL'];
                    $user = $_ENV['DB_USER_MYSQL'];
                    $pass = $_ENV['DB_PASS_MYSQL'];
                }
                $dbname = $_ENV['DB_NAME'];
                $dsn = "{$driver}:host={$host};port={$port};dbname={$dbname}";
                self::$pdo = new PDO($dsn, $user, $pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die("Erreur de connexion PDO : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
    
}