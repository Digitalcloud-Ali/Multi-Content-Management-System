<?php
/**
 * Modern PHP Functions for Multi-Content Management System
 * Updated for PHP 7.0+ compatibility
 */

if (!function_exists("GetSQLValueString")) {
    /**
     * Modern SQL value escaping function
     * @param mixed $theValue The value to escape
     * @param string $theType The data type
     * @param string $theDefinedValue Value if defined
     * @param string $theNotDefinedValue Value if not defined
     * @return string Properly escaped SQL value
     */
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
    {
        // Modern PHP handles input automatically - no need for magic quotes handling
        
        // Use mysqli_real_escape_string for proper SQL escaping
        if (function_exists('dbconnect') && function_exists('mysqli_real_escape_string')) {
            $theValue = mysqli_real_escape_string(dbconnect(), $theValue);
        }
        
        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;    
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
}

/**
 * Modern database connection function
 * @return mysqli|false Database connection object
 */
if (!function_exists("modern_dbconnect")) {
    function modern_dbconnect() {
        static $connection = null;
        
        if ($connection === null) {
            $configFile = __DIR__ . '/db_config.php';
            if (!is_file($configFile)) {
                trigger_error('Database not configured. Run install.php.', E_USER_ERROR);
                return false;
            }
            require_once $configFile;
            $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            
            if (!$connection) {
                trigger_error(mysqli_connect_error(), E_USER_ERROR);
                return false;
            }
            mysqli_set_charset($connection, defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4');
        }
        
        return $connection;
    }
}

/**
 * Modern error reporting function
 */
if (!function_exists("set_modern_error_reporting")) {
    function set_modern_error_reporting() {
        // Set appropriate error reporting for production/development
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
            error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
            ini_set('display_errors', 0);
        } else {
            error_reporting(E_ALL & ~E_NOTICE);
            ini_set('display_errors', 1);
        }
    }
}

/**
 * Modern input sanitization function
 * @param mixed $input The input to sanitize
 * @return mixed Sanitized input
 */
if (!function_exists("sanitize_input")) {
    function sanitize_input($input) {
        if (is_array($input)) {
            return array_map('sanitize_input', $input);
        }
        
        if (is_string($input)) {
            // Remove null bytes
            $input = str_replace(chr(0), '', $input);
            // Basic HTML entity encoding
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
        
        return $input;
    }
}
?>
