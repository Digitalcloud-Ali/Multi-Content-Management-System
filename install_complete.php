<?php
/**
 * Installation Completion Script
 * Run this after successful installation to clean up and finalize
 */

// Check if system is already installed
if (!file_exists('includes/installed.lock')) {
    die('System is not installed. Please run install.php first.');
}

// Check if user is logged in as admin
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die('Access denied. Admin privileges required.');
}

// Handle cleanup actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'delete_install':
            if (unlink('install.php')) {
                $message = 'Installation file deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to delete installation file.';
                $message_type = 'error';
            }
            break;
            
        case 'delete_complete':
            if (unlink('install_complete.php')) {
                $message = 'Completion script deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Failed to delete completion script.';
                $message_type = 'error';
            }
            break;
            
        case 'optimize_database':
            try {
                require_once 'includes/bootstrap.php';
                $db = getDB();
                
                // Optimize tables
                $tables = ['settings', 'users', 'categories', 'posts'];
                foreach ($tables as $table) {
                    $db->execute("OPTIMIZE TABLE `$table`");
                }
                
                $message = 'Database optimized successfully.';
                $message_type = 'success';
            } catch (Exception $e) {
                $message = 'Database optimization failed: ' . $e->getMessage();
                $message_type = 'error';
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Complete - Multi-Content CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-check-circle"></i> Installation Complete
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($message)): ?>
                            <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?>">
                                <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Installation Status</h5>
                            <ul class="mb-0">
                                <li>✅ System installed successfully</li>
                                <li>✅ Database configured and populated</li>
                                <li>✅ Administrator account created</li>
                                <li>✅ Basic content structure created</li>
                            </ul>
                        </div>
                        
                        <h5><i class="fas fa-tasks"></i> Post-Installation Tasks</h5>
                        <p>Complete these tasks to finalize your installation:</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6><i class="fas fa-database"></i> Database Optimization</h6>
                                        <p class="small text-muted">Optimize database tables for better performance</p>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="optimize_database">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-cog"></i> Optimize
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6><i class="fas fa-shield-alt"></i> Security Cleanup</h6>
                                        <p class="small text-muted">Remove installation files for security</p>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_install">
                                            <button type="submit" class="btn btn-warning btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete the installation file?')">
                                                <i class="fas fa-trash"></i> Delete install.php
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> Important Notes:</h6>
                            <ul class="mb-0">
                                <li>Change your admin password after first login</li>
                                <li>Configure your site settings in the admin panel</li>
                                <li>Set up your preferred theme and customize appearance</li>
                                <li>Regularly backup your database and files</li>
                            </ul>
                        </div>
                        
                        <div class="text-center">
                            <a href="administrator/" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-cog"></i> Go to Admin Panel
                            </a>
                            <a href="index.php" class="btn btn-success btn-lg">
                                <i class="fas fa-home"></i> View Your Site
                            </a>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i> 
                                For security, delete this completion script after use
                            </small>
                            <br>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete_complete">
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Delete this completion script?')">
                                    <i class="fas fa-trash"></i> Delete This Script
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
