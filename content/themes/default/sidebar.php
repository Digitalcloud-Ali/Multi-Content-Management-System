<aside class="sidebar">
    <div class="card mb-3">
        <div class="card-header">Recent posts</div>
        <ul class="list-group list-group-flush">
            <?php if (empty($recentPosts)): ?>
                <li class="list-group-item text-muted">No posts yet</li>
            <?php else: ?>
                <?php foreach ($recentPosts as $p): ?>
                    <li class="list-group-item">
                        <a href="<?php echo htmlspecialchars(mc_post_url($p), ENT_QUOTES, 'UTF-8'); ?>">
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
                        <a href="<?php echo htmlspecialchars(mc_post_url($p), ENT_QUOTES, 'UTF-8'); ?>">
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
                    <?php
                    $cid = (int) ($c['id'] ?? $c['categoryid'] ?? 0);
                    $cname = (string) ($c['name'] ?? '');
                    ?>
                    <li class="list-group-item">
                        <?php if ($cid > 0): ?>
                            <a href="<?php echo htmlspecialchars(mc_url('blog', ['category' => $cid]), ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($cname, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        <?php else: ?>
                            <?php echo htmlspecialchars($cname, ENT_QUOTES, 'UTF-8'); ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</aside>
