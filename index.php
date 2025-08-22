<?php
/**
 * Main Entry Point - Multi-Content Management System
 * Now uses modern bootstrap system and classes
 */

// Include the modern bootstrap
require_once 'includes/bootstrap.php';

// Check if system is installed
try {
    $db = getDB();
    $settings = $db->queryOne("SELECT * FROM settings WHERE settingid = 1");
    
    if (!$settings || !isset($settings['installed']) || $settings['installed'] !== 'yes') {
        // Redirect to installation
        header("Location: install.php");
        exit;
    }
    
} catch (Exception $e) {
    logError("System check error: " . $e->getMessage(), 'ERROR');
    
    // If database is not available, show maintenance page
    if (ENVIRONMENT === 'production') {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>System Maintenance</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                .maintenance { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 30px; }
            </style>
        </head>
        <body>
            <div class='maintenance'>
                <h1>🛠️ System Maintenance</h1>
                <p>We're currently performing system maintenance. Please check back soon.</p>
                <p>If this issue persists, please contact support.</p>
            </div>
        </body>
        </html>";
        exit;
    } else {
        // Development: show error
        displayError("Database connection failed. Check configuration.", 'error');
        exit;
    }
}

// Initialize services
$authService = new AuthService();
$contentService = new ContentService();

// Get current page from URL
$page = $_GET['page'] ?? 'home';
$page = Validator::sanitize($page);

// Get popular and recent posts for sidebar
try {
    $popularPosts = $contentService->getPopularPosts(5);
    $recentPosts = $contentService->getRecentPosts(5);
    $categories = $contentService->getCategories();
} catch (Exception $e) {
    logError("Content loading error: " . $e->getMessage(), 'ERROR');
    $popularPosts = [];
    $recentPosts = [];
    $categories = [];
}

// Get current user if logged in
$currentUser = $authService->getCurrentUser();

// Include header
include 'themes/default/header.php';
?>

<!-- Main Content Area -->
<div class="main-content">
    <div class="container">
        <div class="row">
            <!-- Main Content Column -->
            <div class="col-md-8">
                <?php
                // Route to appropriate content based on page
                switch ($page) {
                    case 'home':
                        include 'themes/default/home.php';
                        break;
                        
                    case 'blog':
                        include 'themes/default/blog.php';
                        break;
                        
                    case 'about':
                        include 'themes/default/about.php';
                        break;
                        
                    case 'contact':
                        include 'themes/default/contact.php';
                        break;
                        
                    case 'login':
                        if ($authService->isAuthenticated()) {
                            safeRedirect('index.php', 'You are already logged in');
                        }
                        include 'themes/default/login.php';
                        break;
                        
                    case 'register':
                        if ($authService->isAuthenticated()) {
                            safeRedirect('index.php', 'You are already logged in');
                        }
                        include 'themes/default/register.php';
                        break;
                        
                    case 'profile':
                        if (!$authService->isAuthenticated()) {
                            safeRedirect('index.php?page=login', 'Please login to access your profile');
                        }
                        include 'themes/default/profile.php';
                        break;
                        
                    case 'logout':
                        $authService->logout();
                        safeRedirect('index.php', 'You have been logged out successfully');
                        break;
                        
                    default:
                        // Check if it's a blog post
                        if (is_numeric($page)) {
                            $post = $contentService->getBlogPost($page);
                            if ($post) {
                                include 'themes/default/single-post.php';
                            } else {
                                include 'themes/default/404.php';
                            }
                        } else {
                            include 'themes/default/404.php';
                        }
                        break;
                }
                ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-md-4">
                <?php include 'themes/default/sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include 'themes/default/footer.php';
?>