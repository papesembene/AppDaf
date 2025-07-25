<?php

namespace App\Core\abstract;

use App\Core\Database;
use PDO;
abstract class AbstractRepository extends Singleton{

    protected PDO $pdo;

   public function __construct(){

        $this->pdo = Database::getInstance()->connection();
    }
}