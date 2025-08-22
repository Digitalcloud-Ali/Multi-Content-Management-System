<?php
/**
 * Modern Error Handling Class
 * Proper error handling and logging
 */

class ErrorHandler {
    private static $logFile = null;
    
    /**
     * Initialize error handler
     */
    public static function init($logFile = null) {
        self::$logFile = $logFile ?: __DIR__ . '/../logs/error.log';
        
        // Set error handler
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleFatalError']);
        
        // Create logs directory if it doesn't exist
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    /**
     * Handle PHP errors
     */
    public static function handleError($errno, $errstr, $errfile, $errline) {
        $errorType = self::getErrorType($errno);
        $message = "[{$errorType}] {$errstr} in {$errfile} on line {$errline}";
        
        // Log error
        self::logError($message);
        
        // Display error based on environment
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin: 10px; border-radius: 4px;'>";
            echo "<strong>Error:</strong> {$errstr}<br>";
            echo "<strong>File:</strong> {$errfile}<br>";
            echo "<strong>Line:</strong> {$errline}";
            echo "</div>";
        }
        
        // Don't execute PHP internal error handler
        return true;
    }
    
    /**
     * Handle exceptions
     */
    public static function handleException($exception) {
        $message = "[EXCEPTION] " . $exception->getMessage() . 
                   " in " . $exception->getFile() . 
                   " on line " . $exception->getLine() . 
                   "\nStack trace:\n" . $exception->getTraceAsString();
        
        // Log exception
        self::logError($message);
        
        // Display exception based on environment
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin: 10px; border-radius: 4px;'>";
            echo "<strong>Exception:</strong> " . $exception->getMessage() . "<br>";
            echo "<strong>File:</strong> " . $exception->getFile() . "<br>";
            echo "<strong>Line:</strong> " . $exception->getLine();
            echo "</div>";
        } else {
            // Production: show generic error
            echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin: 10px; border-radius: 4px;'>";
            echo "An error occurred. Please try again later or contact support.";
            echo "</div>";
        }
    }
    
    /**
     * Handle fatal errors
     */
    public static function handleFatalError() {
        $error = error_get_last();
        
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $message = "[FATAL ERROR] {$error['message']} in {$error['file']} on line {$error['line']}";
            
            // Log fatal error
            self::logError($message);
            
            // Display error based on environment
            if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
                echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin: 10px; border-radius: 4px;'>";
                echo "<strong>Fatal Error:</strong> {$error['message']}<br>";
                echo "<strong>File:</strong> {$error['file']}<br>";
                echo "<strong>Line:</strong> {$error['line']}";
                echo "</div>";
            } else {
                // Production: show generic error
                echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; margin: 10px; border-radius: 4px;'>";
                echo "A system error occurred. Please try again later or contact support.";
                echo "</div>";
            }
        }
    }
    
    /**
     * Get error type string
     */
    private static function getErrorType($errno) {
        switch ($errno) {
            case E_ERROR:
                return 'E_ERROR';
            case E_WARNING:
                return 'E_WARNING';
            case E_PARSE:
                return 'E_PARSE';
            case E_NOTICE:
                return 'E_NOTICE';
            case E_CORE_ERROR:
                return 'E_CORE_ERROR';
            case E_CORE_WARNING:
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR:
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING:
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR:
                return 'E_USER_ERROR';
            case E_USER_WARNING:
                return 'E_USER_WARNING';
            case E_USER_NOTICE:
                return 'E_USER_NOTICE';
            case E_STRICT:
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR:
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED:
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED:
                return 'E_USER_DEPRECATED';
            default:
                return 'UNKNOWN';
        }
    }
    
    /**
     * Log error to file
     */
    private static function logError($message) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        if (self::$logFile) {
            file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
        }
    }
    
    /**
     * Custom error logging
     */
    public static function log($message, $level = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
        
        if (self::$logFile) {
            file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
        }
    }
    
    /**
     * Display user-friendly error message
     */
    public static function displayError($message, $type = 'error') {
        $styles = [
            'error' => 'background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;',
            'warning' => 'background: #fff3cd; border: 1px solid #ffeaa7; color: #856404;',
            'success' => 'background: #d4edda; border: 1px solid #c3e6cb; color: #155724;',
            'info' => 'background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460;'
        ];
        
        $style = $styles[$type] ?? $styles['error'];
        
        echo "<div style='{$style} padding: 10px; margin: 10px; border-radius: 4px;'>";
        echo htmlspecialchars($message);
        echo "</div>";
    }
    
    /**
     * Safe redirect with error handling
     */
    public static function safeRedirect($url, $message = '') {
        if (!headers_sent()) {
            if ($message) {
                Session::setFlash('message', $message);
            }
            header("Location: {$url}");
            exit;
        } else {
            // Fallback for when headers already sent
            echo "<script>location.href='{$url}';</script>";
            exit;
        }
    }
}

// Initialize error handler
ErrorHandler::init();
?>
