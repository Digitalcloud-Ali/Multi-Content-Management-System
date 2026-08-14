<?php
/**
 * Modern Application Bootstrap
 * Initializes all modern classes and sets up the application environment
 */

// Prevent direct access
if (!defined('APP_STARTED')) {
    define('APP_STARTED', true);
}

// Set error reporting based on environment
if (is_file(__DIR__ . '/env.php')) {
    require_once __DIR__ . '/env.php';
}
if (!defined('ENVIRONMENT')) {
    // Production-safe default for public installs
    define('ENVIRONMENT', defined('MULTICMS_ENV') ? MULTICMS_ENV : 'production');
}

// Include all modern classes
require_once __DIR__ . '/modern_functions.php';
require_once __DIR__ . '/Hooks.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/Validator.php';
require_once __DIR__ . '/ErrorHandler.php';
require_once __DIR__ . '/LegacyAuth.php';
require_once __DIR__ . '/PluginManager.php';
require_once __DIR__ . '/FlagshipSite.php';
require_once __DIR__ . '/Routing.php';

// Include service classes
require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/ContentService.php';

// Initialize error handling
ErrorHandler::init();

// Initialize session management
Session::start();

// Set modern error reporting
set_modern_error_reporting();

// Set timezone
date_default_timezone_set('UTC');

// Security headers
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    
    if (ENVIRONMENT === 'production') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

// Initialize database connection
try {
    $db = Database::getInstance();
} catch (Exception $e) {
    ErrorHandler::log("Database initialization failed: " . $e->getMessage(), 'ERROR');
    if (ENVIRONMENT === 'development') {
        ErrorHandler::displayError("Database connection failed. Check configuration.", 'error');
    }
}

// Legacy compatibility function
if (!function_exists('dbconnect')) {
    function dbconnect() {
        return Database::getInstance()->getConnection();
    }
}

// Helper function to get database instance
if (!function_exists('getDB')) {
    function getDB() {
        return Database::getInstance();
    }
}

// Helper function to get session instance
if (!function_exists('getSession')) {
    function getSession() {
        return Session::class;
    }
}

// Helper function to get validator instance
if (!function_exists('getValidator')) {
    function getValidator() {
        return new Validator();
    }
}

// Helper function to safely redirect
if (!function_exists('safeRedirect')) {
    function safeRedirect($url, $message = '') {
        ErrorHandler::safeRedirect($url, $message);
    }
}

// Helper function to display errors
if (!function_exists('displayError')) {
    function displayError($message, $type = 'error') {
        ErrorHandler::displayError($message, $type);
    }
}

// Helper function to log errors
if (!function_exists('logError')) {
    function logError($message, $level = 'ERROR') {
        ErrorHandler::log($message, $level);
    }
}

// Helper function to get current user
if (!function_exists('getCurrentUser')) {
    function getCurrentUser() {
        if (!class_exists('AuthService')) {
            return null;
        }
        $authService = new AuthService();
        return $authService->getCurrentUser();
    }
}

// Helper function to check if user is authenticated
if (!function_exists('isAuthenticated')) {
    function isAuthenticated() {
        if (!class_exists('AuthService')) {
            return !empty($_SESSION['MM_Username']);
        }
        $authService = new AuthService();
        return $authService->isAuthenticated();
    }
}

// Helper function to check if user has specific role
if (!function_exists('hasRole')) {
    function hasRole($role) {
        if (!class_exists('AuthService')) {
            if (empty($_SESSION['MM_UserGroup'])) {
                return false;
            }
            return strcasecmp((string) $_SESSION['MM_UserGroup'], (string) $role) === 0
                || (strcasecmp((string) $role, 'admin') === 0 && strcasecmp((string) $_SESSION['MM_UserGroup'], 'administrator') === 0);
        }
        $authService = new AuthService();
        return $authService->hasRole($role);
    }
}

// Helper function to require authentication
if (!function_exists('requireAuth')) {
    function requireAuth() {
        if (!class_exists('AuthService')) {
            header('Location: index.php?page=login');
            exit;
        }
        $authService = new AuthService();
        $authService->requireAuth();
    }
}

// Helper function to require specific role
if (!function_exists('requireRole')) {
    function requireRole($role) {
        if (!class_exists('AuthService')) {
            header('Location: index.php?page=login');
            exit;
        }
        $authService = new AuthService();
        $authService->requireRole($role);
    }
}

// Helper function to get settings
if (!function_exists('getSetting')) {
function getSetting($key, $default = null) {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            if (function_exists('getDB')) {
                $row = getDB()->queryOne("SELECT * FROM settings WHERE settingid = 1");
                if (is_array($row)) {
                    $cache = $row;
                    // Aliases for theme templates
                    if (!isset($cache['site_name']) && isset($cache['site_title'])) {
                        $cache['site_name'] = $cache['site_title'];
                    }
                    if (!isset($cache['title']) && isset($cache['site_title'])) {
                        $cache['title'] = $cache['site_title'];
                    }
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
} // getSetting

// Helper function to sanitize input
if (!function_exists('sanitizeInput')) {
function sanitizeInput($input, $type = 'string') {
    return Validator::sanitize($input, $type);
}
}

// Helper function to validate input
if (!function_exists('validateInput')) {
function validateInput($data, $rules) {
    $validator = new Validator();
    return $validator->validate($data, $rules);
}
}

// Helper function to get validation errors
if (!function_exists('getValidationErrors')) {
function getValidationErrors() {
    if (!isset($GLOBALS['validator'])) {
        return [];
    }
    return $GLOBALS['validator']->getErrors();
}
}

// Helper function to generate CSRF token
if (!function_exists('generateCsrfToken')) {
function generateCsrfToken() {
    return Session::generateCsrfToken();
}
}

// Helper function to validate CSRF token
if (!function_exists('validateCsrfToken')) {
function validateCsrfToken($token) {
    return Session::validateCsrfToken($token);
}
}

// Helper function to set flash message
function setFlashMessage($key, $message) {
    Session::setFlash($key, $message);
}

// Helper function to get flash message
function getFlashMessage($key, $default = '') {
    return Session::getFlash($key, $default);
}

// Helper function to check if flash message exists
function hasFlashMessage($key) {
    return Session::hasFlash($key);
}

// Helper function to display flash messages
function displayFlashMessages() {
    if (hasFlashMessage('message')) {
        $message = getFlashMessage('message');
        $type = 'success';
        
        // Check if it's an error message
        if (stripos($message, 'error') !== false || stripos($message, 'failed') !== false) {
            $type = 'error';
        } elseif (stripos($message, 'warning') !== false) {
            $type = 'warning';
        } elseif (stripos($message, 'info') !== false) {
            $type = 'info';
        }
        
        displayError($message, $type);
    }
}

// Helper function to format date
function formatDate($date, $format = 'Y-m-d H:i:s') {
    if (is_string($date)) {
        $date = new DateTime($date);
    }
    
    if ($date instanceof DateTime) {
        return $date->format($format);
    }
    
    return date($format);
}

// Helper function to truncate text
function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

// Helper function to generate slug
function generateSlug($text) {
    // Convert to lowercase
    $text = strtolower($text);
    
    // Replace non-alphanumeric characters with hyphens
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    
    // Remove multiple consecutive hyphens
    $text = preg_replace('/-+/', '-', $text);
    
    // Remove leading and trailing hyphens
    $text = trim($text, '-');
    
    return $text;
}

// Helper function to check if request is AJAX
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// Helper function to send JSON response
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Helper function to send error response
function sendErrorResponse($message, $statusCode = 400) {
    sendJsonResponse(['error' => $message], $statusCode);
}

// Helper function to send success response
function sendSuccessResponse($data = null, $message = 'Success') {
    sendJsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}
?>
