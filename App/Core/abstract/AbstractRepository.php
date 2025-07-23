<?php

namespace App\Core\Abstract;

use App\Core\DataBase;
use App\Core\App;
use App\Core\Singleton;
use PDO;
abstract class AbstractRepository extends Singleton
{

    protected PDO $pdo;

   public function __construct()
   {
        // $this->pdo = DataBase::getInstance()->getConnection();
        $this->pdo = App::getDependency('DataBase')->getConnection();
    }
}


