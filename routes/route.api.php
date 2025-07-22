<?php

use App\Controllers\CitoyensController;

return [
    'GET /' => [
        'controller' => CitoyensController::class,
        'action' => 'index', 
    ],
    'GET /citoyens' => [
        'controller' => CitoyensController::class,
        'action' => 'index',
    ],
    'GET /citoyens/nci/{nci}' => [  
        'controller' => CitoyensController::class,
        'action' => 'findByNci',
    ],
];
