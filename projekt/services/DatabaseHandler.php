<?php

namespace Services;
class DatabaseHandler
{
    private $host = '127.0.0.1';
    private $username = 'marcin';
    private $password = '1';
    private $dbname = 'hotele2';
    private $conn;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

        try {
            $this->conn = new \PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \Exception("Błąd połączenia z bazą danych: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function executeQuery($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

            return $stmt;
        } catch (\PDOException $e) {
            throw new \Exception("Błąd zapytania SQL: " . $e->getMessage());
        }
    }
}