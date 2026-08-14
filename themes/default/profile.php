<?php
$pageTitle = 'Profile';
$user = $currentUser ?? $authService->getCurrentUser();
?>
<h1 class="mb-3">Your profile</h1>
<?php if (!$user): ?>
    <p>Not logged in.</p>
<?php else: ?>
    <dl class="row">
        <dt class="col-sm-3">Username</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($user['username'] ?? ''); ?></dd>
        <dt class="col-sm-3">Email</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($user['email'] ?? ''); ?></dd>
        <dt class="col-sm-3">Role</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($user['role'] ?? ''); ?></dd>
    </dl>
    <a class="btn btn-outline-secondary" href="index.php?page=logout">Log out</a>
<?php endif; ?>
