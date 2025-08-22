<?php
/**
 * Main RayCMS System File
 * Now uses modern bootstrap system for better organization
 */

// Include the modern bootstrap
require_once __DIR__ . '/bootstrap.php';

// Get database instance
$db = getDB();

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
    logError("Settings query error: " . $e->getMessage());
    
    // Set default values if database is not available
    $row = [
        'selecttopic' => 'default',
        'installed' => 'yes'
    ];
}

// Make settings available globally
if (!isset($GLOBALS['raycms_settings'])) {
    $GLOBALS['raycms_settings'] = $row;
}

// Helper function to get settings
function getSetting($key, $default = null) {
    global $raycms_settings;
    return $raycms_settings[$key] ?? $default;
}
?>
