<?php

namespace Vendor\database;

/**
 * Class Manager
 * @package Vendor\database
 */
class Manager
{
    protected \PDO $db;

    public function __construct ()
    {
        $db = new Database();
        $this->setDb($db->getDb());
    }

    private function setDb (\PDO $db)
    {
        $this->db = $db;
    }
}