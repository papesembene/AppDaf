<?php


use App\Router\Router;

Router::setDependencyMap([
    \App\Services\ICitoyensService::class => \App\Services\CitoyensService::class,
    
]);