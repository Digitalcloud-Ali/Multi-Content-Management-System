<?php
$pageTitle = 'Blog';
$pageNum = max(1, (int) ($_GET['p'] ?? 1));
$result = $contentService->getBlogPosts($pageNum, 10);
$posts = $result['posts'] ?? [];
?>
<h1 class="mb-4">Blog</h1>
<?php if (empty($posts)): ?>
    <p class="text-muted">No published posts yet. Create content from Admin → Posts.</p>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <article class="mb-4 pb-3 border-bottom">
            <h2 class="h4">
                <a href="index.php?page=<?php echo (int) ($post['id'] ?? $post['postid']); ?>">
                    <?php echo htmlspecialchars($post['title'] ?? ''); ?>
                </a>
            </h2>
            <p class="text-muted small mb-2">
                <?php echo htmlspecialchars($post['author_name'] ?? 'Unknown'); ?>
                · <?php echo htmlspecialchars($post['created_at'] ?? ''); ?>
                <?php if (!empty($post['category_name'])): ?>
                    · <?php echo htmlspecialchars($post['category_name']); ?>
                <?php endif; ?>
            </p>
            <p><?php echo htmlspecialchars($post['excerpt'] ?? mb_substr(strip_tags($post['content'] ?? ''), 0, 200)); ?></p>
        </article>
    <?php endforeach; ?>
    <?php if (($result['pages'] ?? 1) > 1): ?>
        <nav>
            <?php for ($i = 1; $i <= $result['pages']; $i++): ?>
                <a class="btn btn-sm <?php echo $i === $pageNum ? 'btn-primary' : 'btn-outline-primary'; ?>" href="index.php?page=blog&p=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
