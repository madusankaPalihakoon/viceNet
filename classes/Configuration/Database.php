<?php
namespace Configuration;

use Functions\ErrorLogger;

class Database {
    private $host;
    private $dbname;
    private $user;
    private $password;
    private $conn;

    public function __construct($host, $dbname, $user, $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect()
    {
        try {
            $this->conn = new \PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->password);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (\PDOException $e) {
            ErrorLogger::logError($e ,null, 'database');
        }
    }

    public function disconnect()
    {
        $this->conn = null;
    }
}
