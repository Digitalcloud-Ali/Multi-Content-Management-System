<?php
/**
 * Legacy bootstrap for administrator + ready-made site packs.
 * Loads modern DB config and shared auth helpers.
 */

if (!defined('MULTICMS_ROOT')) {
    define('MULTICMS_ROOT', dirname(__DIR__));
}

if (is_file(__DIR__ . '/db_config.php')) {
    require_once __DIR__ . '/db_config.php';
}

require_once __DIR__ . '/modern_functions.php';
require_once __DIR__ . '/LegacyAuth.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/Validator.php';

Session::start();

if (!function_exists('dbconnect')) {
    function dbconnect() {
        return Database::getInstance()->getConnection();
    }
}

set_modern_error_reporting();

// Legacy mysqli_select_db() expects a database name variable
$database_rayicecms = defined('DB_NAME') ? DB_NAME : '';
$hostname_rayicecms = defined('DB_HOST') ? DB_HOST : 'localhost';
$username_rayicecms = defined('DB_USERNAME') ? DB_USERNAME : '';
$password_rayicecms = defined('DB_PASSWORD') ? DB_PASSWORD : '';

try {
    $db = Database::getInstance();
    $row = $db->queryOne("SELECT * FROM settings WHERE settingid = 1");

    if ($row && isset($row['selecttopic']) && isset($_SESSION['sitetopic']) && $row['selecttopic'] != $_SESSION['sitetopic']) {
        echo '<script type="text/javascript">location.replace("/");</script>';
    } else {
        unset($_SESSION['sitetopic']);
    }
} catch (Exception $e) {
    error_log("Settings query error: " . $e->getMessage());
    $row = [
        'selecttopic' => 'default',
        'installed' => 'yes',
        'title' => 'MultiCMS'
    ];
}
