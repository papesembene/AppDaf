<?php

namespace App\Migrations;


interface  IMigrationDriver
{
    public function createDatabase(): void;
    public function createTables(): void;
}