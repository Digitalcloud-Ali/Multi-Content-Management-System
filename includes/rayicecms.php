<?php
// Include modern classes for PHP 7.0+ compatibility
require_once(__DIR__ . '/modern_functions.php');
require_once(__DIR__ . '/Database.php');
require_once(__DIR__ . '/Session.php');
require_once(__DIR__ . '/Validator.php');

// Initialize modern session management
Session::start();

// Legacy compatibility function (now uses modern Database class)
function dbconnect() {
    return Database::getInstance()->getConnection();
}

// Set modern error reporting
set_modern_error_reporting();

// Get database instance
$db = Database::getInstance();

// Query settings with modern database class
try {
    $query = "SELECT * FROM settings WHERE settingid = 1";
    $row = $db->queryOne($query);
    
    if ($row && isset($row['selecttopic']) && isset($_SESSION['sitetopic']) && $row['selecttopic'] != $_SESSION['sitetopic']) {
        // Redirect if site topic doesn't match
        echo '<script type="text/javascript">location.replace("Location: /");</script>';
    } else {
        unset($_SESSION['sitetopic']); 
    }
    
} catch (Exception $e) {
    // Log error but don't expose it to users
    error_log("Settings query error: " . $e->getMessage());
    
    // Set default values if database is not available
    $row = [
        'selecttopic' => 'default',
        'installed' => 'yes'
    ];
}
?>
