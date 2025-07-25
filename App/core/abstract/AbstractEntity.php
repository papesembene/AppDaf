<?php

namespace App\Core\Abstract;

Interface AbstractEntity {
     public static function toObject(array $data):static;

     public  function toArray();

    
}
