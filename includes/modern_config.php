<?php
/**
 * Modern Configuration File for Multi-Content Management System
 * Environment-based configuration with security improvements
 */

// Define environment
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development'); // Change to 'production' for live sites
}

// Database configuration
$config = [
    'development' => [
        'host' => 'localhost',
        'username' => 'db_username',
        'password' => 'password',
        'database' => 'db_password',
        'charset' => 'utf8mb4',
        'error_reporting' => E_ALL & ~E_NOTICE,
        'display_errors' => 1
    ],
    'production' => [
        'host' => 'localhost',
        'username' => 'db_username',
        'password' => 'password',
        'database' => 'db_password',
        'charset' => 'utf8mb4',
        'error_reporting' => E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED,
        'display_errors' => 0
    ]
];

// Set current configuration
$current_config = $config[ENVIRONMENT];

// Set error reporting
error_reporting($current_config['error_reporting']);
ini_set('display_errors', $current_config['display_errors']);

// Security headers
if (!headers_sent()) {
    // Prevent XSS attacks
    header('X-XSS-Protection: 1; mode=block');
    
    // Prevent clickjacking
    header('X-Frame-Options: SAMEORIGIN');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// Session security
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', ENVIRONMENT === 'production' ? 1 : 0);
ini_set('session.use_strict_mode', 1);

// Timezone
date_default_timezone_set('UTC');

// Constants
define('SITE_URL', 'http://localhost'); // Update this for your domain
define('ADMIN_EMAIL', 'admin@example.com'); // Update this
define('UPLOAD_MAX_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Database connection function
function get_db_config() {
    global $current_config;
    return $current_config;
}

// Logging function
function log_error($message, $level = 'ERROR') {
    $log_file = __DIR__ . '/../logs/error.log';
    $log_dir = dirname($log_file);
    
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$level] $message" . PHP_EOL;
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

// Input sanitization function
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

// CSRF token generation
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF token validation
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
