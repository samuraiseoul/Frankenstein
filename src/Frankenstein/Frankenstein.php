<?php

namespace Frankenstein;

use Frankenstein\DatabaseDriver\CreateDriver;
use Frankenstein\DatabaseDriver\InsertDriver;

class Frankenstein
{
    private $username;
    
    private $password;
    
    private $database;
    
    /** @var  InsertDriver */
    private $insertDriver;
    private $createDriver;
    
    public function __construct($username = 'root', $password = '', $database)
    {
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    
        $this->insertDriver = new InsertDriver($username, $password, $database);
        $this->createDriver = new CreateDriver($username, $password, $database);
    }
    
    public function sparkOfLife($tableName, $columns)
    {
        $this->createDriver->create($tableName, $columns);
    }
    
    public function itsAlive($table, $columnValues)
    {
        $this->insertDriver->insert($table, $columnValues);
    }
}