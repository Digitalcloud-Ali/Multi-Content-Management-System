<?php
/**
 * Search results template.
 */
$q = trim((string) ($_GET['q'] ?? ''));
$pageNum = max(1, (int) ($_GET['p'] ?? 1));
$results = ['posts' => [], 'total' => 0, 'pages' => 0, 'current_page' => 1, 'search_query' => $q];

if ($q !== '' && isset($contentService) && method_exists($contentService, 'searchPosts')) {
    $results = $contentService->searchPosts($q, $pageNum, 10);
}
?>
<h1 class="h3 mb-3">Search</h1>
<form class="mb-4" method="get" action="<?php echo htmlspecialchars(mc_url('search'), ENT_QUOTES, 'UTF-8'); ?>">
    <div class="input-group">
        <input class="form-control" type="search" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Search posts…" required>
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>

<?php if ($q === ''): ?>
    <p class="text-muted">Enter a term to search published posts.</p>
<?php elseif (empty($results['posts'])): ?>
    <p class="text-muted">No posts matched “<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>”.</p>
<?php else: ?>
    <p class="text-muted mb-3"><?php echo (int) $results['total']; ?> result(s) for “<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>”</p>
    <?php foreach ($results['posts'] as $postRow): ?>
        <?php
        $slug = $postRow['slug'] ?? '';
        $href = $slug !== '' ? mc_url($slug) : mc_url('blog');
        $title = $postRow['title'] ?? 'Untitled';
        $excerpt = $postRow['excerpt'] ?? '';
        if ($excerpt === '' && !empty($postRow['content'])) {
            $excerpt = mb_substr(strip_tags((string) $postRow['content']), 0, 160) . '…';
        }
        ?>
        <article class="mb-4 pb-3 border-bottom">
            <h2 class="h5 mb-1">
                <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>" class="text-decoration-none">
                    <?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </h2>
            <?php if ($excerpt !== ''): ?>
                <p class="mb-0 text-muted"><?php echo htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>

    <?php if (($results['pages'] ?? 0) > 1): ?>
        <nav class="mt-3" aria-label="Search pages">
            <ul class="pagination">
                <?php for ($i = 1; $i <= (int) $results['pages']; $i++): ?>
                    <li class="page-item <?php echo $i === (int) $results['current_page'] ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo htmlspecialchars(mc_url('search', ['q' => $q, 'p' => $i]), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>
