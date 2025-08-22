<?php
/**
 * Installation System Test Script
 * Run this to verify all components are working correctly
 */

echo "<h1>Multi-Content CMS Installation System Test</h1>\n";
echo "<p>Testing installation system components...</p>\n";

// Test 1: PHP Version
echo "<h2>1. PHP Version Check</h2>\n";
echo "Current PHP Version: " . PHP_VERSION . "\n";
echo "Required: 7.4.0+\n";
if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
    echo "✅ PHP version is compatible\n";
} else {
    echo "❌ PHP version is too old\n";
}

// Test 2: Required Extensions
echo "<h2>2. Required Extensions</h2>\n";
$required_extensions = ['mysqli', 'gd', 'curl'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ {$ext} extension is loaded\n";
    } else {
        echo "❌ {$ext} extension is missing\n";
    }
}

// Test 3: Directory Permissions
echo "<h2>3. Directory Permissions</h2>\n";
$directories = ['includes', 'uploads'];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "✅ {$dir} directory is writable\n";
        } else {
            echo "❌ {$dir} directory is not writable\n";
        }
    } else {
        echo "❌ {$dir} directory does not exist\n";
    }
}

// Test 4: File Existence
echo "<h2>4. Required Files</h2>\n";
$required_files = [
    'install.php',
    'includes/bootstrap.php',
    'includes/Database.php',
    'includes/Session.php',
    'includes/Validator.php',
    'includes/ErrorHandler.php'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✅ {$file} exists\n";
    } else {
        echo "❌ {$file} is missing\n";
    }
}

// Test 5: Class Loading
echo "<h2>5. Class Loading Test</h2>\n";
try {
    require_once 'includes/bootstrap.php';
    echo "✅ bootstrap.php loaded successfully\n";
    
    // Test database class
    if (class_exists('Database')) {
        echo "✅ Database class loaded\n";
    } else {
        echo "❌ Database class not found\n";
    }
    
    // Test session class
    if (class_exists('Session')) {
        echo "✅ Session class loaded\n";
    } else {
        echo "❌ Session class not found\n";
    }
    
    // Test validator class
    if (class_exists('Validator')) {
        echo "✅ Validator class loaded\n";
    } else {
        echo "❌ Validator class not found\n";
    }
    
    // Test error handler class
    if (class_exists('ErrorHandler')) {
        echo "✅ ErrorHandler class loaded\n";
    } else {
        echo "❌ ErrorHandler class not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error loading bootstrap: " . $e->getMessage() . "\n";
}

// Test 6: Function Availability
echo "<h2>6. Function Availability</h2>\n";
$required_functions = ['getDB', 'getSession', 'getValidator', 'logError'];
foreach ($required_functions as $func) {
    if (function_exists($func)) {
        echo "✅ {$func} function is available\n";
    } else {
        echo "❌ {$func} function is missing\n";
    }
}

// Test 7: Installation Status
echo "<h2>7. Installation Status</h2>\n";
if (file_exists('includes/installed.lock')) {
    echo "❌ System is already installed\n";
    echo "Delete includes/installed.lock to test installation\n";
} else {
    echo "✅ System is ready for installation\n";
}

// Test 8: Database Connection Test (if credentials available)
echo "<h2>8. Database Connection Test</h2>\n";
if (file_exists('includes/db_config.php')) {
    echo "Database config file exists - testing connection...\n";
    try {
        require_once 'includes/db_config.php';
        if (defined('DB_HOST') && defined('DB_USERNAME') && defined('DB_NAME')) {
            $test_conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            if ($test_conn->connect_error) {
                echo "❌ Database connection failed: " . $test_conn->connect_error . "\n";
            } else {
                echo "✅ Database connection successful\n";
                $test_conn->close();
            }
        } else {
            echo "⚠️ Database configuration incomplete\n";
        }
    } catch (Exception $e) {
        echo "❌ Database test error: " . $e->getMessage() . "\n";
    }
} else {
    echo "⚠️ No database configuration file found\n";
}

// Test 9: Session Test
echo "<h2>9. Session Test</h2>\n";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "✅ Session is active\n";
} else {
    echo "⚠️ Session is not active\n";
}

// Test 10: Error Reporting
echo "<h2>10. Error Reporting</h2>\n";
echo "Current error reporting level: " . error_reporting() . "\n";
echo "Display errors: " . (ini_get('display_errors') ? 'On' : 'Off') . "\n";

echo "<h2>Test Summary</h2>\n";
echo "<p>All tests completed. Review the results above to ensure your system is ready for installation.</p>\n";
echo "<p><strong>Next Step:</strong> If all tests pass, you can run <a href='install.php'>install.php</a> to begin the installation process.</p>\n";

echo "<hr>\n";
echo "<p><small>Test completed at: " . date('Y-m-d H:i:s') . "</small></p>\n";
?>
