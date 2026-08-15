<?php
/**
 * Standalone 500 error page for Apache ErrorDocument.
 */
http_response_code(500);
header('Content-Type: text/html; charset=UTF-8');
$home = '/';
if (is_file(dirname(__DIR__, 3) . '/includes/site_path.php')) {
    require_once dirname(__DIR__, 3) . '/includes/site_path.php';
    if (defined('SITE_BASE_PATH')) {
        $base = rtrim((string) SITE_BASE_PATH, '/');
        $home = $base === '' ? '/' : $base . '/';
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="h3">Something went wrong</h1>
    <p class="text-muted">The server encountered an error. Please try again later.</p>
    <a class="btn btn-primary" href="<?php echo htmlspecialchars($home, ENT_QUOTES, 'UTF-8'); ?>">Back home</a>
</div>
</body>
</html>
