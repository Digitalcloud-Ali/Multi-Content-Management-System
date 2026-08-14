<?php
/**
 * Multi-Content Management System - Installation Script
 * Modern, secure installation process with verification steps
 */

// Prevent access if already installed (same idea as WordPress — file can stay)
if (file_exists('includes/installed.lock')) {
    http_response_code(200);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Already installed</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>';
    echo '<body class="bg-light"><div class="container py-5" style="max-width:560px">';
    echo '<h1 class="h3">MultiCMS is already installed</h1>';
    echo '<p class="text-muted">The installer cannot run again while <code>includes/installed.lock</code> exists.</p>';
    echo '<p><a class="btn btn-primary" href="administrator/login.php">Log in to Admin</a> ';
    echo '<a class="btn btn-outline-secondary" href="index.php">View site</a></p>';
    echo '</div></body></html>';
    exit;
}

// Set error reporting for installation
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session for installation process
session_start();

require_once __DIR__ . '/includes/LegacyAuth.php';

// Installation steps
$steps = [
    'welcome' => 'Welcome',
    'requirements' => 'System Requirements',
    'database' => 'Database Configuration',
    'site_config' => 'Site Configuration',
    'admin_setup' => 'Administrator Setup',
    'installation' => 'Installing System',
    'complete' => 'Installation Complete'
];

$current_step = $_GET['step'] ?? 'welcome';
$current_step = in_array($current_step, array_keys($steps)) ? $current_step : 'welcome';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!multicms_csrf_validate($_POST['csrf_token'] ?? '')) {
        $_SESSION['install_error'] = 'Invalid security token. Please try again.';
    } else {
        switch ($current_step) {
            case 'database':
                handleDatabaseConfig();
                break;
            case 'site_config':
                handleSiteConfig();
                break;
            case 'admin_setup':
                handleAdminSetup();
                break;
        }
    }
}

// Function to check system requirements
function checkRequirements() {
    // Ensure uploads exists for writable check / first install
    if (!is_dir('uploads')) {
        @mkdir('uploads', 0755, true);
    }
    $uploadsDeny = 'uploads/.htaccess';
    if (!is_file($uploadsDeny)) {
        @file_put_contents($uploadsDeny, "# Deny script execution in uploads\n<FilesMatch \"\\.(?i:php|phtml|phar|cgi|pl|py|jsp|asp|aspx)$\">\n    Require all denied\n</FilesMatch>\nOptions -ExecCGI\nRemoveHandler .php .phtml .php3 .php4 .php5 .php7 .php8\n");
    }
    if (!is_dir('logs')) {
        @mkdir('logs', 0755, true);
    }
    if (!is_dir('backups')) {
        @mkdir('backups', 0755, true);
    }

    $rewriteOk = true;
    if (function_exists('apache_get_modules')) {
        $mods = apache_get_modules();
        $rewriteOk = in_array('mod_rewrite', $mods, true);
    } elseif (isset($_SERVER['SERVER_SOFTWARE']) && stripos((string) $_SERVER['SERVER_SOFTWARE'], 'apache') !== false) {
        // Cannot probe modules reliably (CGI/FPM) — treat as unknown/ok with note
        $rewriteOk = true;
    }

    $requirements = [
        'php_version' => [
            'name' => 'PHP Version',
            'required' => '7.4.0+',
            'current' => PHP_VERSION,
            'status' => version_compare(PHP_VERSION, '7.4.0', '>='),
            'description' => 'PHP 7.4 or higher is required'
        ],
        'mysql' => [
            'name' => 'MySQL Extension (mysqli)',
            'required' => 'Available',
            'current' => extension_loaded('mysqli') ? 'Available' : 'Not Available',
            'status' => extension_loaded('mysqli'),
            'description' => 'Required for the database'
        ],
        'json' => [
            'name' => 'JSON Extension',
            'required' => 'Available',
            'current' => extension_loaded('json') ? 'Available' : 'Not Available',
            'status' => extension_loaded('json'),
            'description' => 'Required for settings and updates'
        ],
        'mbstring' => [
            'name' => 'mbstring Extension',
            'required' => 'Recommended',
            'current' => extension_loaded('mbstring') ? 'Available' : 'Not Available',
            'status' => extension_loaded('mbstring'),
            'description' => 'Recommended for text handling',
            'optional' => true,
        ],
        'gd' => [
            'name' => 'GD Extension',
            'required' => 'Available',
            'current' => extension_loaded('gd') ? 'Available' : 'Not Available',
            'status' => extension_loaded('gd'),
            'description' => 'Required for image processing'
        ],
        'curl' => [
            'name' => 'cURL Extension',
            'required' => 'Available',
            'current' => extension_loaded('curl') ? 'Available' : 'Not Available',
            'status' => extension_loaded('curl'),
            'description' => 'Required to check GitHub for updates'
        ],
        'zip' => [
            'name' => 'ZIP Extension',
            'required' => 'Available',
            'current' => class_exists('ZipArchive') ? 'Available' : 'Not Available',
            'status' => class_exists('ZipArchive'),
            'description' => 'Required for backups and one-click updates'
        ],
        'mod_rewrite' => [
            'name' => 'Apache mod_rewrite / pretty URLs',
            'required' => 'Recommended',
            'current' => $rewriteOk ? 'OK / assumed available' : 'Not detected',
            'status' => $rewriteOk,
            'description' => 'Needed for clean permalinks (/blog, /post/slug). Query URLs still work without it.',
            'optional' => true,
        ],
        'writable_dirs' => [
            'name' => 'Writable folders',
            'required' => 'includes, uploads, backups',
            'current' => (is_writable('includes') && is_writable('uploads') && is_writable('backups')) ? 'Writable' : 'Not writable',
            'status' => is_writable('includes') && is_writable('uploads') && is_writable('backups'),
            'description' => 'Installer must write config, uploads, and backups'
        ],
        'install_path' => [
            'name' => 'Detected install path',
            'required' => 'Auto',
            'current' => (function () {
                require_once __DIR__ . '/includes/InstallPath.php';
                $b = InstallPath::detectBasePath();
                return $b === '' ? 'Web root (/)' : $b;
            })(),
            'status' => true,
            'description' => 'RewriteBase will be set automatically — no manual .htaccess edit needed',
            'optional' => true,
        ],
    ];
    
    return $requirements;
}

// Function to test database connection
function testDatabaseConnection($host, $username, $password, $database) {
    try {
        $connection = new mysqli($host, $username, $password);
        
        if ($connection->connect_error) {
            return ['success' => false, 'error' => 'Connection failed: ' . $connection->connect_error];
        }
        
        // Check if database exists
        if (!$connection->select_db($database)) {
            // Try to create database
            if (!$connection->query("CREATE DATABASE `" . $connection->real_escape_string($database) . "`")) {
                return ['success' => false, 'error' => 'Database does not exist and cannot be created. Please create it manually.'];
            }
        }
        
        $connection->close();
        return ['success' => true, 'message' => 'Database connection successful'];
        
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

// Function to handle database configuration
function handleDatabaseConfig() {
    if (!isset($_POST['db_host']) || !isset($_POST['db_username']) || !isset($_POST['db_name'])) {
        $_SESSION['install_error'] = 'All database fields are required';
        return;
    }
    
    $db_host = trim($_POST['db_host']);
    $db_username = trim($_POST['db_username']);
    $db_password = $_POST['db_password'] ?? '';
    $db_name = trim($_POST['db_name']);
    
    // Test connection
    $test_result = testDatabaseConnection($db_host, $db_username, $db_password, $db_name);
    
    if ($test_result['success']) {
        $_SESSION['db_config'] = [
            'host' => $db_host,
            'username' => $db_username,
            'password' => $db_password,
            'database' => $db_name
        ];
        
        // Store in session and redirect to next step
        header('Location: install.php?step=site_config');
        exit;
    } else {
        $_SESSION['install_error'] = $test_result['error'];
    }
}

// Function to handle site configuration
function handleSiteConfig() {
    if (!isset($_SESSION['db_config'])) {
        header('Location: install.php?step=database');
        exit;
    }
    
    if (!isset($_POST['site_title']) || !isset($_POST['site_description'])) {
        $_SESSION['install_error'] = 'All site configuration fields are required';
        return;
    }

    $mode = ($_POST['start_mode'] ?? 'fresh') === 'flagship' ? 'flagship' : 'fresh';
    $flagship = basename(trim((string) ($_POST['flagship_slug'] ?? 'blog')));
    if ($mode === 'flagship') {
        require_once __DIR__ . '/includes/FlagshipSite.php';
        if (!FlagshipSite::getPackage($flagship)) {
            $_SESSION['install_error'] = 'Selected flagship site was not found.';
            return;
        }
    }

    $_SESSION['site_config'] = [
        'title' => trim($_POST['site_title']),
        'description' => trim($_POST['site_description']),
        'mode' => $mode,
        'flagship_slug' => $flagship,
        'topic' => 'default',
        'admin_email' => trim($_POST['admin_email'] ?? ''),
        'timezone' => $_POST['timezone'] ?? 'UTC'
    ];
    
    header('Location: install.php?step=admin_setup');
    exit;
}

// Function to handle admin setup
function handleAdminSetup() {
    if (!isset($_SESSION['db_config']) || !isset($_SESSION['site_config'])) {
        header('Location: install.php?step=database');
        exit;
    }
    
    if (!isset($_POST['admin_username']) || !isset($_POST['admin_password']) || !isset($_POST['admin_confirm_password'])) {
        $_SESSION['install_error'] = 'All administrator fields are required';
        return;
    }
    
    if ($_POST['admin_password'] !== $_POST['admin_confirm_password']) {
        $_SESSION['install_error'] = 'Passwords do not match';
        return;
    }
    
    if (strlen($_POST['admin_password']) < 8) {
        $_SESSION['install_error'] = 'Password must be at least 8 characters long';
        return;
    }
    
    $_SESSION['admin_config'] = [
        'username' => trim($_POST['admin_username']),
        'password' => $_POST['admin_password'],
        'email' => $_SESSION['site_config']['admin_email']
    ];
    
    header('Location: install.php?step=installation');
    exit;
}

// Function to perform installation
function performInstallation() {
    if (!isset($_SESSION['db_config']) || !isset($_SESSION['site_config']) || !isset($_SESSION['admin_config'])) {
        return false;
    }
    
    try {
        $db_config = $_SESSION['db_config'];
        $site_config = $_SESSION['site_config'];
        $admin_config = $_SESSION['admin_config'];
        
        // Connect to database
        $connection = new mysqli($db_config['host'], $db_config['username'], $db_config['password'], $db_config['database']);
        
        if ($connection->connect_error) {
            throw new Exception('Database connection failed: ' . $connection->connect_error);
        }
        
        $connection->set_charset('utf8mb4');
        
        // Create database tables
        createDatabaseTables($connection);
        
        // Insert initial data
        insertInitialData($connection, $site_config, $admin_config);
        
        createConfigFile($db_config);

        require_once __DIR__ . '/includes/InstallPath.php';
        InstallPath::apply(__DIR__);

        require_once __DIR__ . '/includes/PluginManager.php';
        require_once __DIR__ . '/includes/FlagshipSite.php';

        $mode = $site_config['mode'] ?? 'fresh';
        if ($mode === 'flagship') {
            $slug = $site_config['flagship_slug'] ?? 'blog';
            $adminId = 1;
            $uidRes = $connection->query("SELECT userid FROM users ORDER BY userid ASC LIMIT 1");
            if ($uidRes && ($urow = $uidRes->fetch_assoc())) {
                $adminId = (int) $urow['userid'];
            }
            $applied = FlagshipSite::apply($slug, $connection, $adminId);
            if (empty($applied['success'])) {
                throw new Exception($applied['message'] ?? 'Flagship apply failed');
            }
            // Keep installer title/description the visitor typed
            $title = $site_config['title'];
            $description = $site_config['description'];
            $stmt = $connection->prepare('UPDATE settings SET site_title = ?, site_description = ? WHERE settingid = 1');
            if ($stmt) {
                $stmt->bind_param('ss', $title, $description);
                $stmt->execute();
                $stmt->close();
            }
        } else {
            PluginManager::applyFreshDefault($connection);
        }

        // Create installed lock file
        file_put_contents('includes/installed.lock', date('Y-m-d H:i:s'));
        
        $connection->close();
        
        // Clear session data
        unset($_SESSION['db_config'], $_SESSION['site_config'], $_SESSION['admin_config']);
        
        return true;
        
    } catch (Exception $e) {
        $_SESSION['install_error'] = 'Installation failed: ' . $e->getMessage();
        return false;
    }
}

// Function to create database tables
function createDatabaseTables($connection) {
    $tables = [
        'settings' => "CREATE TABLE `settings` (
            `settingid` int(11) NOT NULL AUTO_INCREMENT,
            `host` varchar(255) NOT NULL,
            `username` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `database` varchar(255) NOT NULL,
            `selecttopic` varchar(50) NOT NULL DEFAULT 'default',
            `installed` enum('yes','no') NOT NULL DEFAULT 'no',
            `site_title` varchar(255) NOT NULL,
            `site_description` text,
            `admin_email` varchar(255) NOT NULL,
            `timezone` varchar(50) NOT NULL DEFAULT 'UTC',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`settingid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'users' => "CREATE TABLE `users` (
            `userid` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(50) NOT NULL UNIQUE,
            `email` varchar(255) NOT NULL UNIQUE,
            `password` varchar(255) NOT NULL,
            `role` enum('admin','user','moderator') NOT NULL DEFAULT 'user',
            `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`userid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'core_categories' => "CREATE TABLE `core_categories` (
            `categoryid` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `slug` varchar(100) NOT NULL UNIQUE,
            `description` text,
            `parent_id` int(11) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`categoryid`),
            KEY `parent_id` (`parent_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'posts' => "CREATE TABLE `posts` (
            `postid` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL UNIQUE,
            `content` longtext NOT NULL,
            `excerpt` text,
            `author_id` int(11) NOT NULL,
            `category_id` int(11) DEFAULT NULL,
            `status` enum('published','draft','private') NOT NULL DEFAULT 'draft',
            `featured_image` varchar(255) DEFAULT NULL,
            `views` int(11) NOT NULL DEFAULT 0,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`postid`),
            KEY `author_id` (`author_id`),
            KEY `category_id` (`category_id`),
            KEY `status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'pages' => "CREATE TABLE `pages` (
            `pageid` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `content` longtext NOT NULL,
            `status` enum('published','draft') NOT NULL DEFAULT 'published',
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`pageid`),
            UNIQUE KEY `slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    ];
    
    foreach ($tables as $table_name => $sql) {
        if (!$connection->query($sql)) {
            throw new Exception("Failed to create table {$table_name}: " . $connection->error);
        }
    }
}

// Function to insert initial data
function insertInitialData($connection, $site_config, $admin_config) {
    // DB password is NOT stored in settings — only in includes/db_config.php
    $emptyPassword = '';
    $settings_sql = "INSERT INTO settings (host, username, password, `database`, selecttopic, installed, site_title, site_description, admin_email, timezone) VALUES (?, ?, ?, ?, ?, 'yes', ?, ?, ?, ?)";
    $stmt = $connection->prepare($settings_sql);
    if (!$stmt) {
        throw new Exception('Failed to prepare settings insert: ' . $connection->error);
    }
    $host = $_SESSION['db_config']['host'];
    $dbUser = $_SESSION['db_config']['username'];
    $dbName = $_SESSION['db_config']['database'];
    $topic = $site_config['topic'];
    $title = $site_config['title'];
    $description = $site_config['description'];
    $adminEmail = $site_config['admin_email'];
    $timezone = $site_config['timezone'];
    $stmt->bind_param(
        'sssssssss',
        $host,
        $dbUser,
        $emptyPassword,
        $dbName,
        $topic,
        $title,
        $description,
        $adminEmail,
        $timezone
    );
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert settings: ' . $stmt->error);
    }
    $stmt->close();
    
    // Insert admin user (role hardcoded server-side)
    $admin_sql = "INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, 'admin', 'active')";
    $stmt = $connection->prepare($admin_sql);
    $hashed_password = password_hash($admin_config['password'], PASSWORD_DEFAULT);
    $adminUser = $admin_config['username'];
    $adminMail = $admin_config['email'];
    $stmt->bind_param('sss', $adminUser, $adminMail, $hashed_password);
    if (!$stmt->execute()) {
        throw new Exception('Failed to create admin user: ' . $stmt->error);
    }
    $stmt->close();
    
    // Fresh install gets starter categories; flagship packages seed their own
    if (($site_config['mode'] ?? 'fresh') !== 'flagship') {
        $default_categories = ['General', 'Technology', 'Business', 'Lifestyle'];
        foreach ($default_categories as $category) {
            $slug = strtolower(str_replace(' ', '-', $category));
            $cat_sql = "INSERT INTO core_categories (name, slug) VALUES (?, ?)";
            $stmt = $connection->prepare($cat_sql);
            $stmt->bind_param('ss', $category, $slug);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Function to create configuration file
function createConfigFile($db_config) {
    $config_content = "<?php
/**
 * Database Configuration
 * Auto-generated during installation — do not commit real credentials.
 */

define('DB_HOST', '" . addslashes($db_config['host']) . "');
define('DB_USERNAME', '" . addslashes($db_config['username']) . "');
define('DB_PASSWORD', '" . addslashes($db_config['password']) . "');
define('DB_NAME', '" . addslashes($db_config['database']) . "');
define('DB_CHARSET', 'utf8mb4');

define('SITE_INSTALLED', true);
define('INSTALLATION_DATE', '" . date('Y-m-d H:i:s') . "');
";
    
    if (file_put_contents('includes/db_config.php', $config_content) === false) {
        throw new Exception('Failed to write includes/db_config.php');
    }

    $env = "<?php\n/** Runtime environment — generated by installer */\ndefine('MULTICMS_ENV', 'production');\n";
    if (file_put_contents('includes/env.php', $env) === false) {
        throw new Exception('Failed to write includes/env.php');
    }
}

// Perform installation if on installation step
if ($current_step === 'installation') {
    if (performInstallation()) {
        header('Location: install.php?step=complete');
        exit;
    }
}

// Get requirements for requirements step
$requirements = checkRequirements();
$all_requirements_met = true;
foreach ($requirements as $req) {
    if (!$req['status'] && empty($req['optional'])) {
        $all_requirements_met = false;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Multi-Content Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .install-container { max-width: 800px; margin: 2rem auto; }
        .step-indicator { margin-bottom: 2rem; }
        .step-item { 
            display: inline-block; 
            margin-right: 1rem; 
            padding: 0.5rem 1rem; 
            border-radius: 20px; 
            font-size: 0.9rem; 
        }
        .step-active { background-color: #007bff; color: white; }
        .step-completed { background-color: #28a745; color: white; }
        .step-pending { background-color: #6c757d; color: white; }
        .install-card { 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); 
            padding: 2rem; 
        }
        .requirement-item { 
            padding: 0.75rem; 
            border-radius: 5px; 
            margin-bottom: 0.5rem; 
        }
        .requirement-success { background-color: #d4edda; border: 1px solid #c3e6cb; }
        .requirement-error { background-color: #f8d7da; border: 1px solid #f5c6cb; }
        .form-group { margin-bottom: 1.5rem; }
        .btn-install { padding: 0.75rem 2rem; font-size: 1.1rem; }
    </style>
</head>
<body>
    <div class="install-container">
        <!-- Header -->
        <div class="text-center mb-4">
            <h1 class="display-4 text-primary">
                <i class="fas fa-cogs"></i> Multi-Content CMS
            </h1>
            <p class="lead text-muted">Installation Wizard</p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator text-center">
            <?php foreach ($steps as $step_key => $step_name): ?>
                <?php
                $step_class = 'step-pending';
                if ($step_key === $current_step) {
                    $step_class = 'step-active';
                } elseif (array_search($step_key, array_keys($steps)) < array_search($current_step, array_keys($steps))) {
                    $step_class = 'step-completed';
                }
                ?>
                <span class="step-item <?php echo $step_class; ?>">
                    <?php echo $step_name; ?>
                </span>
            <?php endforeach; ?>
        </div>

        <!-- Main Content -->
        <div class="install-card">
            <?php if (isset($_SESSION['install_error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($_SESSION['install_error']); ?>
                </div>
                <?php unset($_SESSION['install_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['install_success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['install_success']); ?>
                </div>
                <?php unset($_SESSION['install_success']); ?>
            <?php endif; ?>

            <?php switch ($current_step): 
                case 'welcome': ?>
                    <div class="text-center">
                        <i class="fas fa-rocket fa-3x text-primary mb-3"></i>
                        <h2>Welcome to Multi-Content CMS</h2>
                        <p class="lead">This wizard will help you install and configure your new content management system.</p>
                        <div class="mt-4">
                            <a href="install.php?step=requirements" class="btn btn-primary btn-install">
                                <i class="fas fa-arrow-right"></i> Get Started
                            </a>
                        </div>
                    </div>
                    <?php break; ?>

                <?php case 'requirements': ?>
                    <h2><i class="fas fa-clipboard-check"></i> Host compatibility check</h2>
                    <p>We checked this hosting automatically. <span class="text-success fw-bold">Green</span> = ready, <span class="text-danger fw-bold">Red</span> = must fix before install.</p>
                    
                    <?php foreach ($requirements as $req): ?>
                        <div class="requirement-item <?php echo $req['status'] ? 'requirement-success' : (empty($req['optional']) ? 'requirement-error' : 'requirement-success'); ?>" style="<?php echo (!$req['status'] && !empty($req['optional'])) ? 'background:#fff3cd;border-color:#ffecb5;' : ''; ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>
                                        <?php if ($req['status']): ?>
                                            <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                        <?php elseif (!empty($req['optional'])): ?>
                                            <span class="text-warning"><i class="fas fa-exclamation-circle"></i></span>
                                        <?php else: ?>
                                            <span class="text-danger"><i class="fas fa-times-circle"></i></span>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($req['name']); ?>
                                    </strong>
                                    <br>
                                    <small class="text-muted"><?php echo htmlspecialchars($req['description']); ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold <?php echo $req['status'] ? 'text-success' : (empty($req['optional']) ? 'text-danger' : 'text-warning'); ?>">
                                        <?php echo $req['status'] ? 'PASS' : (empty($req['optional']) ? 'FAIL' : 'WARN'); ?>
                                    </div>
                                    <div><?php echo htmlspecialchars($req['current']); ?></div>
                                    <small class="text-muted">Need: <?php echo htmlspecialchars($req['required']); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="mt-4">
                        <?php if ($all_requirements_met): ?>
                            <div class="alert alert-success"><i class="fas fa-check"></i> This host looks compatible. Continue when ready.</div>
                            <a href="install.php?step=database" class="btn btn-success btn-install">
                                <i class="fas fa-arrow-right"></i> Continue to Database Setup
                            </a>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Fix the red items with your host (enable PHP extensions / folder permissions), then refresh this page.
                            </div>
                            <a href="install.php?step=requirements" class="btn btn-outline-primary">Refresh check</a>
                        <?php endif; ?>
                    </div>
                    <?php break; ?>

                <?php case 'database': ?>
                    <h2><i class="fas fa-database"></i> Database Configuration</h2>
                    <p>Enter your database connection details:</p>
                    
                    <form method="POST" action="install.php?step=database">
                        <?php echo multicms_csrf_field(); ?>
                        <div class="form-group">
                            <label for="db_host" class="form-label">Database Host</label>
                            <input type="text" class="form-control" id="db_host" name="db_host" 
                                   value="<?php echo htmlspecialchars($_SESSION['db_config']['host'] ?? 'localhost'); ?>" required>
                            <div class="form-text">Usually 'localhost' or your database server IP</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="db_username" class="form-label">Database Username</label>
                            <input type="text" class="form-control" id="db_username" name="db_username" 
                                   value="<?php echo htmlspecialchars($_SESSION['db_config']['username'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="db_password" class="form-label">Database Password</label>
                            <input type="password" class="form-control" id="db_password" name="db_password" 
                                   value="<?php echo htmlspecialchars($_SESSION['db_config']['password'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="db_name" class="form-label">Database Name</label>
                            <input type="text" class="form-control" id="db_name" name="db_name" 
                                   value="<?php echo htmlspecialchars($_SESSION['db_config']['database'] ?? ''); ?>" required>
                            <div class="form-text">The database will be created if it doesn't exist</div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-install">
                                <i class="fas fa-database"></i> Test Connection & Continue
                            </button>
                        </div>
                    </form>
                    <?php break; ?>

                <?php case 'site_config': ?>
                    <h2><i class="fas fa-cog"></i> Site Configuration</h2>
                    <p>Configure your website settings:</p>
                    
                    <form method="POST" action="install.php?step=site_config">
                        <?php echo multicms_csrf_field(); ?>
                        <div class="form-group">
                            <label for="site_title" class="form-label">Site Title</label>
                            <input type="text" class="form-control" id="site_title" name="site_title" 
                                   value="<?php echo htmlspecialchars($_SESSION['site_config']['title'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="site_description" class="form-label">Site Description</label>
                            <textarea class="form-control" id="site_description" name="site_description" rows="3" required><?php echo htmlspecialchars($_SESSION['site_config']['description'] ?? ''); ?></textarea>
                        </div>

                        <?php
                        require_once __DIR__ . '/includes/FlagshipSite.php';
                        $flagships = FlagshipSite::listPackages();
                        $selMode = $_SESSION['site_config']['mode'] ?? 'fresh';
                        $selFlag = $_SESSION['site_config']['flagship_slug'] ?? 'blog';
                        ?>
                        <div class="form-group">
                            <label class="form-label">Start mode</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="start_mode" id="mode_fresh" value="fresh"
                                    <?php echo $selMode !== 'flagship' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="mode_fresh">
                                    <strong>Fresh</strong> — empty modern core (you create all content)
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="start_mode" id="mode_flagship" value="flagship"
                                    <?php echo $selMode === 'flagship' ? 'checked' : ''; ?>
                                    <?php echo empty($flagships) ? 'disabled' : ''; ?>>
                                <label class="form-check-label" for="mode_flagship">
                                    <strong>Flagship site</strong> — 1-click starter with sample posts and pages
                                </label>
                            </div>
                        </div>
                        <?php if (!empty($flagships)): ?>
                        <div class="form-group">
                            <label for="flagship_slug" class="form-label">Flagship package</label>
                            <select class="form-select" id="flagship_slug" name="flagship_slug">
                                <?php foreach ($flagships as $pkg): ?>
                                <option value="<?php echo htmlspecialchars($pkg['slug'], ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php echo $selFlag === $pkg['slug'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($pkg['name'] . ' — ' . $pkg['description'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="admin_email" class="form-label">Admin Email</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email" 
                                   value="<?php echo htmlspecialchars($_SESSION['site_config']['admin_email'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="timezone" class="form-label">Timezone</label>
                            <select class="form-select" id="timezone" name="timezone">
                                <option value="UTC" <?php echo (($_SESSION['site_config']['timezone'] ?? '') === 'UTC') ? 'selected' : ''; ?>>UTC</option>
                                <option value="America/New_York" <?php echo (($_SESSION['site_config']['timezone'] ?? '') === 'America/New_York') ? 'selected' : ''; ?>>Eastern Time</option>
                                <option value="America/Chicago" <?php echo (($_SESSION['site_config']['timezone'] ?? '') === 'America/Chicago') ? 'selected' : ''; ?>>Central Time</option>
                                <option value="America/Denver" <?php echo (($_SESSION['site_config']['timezone'] ?? '') === 'America/Denver') ? 'selected' : ''; ?>>Mountain Time</option>
                                <option value="America/Los_Angeles" <?php echo (($_SESSION['site_config']['timezone'] ?? '') === 'America/Los_Angeles') ? 'selected' : ''; ?>>Pacific Time</option>
                                <option value="Europe/London" <?php echo (($_SESSION['site_config']['timezone'] ?? '') === 'Europe/London') ? 'selected' : ''; ?>>London</option>
                                <option value="Europe/Paris" <?php echo (($_SESSION['site_config']['timezone'] ?? '') === 'Europe/Paris') ? 'selected' : ''; ?>>Paris</option>
                                <option value="Asia/Tokyo" <?php echo (($_SESSION['site_config']['timezone'] ?? '') === 'Asia/Tokyo') ? 'selected' : ''; ?>>Tokyo</option>
                            </select>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-install">
                                <i class="fas fa-arrow-right"></i> Continue to Admin Setup
                            </button>
                        </div>
                    </form>
                    <?php break; ?>

                <?php case 'admin_setup': ?>
                    <h2><i class="fas fa-user-shield"></i> Administrator Account</h2>
                    <p>Create your administrator account:</p>
                    
                    <form method="POST" action="install.php?step=admin_setup">
                        <?php echo multicms_csrf_field(); ?>
                        <div class="form-group">
                            <label for="admin_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="admin_username" name="admin_username" 
                                   value="<?php echo htmlspecialchars($_SESSION['admin_config']['username'] ?? ''); ?>" required>
                            <div class="form-text">Choose a unique username for your admin account</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                            <div class="form-text">Password must be at least 8 characters long</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="admin_confirm_password" name="admin_confirm_password" required>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-install">
                                <i class="fas fa-arrow-right"></i> Continue to Installation
                            </button>
                        </div>
                    </form>
                    <?php break; ?>

                <?php case 'installation': ?>
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                        <h2>Installing System</h2>
                        <p class="lead">Please wait while we set up your content management system...</p>
                        
                        <div class="progress mt-4">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 100%"></div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="install.php?step=installation" class="btn btn-primary btn-install">
                                <i class="fas fa-sync"></i> Retry Installation
                            </a>
                        </div>
                    </div>
                    <?php break; ?>

                <?php case 'complete': ?>
                    <div class="text-center">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h2>Installation Complete!</h2>
                        <p class="lead">Your Multi-Content CMS has been successfully installed.</p>
                        
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Next Steps:</h5>
                            <ul class="text-start">
                                <li>Pretty URLs: RewriteBase was set automatically for this install path</li>
                                <li>Config written: <code>includes/db_config.php</code>, <code>includes/env.php</code>, <code>includes/site_path.php</code>, lock file</li>
                                <li>Open Admin → Posts / Site Settings / Flagship Sites / Updates &amp; Backup</li>
                                <li><code>install.php</code> stays locked (like WordPress)</li>
                            </ul>
                        </div>
                        
                        <div class="mt-4">
                            <a href="administrator/" class="btn btn-primary btn-install me-3">
                                <i class="fas fa-cog"></i> Go to Admin Panel
                            </a>
                            <a href="index.php" class="btn btn-success btn-install">
                                <i class="fas fa-home"></i> View Your Site
                            </a>
                        </div>
                        
                        <div class="mt-4">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i>
                                install.php is now locked — same idea as WordPress (no need to delete it)
                            </small>
                        </div>
                    </div>
                    <?php break; ?>

            <?php endswitch; ?>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-4">
            <small class="text-muted">
                Multi-Content CMS Installation Wizard &copy; <?php echo date('Y'); ?>
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-redirect on installation step
        <?php if ($current_step === 'installation'): ?>
        setTimeout(function() {
            window.location.href = 'install.php?step=complete';
        }, 3000);
        <?php endif; ?>
        
        // Password confirmation validation
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('admin_password');
            const confirmPassword = document.getElementById('admin_confirm_password');
            
            if (password && confirmPassword) {
                function validatePassword() {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity('Passwords do not match');
                    } else {
                        confirmPassword.setCustomValidity('');
                    }
                }
                
                password.addEventListener('change', validatePassword);
                confirmPassword.addEventListener('keyup', validatePassword);
            }
        });
    </script>
</body>
</html>