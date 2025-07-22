<?php

namespace App\Core\Abstract;

use App\Core\Session;

abstract class AbstractController {

    protected Session $session;

    public function __construct() {
        $this->session = Session::getInstance();
    }

   

}