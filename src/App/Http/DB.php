<?php

namespace Urisoft\App\Http;

class DB
{
    private $host;
    private $dbName;
    private $username;
    private $password;
    private $conn;
    private $table;

    public function __construct($table_name_no_prefix)
	{
		$this->host = env( 'DB_HOST' );
		$this->dbName = env( 'DB_NAME' );
		$this->username = env( 'DB_USER' );
		$this->password = env( 'DB_PASSWORD' );
        $this->table = env('DB_PREFIX') . $table_name_no_prefix;
    }

    private function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbName, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            wp_terminate("Connection error: " . $e->getMessage());
        }

        return $this->conn;
    }

    // Fetch all records from the table
    public function all() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->connect()->prepare($query);

        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            wp_terminate("Read error: " . $e->getMessage());
        }
    }

    // Find a specific record by ID
    public function find($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            wp_terminate("Find error: " . $e->getMessage());
        }
    }

    // Get records based on a specified condition
    public function where($column, $value) {
        $query = "SELECT * FROM " . $this->table . " WHERE " . $column . " = :value";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':value', $value);

        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            wp_terminate("Query error: " . $e->getMessage());
        }
    }
}
