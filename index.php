<?php
/**
 * Main Entry Point - Multi-Content Management System
 */

require_once 'includes/bootstrap.php';

try {
    $db = getDB();
    $settings = $db->queryOne("SELECT * FROM settings WHERE settingid = 1");

    if (!$settings || !isset($settings['installed']) || $settings['installed'] !== 'yes') {
        header("Location: install.php");
        exit;
    }
} catch (Exception $e) {
    logError("System check error: " . $e->getMessage(), 'ERROR');
    if (ENVIRONMENT === 'production') {
        echo "<!DOCTYPE html><html><head><title>System Maintenance</title></head><body><p>System maintenance.</p></body></html>";
        exit;
    }
    displayError("Database connection failed. Check configuration.", 'error');
    exit;
}

$authService = new AuthService();
$contentService = new ContentService();

$page = $_GET['page'] ?? 'home';
$page = Validator::sanitize($page);

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

$currentUser = $authService->getCurrentUser();

include 'themes/default/header.php';
?>

<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
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
            <div class="col-md-4">
                <?php include 'themes/default/sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'themes/default/footer.php'; ?>
