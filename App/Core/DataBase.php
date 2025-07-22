<?php
namespace App\Core;

use PDO;

class DataBase {
    private static ?DataBase $instance = null;
    private ?PDO $pdo = null;

    private string $user;
    private string $password;
    private string $dsn;

    private function __construct() {
        $this->user = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->dsn = $_ENV['DSN'];
    }
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new DataBase();
        }
        return self::$instance;
    }

   public function connection(): PDO {
       if (is_null($this->pdo)) {
           $this->pdo = new PDO($this->dsn, $this->user, $this->password);
           $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       }
       return $this->pdo;
   }


      
    }


