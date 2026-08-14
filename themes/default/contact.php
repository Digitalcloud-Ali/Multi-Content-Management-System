<?php
$pageTitle = 'Contact';
$cmsPage = null;
if (isset($contentService) && method_exists($contentService, 'getPageBySlug')) {
    $cmsPage = $contentService->getPageBySlug('contact');
}
$sent = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $sent = true;
    }
}
$csrfToken = Session::generateCsrfToken();
?>
<?php if ($cmsPage): ?>
    <h1 class="mb-3"><?php echo htmlspecialchars($cmsPage['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <div class="page-body mb-4"><?php echo $cmsPage['content']; ?></div>
<?php else: ?>
    <h1 class="mb-3">Contact</h1>
<?php endif; ?>
<?php if ($sent): ?>
    <div class="alert alert-success">Thanks — your message was received (demo form; wire to mail in a later phase).</div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="post" class="mb-4">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea name="message" class="form-control" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send</button>
</form>
<p class="text-muted">Admin email: <?php echo htmlspecialchars(getSetting('admin_email', '')); ?></p>
