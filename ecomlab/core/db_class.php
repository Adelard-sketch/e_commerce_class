<?php

// Pull in the connection settings (DATABASE, SERVER, USERNAME, PASSWD constants)
require_once "db_cred.php";

class Database
{
    
    private $conn;
    private $host = SERVER;
    private $dbname = DATABASE;
    private $username = USERNAME;
    private $password = PASSWD;

  
    public function __construct()
    {
        try {

            // which driver, host and database to use.
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );

            // Show database errors as exceptions
            // (instead of PHP warnings, which are easy to miss)
            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $e) {

            // If the connection fails (wrong credentials, MySQL not
            // running, etc.) stop the script and show why.
            die("Database connection failed: " . $e->getMessage());

        }
    }

    public function fetchAll($sql, $params = [])
    {
        // Prepare the query (PDO checks its structure before running it)
        $stmt = $this->conn->prepare($sql);

        // Run the query, safely substituting $params in place of the "?" marks
        $stmt->execute($params);

        // Return every matching row as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Execute a SELECT query that is only expected to return one row
    // (e.g. looking up a single customer by id).
    public function fetchOne($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);

        $stmt->execute($params);

        // fetch() (no "All") returns just the first matching row
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Execute INSERT, UPDATE and DELETE statements.
    // Returns true/false depending on whether the statement succeeded.
    public function execute($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute($params);
    }

    // Get the raw PDO connection if a child class ever needs to do
    // something this class doesn't already provide a helper for
    // (e.g. transactions).
    public function getConnection()
    {
        return $this->conn;
    }
}
