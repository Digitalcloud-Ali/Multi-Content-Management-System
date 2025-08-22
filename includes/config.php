<?php
/**
 * Main Configuration File
 * Modernized for Multi-Content CMS
 */

// Check if system is installed
if (!file_exists('installed.lock')) {
    die('System is not installed. Please run install.php first.');
}

// Include the modern bootstrap system
require_once 'bootstrap.php';

// Get database instance
$db = getDB();

// Get settings from database
try {
    $settings = $db->queryOne("SELECT * FROM settings WHERE settingid = 1");
    
    if (!$settings) {
        throw new Exception('Settings not found in database');
    }
    
    // Define database connection constants for backward compatibility
    define('DB_HOST', $settings['host']);
    define('DB_USERNAME', $settings['username']);
    define('DB_PASSWORD', $settings['password']);
    define('DB_NAME', $settings['database']);
    
    // Legacy variables for backward compatibility
    $hostname_rayicecms = $settings['host'];
    $database_rayicecms = $settings['database'];
    $username_rayicecms = $settings['username'];
    $password_rayicecms = $settings['password'];
    
} catch (Exception $e) {
    logError("Settings retrieval error: " . $e->getMessage(), 'ERROR');
    
    // Set default values if database is not available
    $hostname_rayicecms = 'localhost';
    $database_rayicecms = 'cms_database';
    $username_rayicecms = 'db_user';
    $password_rayicecms = 'db_password';
    
    define('DB_HOST', $hostname_rayicecms);
    define('DB_USERNAME', $username_rayicecms);
    define('DB_PASSWORD', $password_rayicecms);
    define('DB_NAME', $database_rayicecms);
}

// Modern GetSQLValueString function for backward compatibility
if (!function_exists("GetSQLValueString")) {
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

// Legacy database connection for backward compatibility
if (!function_exists('dbconnect')) {
    function dbconnect() {
        static $connection = null;
        
        if ($connection === null) {
            try {
                $connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
                
                if ($connection->connect_error) {
                    throw new Exception('Database connection failed: ' . $connection->connect_error);
                }
                
                $connection->set_charset('utf8mb4');
                
            } catch (Exception $e) {
                logError("Database connection error: " . $e->getMessage(), 'ERROR');
                return false;
            }
        }
        
        return $connection;
    }
}

// Get current settings for backward compatibility
try {
    $query_setting = "SELECT * FROM settings WHERE settingid = 1";
    $setting = $db->queryAll($query_setting);
    $row_setting = $setting[0] ?? [];
    $totalRows_setting = count($setting);
    
} catch (Exception $e) {
    logError("Settings query error: " . $e->getMessage(), 'ERROR');
    $row_setting = [];
    $totalRows_setting = 0;
}

// Legacy connection variable for backward compatibility
$rayicecms = dbconnect();

// Clean up
if (isset($setting) && is_array($setting)) {
    // No need to free result with new Database class
    unset($setting);
}
?>