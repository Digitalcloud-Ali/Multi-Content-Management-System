<?php
/**
 * Modern Session Management Class
 * Secure session handling with CSRF protection
 */

class Session {
    private static $started = false;
    
    /**
     * Start session with security settings
     */
    public static function start() {
        if (self::$started) {
            return;
        }
        
        // Set secure session parameters
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', self::isHttps() ? 1 : 0);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_samesite', 'Strict');
        
        // Set session name
        session_name('RAYCMS_SESSION');
        
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Regenerate session ID periodically for security
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutes
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
        
        self::$started = true;
    }
    
    /**
     * Check if HTTPS is being used
     */
    private static function isHttps() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
               ($_SERVER['SERVER_PORT'] == 443) ||
               (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    }
    
    /**
     * Set session value
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session value
     */
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session key
     */
    public static function remove($key) {
        self::start();
        unset($_SESSION[$key]);
    }
    
    /**
     * Clear all session data
     */
    public static function clear() {
        self::start();
        session_unset();
        session_destroy();
        self::$started = false;
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken() {
        self::start();
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateCsrfToken($token) {
        self::start();
        
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return self::has('MM_Username') && !empty(self::get('MM_Username'));
    }
    
    /**
     * Check if user has specific role
     */
    public static function hasRole($role) {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        $userRole = self::get('MM_UserGroup');
        return $userRole === $role;
    }
    
    /**
     * Require authentication
     */
    public static function requireAuth() {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }
    
    /**
     * Require specific role
     */
    public static function requireRole($role) {
        self::requireAuth();
        
        if (!self::hasRole($role)) {
            header('Location: access-denied.php');
            exit;
        }
    }
    
    /**
     * Flash message system
     */
    public static function setFlash($key, $message) {
        self::set("flash_{$key}", $message);
    }
    
    public static function getFlash($key, $default = '') {
        $message = self::get("flash_{$key}", $default);
        self::remove("flash_{$key}");
        return $message;
    }
    
    /**
     * Check if flash message exists
     */
    public static function hasFlash($key) {
        return self::has("flash_{$key}");
    }
}

// Initialize session when file is included
Session::start();
?>
