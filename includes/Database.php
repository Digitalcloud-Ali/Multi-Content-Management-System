<?php
/**
 * Modern Database Class for Multi-Content Management System
 * Uses prepared statements and loads credentials from includes/db_config.php
 */

class Database {
    private $connection;
    private $config;
    private static $instance = null;
    
    private function __construct() {
        $this->config = self::loadConfig();
        $this->connect();
    }

    /**
     * Load DB credentials from installer-generated config (fail closed if incomplete).
     */
    private static function loadConfig() {
        $configFile = __DIR__ . '/db_config.php';
        $defaults = [
            'host' => 'localhost',
            'username' => '',
            'password' => '',
            'database' => '',
            'charset' => 'utf8mb4'
        ];

        if (is_file($configFile)) {
            require_once $configFile;
            if (defined('DB_HOST') && defined('DB_USERNAME') && defined('DB_NAME')) {
                return [
                    'host' => DB_HOST,
                    'username' => DB_USERNAME,
                    'password' => defined('DB_PASSWORD') ? DB_PASSWORD : '',
                    'database' => DB_NAME,
                    'charset' => defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4'
                ];
            }
        }

        // Uninstalled / misconfigured — do not use fake placeholder credentials
        if (is_file(__DIR__ . '/installed.lock')) {
            throw new Exception('Database configuration missing. Re-run install or restore includes/db_config.php.');
        }

        return $defaults;
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function connect() {
        if ($this->config['username'] === '' || $this->config['database'] === '') {
            throw new Exception('Database is not configured. Run install.php first.');
        }

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
    
    public function queryOne($sql, $types = '', $params = []) {
        $result = $this->query($sql, $types, $params);
        return $result ? $result->fetch_assoc() : null;
    }
    
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
    
    public function beginTransaction() {
        $this->connection->begin_transaction();
    }
    
    public function commit() {
        $this->connection->commit();
    }
    
    public function rollback() {
        $this->connection->rollback();
    }
    
    public function close() {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }
    
    public function __destruct() {
        // Keep singleton connection alive for request lifetime; do not close here.
    }
}

if (!function_exists('dbconnect')) {
    function dbconnect() {
        return Database::getInstance()->getConnection();
    }
}
