<?php

namespace App\Core\abstract;

use App\Core\Database;
use PDO;
abstract class AbstractRepository{

    protected PDO $pdo;

   public function __construct(){

        $this->pdo = Database::getInstance()->connection();
    }
}


