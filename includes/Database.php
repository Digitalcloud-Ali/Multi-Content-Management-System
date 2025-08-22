<?php
/**
 * Modern Database Class for Multi-Content Management System
 * Uses prepared statements and proper error handling
 */

class Database {
    private $connection;
    private $config;
    private static $instance = null;
    
    private function __construct() {
        $this->config = [
            'host' => 'localhost',
            'username' => 'db_username',
            'password' => 'password',
            'database' => 'db_password',
            'charset' => 'utf8mb4'
        ];
        
        $this->connect();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function connect() {
        try {
            $this->connection = new mysqli(
                $this->config['host'],
                $this->config['username'],
                $this->config['password'],
                $this->config['database']
            );
            
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset($this->config['charset']);
            
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Execute a prepared statement
     */
    public function prepare($sql, $types = '', $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->connection->error);
            }
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            return $stmt;
            
        } catch (Exception $e) {
            error_log("Prepare statement error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Execute a query and return result
     */
    public function query($sql, $types = '', $params = []) {
        try {
            $stmt = $this->prepare($sql, $types, $params);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Query execution error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Execute a query and return single row
     */
    public function queryOne($sql, $types = '', $params = []) {
        $result = $this->query($sql, $types, $params);
        return $result ? $result->fetch_assoc() : null;
    }
    
    /**
     * Execute a query and return all rows
     */
    public function queryAll($sql, $types = '', $params = []) {
        $result = $this->query($sql, $types, $params);
        $rows = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        
        return $rows;
    }
    
    /**
     * Execute INSERT, UPDATE, DELETE queries
     */
    public function execute($sql, $types = '', $params = []) {
        try {
            $stmt = $this->prepare($sql, $types, $params);
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $insertId = $stmt->insert_id;
            $stmt->close();
            
            return [
                'affected_rows' => $affectedRows,
                'insert_id' => $insertId
            ];
            
        } catch (Exception $e) {
            error_log("Execute error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction() {
        $this->connection->begin_transaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        $this->connection->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback() {
        $this->connection->rollback();
    }
    
    /**
     * Close connection
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    /**
     * Get connection for legacy compatibility
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Escape string safely
     */
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }
    
    /**
     * Destructor
     */
    public function __destruct() {
        $this->close();
    }
}

// Legacy compatibility function
function dbconnect() {
    return Database::getInstance()->getConnection();
}
?>
