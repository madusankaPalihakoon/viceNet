<?php
namespace ViceNet\Database;

class PDO_Config {
    private $pdo;

    public function __construct()
    {
        $dsn =  'mysql:host=localhost;dbname=vicenet;charset=utf8mb4';
        $username = 'root';
        $password = '';
        $this->pdo = new \PDO($dsn, $username, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);        
    }

    public function getConnection(): \PDO
    {
        return $this->pdo;
    }

    public function closeConnection(): void
    {
        $this->pdo = null;
    }
}
