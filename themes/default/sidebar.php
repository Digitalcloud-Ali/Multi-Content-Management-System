<aside class="sidebar">
    <div class="card mb-3">
        <div class="card-header">Recent posts</div>
        <ul class="list-group list-group-flush">
            <?php if (empty($recentPosts)): ?>
                <li class="list-group-item text-muted">No posts yet</li>
            <?php else: ?>
                <?php foreach ($recentPosts as $p): ?>
                    <li class="list-group-item">
                        <a href="index.php?page=<?php echo (int) ($p['id'] ?? $p['postid']); ?>">
                            <?php echo htmlspecialchars($p['title'] ?? ''); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    <div class="card mb-3">
        <div class="card-header">Popular</div>
        <ul class="list-group list-group-flush">
            <?php if (empty($popularPosts)): ?>
                <li class="list-group-item text-muted">No posts yet</li>
            <?php else: ?>
                <?php foreach ($popularPosts as $p): ?>
                    <li class="list-group-item">
                        <a href="index.php?page=<?php echo (int) ($p['id'] ?? $p['postid']); ?>">
                            <?php echo htmlspecialchars($p['title'] ?? ''); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    <div class="card mb-3">
        <div class="card-header">Categories</div>
        <ul class="list-group list-group-flush">
            <?php if (empty($categories)): ?>
                <li class="list-group-item text-muted">No categories</li>
            <?php else: ?>
                <?php foreach ($categories as $c): ?>
                    <li class="list-group-item"><?php echo htmlspecialchars($c['name'] ?? ''); ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</aside>
