<?php
/**
 * Standalone HTTP error pages (Apache ErrorDocument + in-theme partials).
 * When included from index.php, only the body fragment is used.
 * When hit directly by Apache, render a full HTML document.
 */
$__mc_error_standalone = (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === basename(__FILE__));
if ($__mc_error_standalone) {
    http_response_code(404);
    header('Content-Type: text/html; charset=UTF-8');
    $home = '/';
    if (is_file(dirname(__DIR__, 3) . '/includes/site_path.php')) {
        require_once dirname(__DIR__, 3) . '/includes/site_path.php';
        if (defined('SITE_BASE_PATH')) {
            $base = rtrim((string) SITE_BASE_PATH, '/');
            $home = $base === '' ? '/' : $base . '/';
        }
    }
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>Not found</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light">';
    echo '<div class="container py-5"><h1 class="h3">Page not found</h1><p class="text-muted">The page you requested does not exist.</p>';
    echo '<a class="btn btn-primary" href="' . htmlspecialchars($home, ENT_QUOTES, 'UTF-8') . '">Back home</a></div></body></html>';
    exit;
}
$pageTitle = 'Not found';
http_response_code(404);
?>
<h1 class="mb-3">Page not found</h1>
<p>The page you requested does not exist.</p>
<a href="<?php echo htmlspecialchars(function_exists('mc_url') ? mc_url() : '/', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary">Back home</a>
