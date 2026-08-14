<?php
if (empty($post)) {
    include __DIR__ . '/404.php';
    return;
}
$pageTitle = $post['title'] ?? 'Post';
?>
<article>
    <h1 class="mb-3"><?php echo htmlspecialchars($post['title'] ?? ''); ?></h1>
    <p class="text-muted small">
        <?php echo htmlspecialchars($post['author_name'] ?? ''); ?>
        · <?php echo htmlspecialchars($post['created_at'] ?? ''); ?>
        · <?php echo (int) ($post['views'] ?? 0); ?> views
    </p>
    <div class="post-content">
        <?php echo $post['content'] ?? ''; ?>
    </div>
    <p class="mt-4"><a href="index.php?page=blog">&larr; Back to blog</a></p>
</article>
