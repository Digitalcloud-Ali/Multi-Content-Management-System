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

$GLOBALS['raycms_settings'] = is_array($row) ? $row : [];

if (!function_exists('getDB')) {
    function getDB() {
        return Database::getInstance();
    }
}

if (!function_exists('getSetting')) {
    function getSetting($key, $default = null) {
        static $cache = null;
        if ($cache === null) {
            $cache = [];
            try {
                $row = Database::getInstance()->queryOne('SELECT * FROM settings WHERE settingid = 1');
                if (is_array($row)) {
                    $cache = $row;
                    if (!isset($cache['site_name']) && isset($cache['site_title'])) {
                        $cache['site_name'] = $cache['site_title'];
                    }
                    if (!isset($cache['site_title']) && isset($cache['title'])) {
                        $cache['site_title'] = $cache['title'];
                        $cache['site_name'] = $cache['title'];
                    }
                    if (!isset($cache['site_description']) && isset($cache['metadesc'])) {
                        $cache['site_description'] = $cache['metadesc'];
                    }
                }
            } catch (Exception $e) {
                $cache = [];
            }
        }
        global $raycms_settings;
        if (is_array($raycms_settings ?? null) && array_key_exists($key, $raycms_settings)) {
            return $raycms_settings[$key];
        }
        return $cache[$key] ?? $default;
    }
}

if (!function_exists('generateCsrfToken')) {
    function generateCsrfToken() {
        if (class_exists('Session') && method_exists('Session', 'generateCsrfToken')) {
            return Session::generateCsrfToken();
        }
        return function_exists('multicms_csrf_token') ? multicms_csrf_token() : '';
    }
}

if (!function_exists('logError')) {
    function logError($message, $level = 'ERROR') {
        error_log('[' . $level . '] ' . $message);
    }
}

if (!function_exists('hasRole')) {
    function hasRole($role) {
        if (empty($_SESSION['MM_UserGroup'])) {
            return false;
        }
        return strcasecmp((string) $_SESSION['MM_UserGroup'], (string) $role) === 0
            || (strcasecmp((string) $role, 'admin') === 0 && strcasecmp((string) $_SESSION['MM_UserGroup'], 'administrator') === 0);
    }
}

if (!class_exists('Multicms_Hooks') && is_file(__DIR__ . '/Hooks.php')) {
    require_once __DIR__ . '/Hooks.php';
}
