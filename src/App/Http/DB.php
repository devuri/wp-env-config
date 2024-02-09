<?php

namespace Urisoft\App\Http;

use PDO;
use PDOException;

class DB
{
    private $host;
    private $dbName;
    private $username;
    private $password;
    private $conn;
    private $table;

    public function __construct( string $table_name_no_prefix, string $host, string $dbName, string $username, string $password )
    {
        $this->host     = $host;
        $this->dbName   = $dbName;
        $this->username = $username;
        $this->password = $password;

        // set table_name.
        $this->table = env( 'LANDLORD_DB_PREFIX' ) . $table_name_no_prefix;
    }

    // Fetch all records from the table
    public function all()
    {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt  = $this->connect()->prepare( $query );

        try {
            $stmt->execute();

            return $stmt->fetchAll( PDO::FETCH_OBJ );
        } catch ( PDOException $e ) {
            wp_terminate( 'Read error: ' . $e->getMessage() );
        }
    }

    // Find a specific record by ID
    public function find( $id )
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt  = $this->connect()->prepare( $query );
        $stmt->bindParam( ':id', $id, PDO::PARAM_INT );

        try {
            $stmt->execute();

            return $stmt->fetch( PDO::FETCH_ASSOC );
        } catch ( PDOException $e ) {
            wp_terminate( 'Find error: ' . $e->getMessage() );
        }
    }

    // Get records based on a specified condition
    public function where( $column, $value )
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $column . ' = :value';
        $stmt  = $this->connect()->prepare( $query );
        $stmt->bindParam( ':value', $value );

        try {
            $stmt->execute();

            return $stmt->fetchAll( PDO::FETCH_ASSOC );
        } catch ( PDOException $e ) {
            wp_terminate( 'Query error: ' . $e->getMessage() );
        }
    }

    private function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO( 'mysql:host=' . $this->host . ';dbname=' . $this->dbName, $this->username, $this->password );
            $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch ( PDOException $e ) {
            wp_terminate( 'Connection error: ' . $e->getMessage() );
        }

        return $this->conn;
    }
}
