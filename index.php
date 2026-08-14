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
    } else {
        displayError("Database connection failed. Check configuration.", 'error');
    }
    exit;
}

$authService = new AuthService();
$contentService = new ContentService();
$resolved = mc_resolve_request($contentService);
$page = $resolved['page'];
$post = $resolved['post'];
$cmsPage = $resolved['cms_page'];

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
$theme = mc_theme_path();

include $theme . '/header.php';
?>

<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                switch ($page) {
                    case 'home':
                        include $theme . '/home.php';
                        break;
                    case 'blog':
                        include $theme . '/blog.php';
                        break;
                    case 'about':
                        include $theme . '/about.php';
                        break;
                    case 'contact':
                        include $theme . '/contact.php';
                        break;
                    case 'login':
                        if ($authService->isAuthenticated()) {
                            safeRedirect(mc_url(), 'You are already logged in');
                        }
                        include $theme . '/login.php';
                        break;
                    case 'register':
                        if ($authService->isAuthenticated()) {
                            safeRedirect(mc_url(), 'You are already logged in');
                        }
                        include $theme . '/register.php';
                        break;
                    case 'profile':
                        if (!$authService->isAuthenticated()) {
                            safeRedirect(mc_url('login'), 'Please login to access your profile');
                        }
                        include $theme . '/profile.php';
                        break;
                    case 'logout':
                        $authService->logout();
                        safeRedirect(mc_url(), 'You have been logged out successfully');
                        break;
                    case 'single':
                        include $theme . '/single-post.php';
                        break;
                    case 'cms_page':
                        include $theme . '/cms-page.php';
                        break;
                    default:
                        include $theme . '/404.php';
                        break;
                }
                ?>
            </div>
            <div class="col-md-4">
                <?php include $theme . '/sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include $theme . '/footer.php'; ?>
