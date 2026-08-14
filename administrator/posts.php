<?php
/**
 * Core Posts admin (modern users/posts schema) — Phase 2
 */
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/LegacyAuth.php';

Session::start();

$isAdmin = (function_exists('hasRole') && (hasRole('admin') || hasRole('administrator')))
    || (!empty($_SESSION['MM_UserGroup']) && in_array($_SESSION['MM_UserGroup'], ['admin', 'administrator'], true));

if (!$isAdmin) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$content = new ContentService();
$flash = '';
$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;

// Resolve categories table name
$catTable = 'core_categories';
$conn = $db->getConnection();
$r = $conn->query("SHOW TABLES LIKE 'core_categories'");
if (!$r || $r->num_rows === 0) {
    $r2 = $conn->query("SHOW COLUMNS FROM categories LIKE 'categoryid'");
    if ($r2 && $r2->num_rows > 0) {
        $catTable = 'categories';
    }
}

$userId = (int) (Session::get('MM_UserID') ?: 0);
if ($userId <= 0) {
    // Resolve from users table by username
    $u = $db->queryOne('SELECT userid FROM users WHERE username = ?', 's', [Session::get('MM_Username')]);
    $userId = (int) ($u['userid'] ?? 0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!multicms_csrf_validate($_POST['csrf_token'] ?? '')) {
        $flash = 'Invalid security token.';
    } else {
        $action = $_POST['action'] ?? '';
        if ($action === 'save') {
            $title = trim((string) ($_POST['title'] ?? ''));
            $contentBody = trim((string) ($_POST['content'] ?? ''));
            $excerpt = trim((string) ($_POST['excerpt'] ?? ''));
                    $categoryId = (int) ($_POST['category_id'] ?? 0);
                    if ($categoryId <= 0) {
                        $categoryId = 0;
                    }
                    $status = in_array($_POST['status'] ?? '', ['draft', 'published', 'private'], true)
                        ? $_POST['status'] : 'draft';
                    $postId = (int) ($_POST['postid'] ?? 0);
                    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
                    $slug = trim($slug, '-') ?: ('post-' . time());

                    if ($title === '' || strlen($contentBody) < 10) {
                        $flash = 'Title and content (min 10 chars) are required.';
                    } elseif ($postId > 0) {
                        $db->execute(
                            'UPDATE posts SET title=?, slug=?, content=?, excerpt=?, category_id=?, status=?, updated_at=NOW() WHERE postid=?',
                            'ssssisi',
                            [$title, $slug, $contentBody, $excerpt, $categoryId, $status, $postId]
                        );
                        $flash = 'Post updated.';
                        $editId = $postId;
                    } else {
                        if ($userId <= 0) {
                            $flash = 'Cannot resolve author user id. Re-login to admin.';
                        } else {
                            $db->execute(
                                'INSERT INTO posts (title, slug, content, excerpt, author_id, category_id, status, created_at, updated_at) VALUES (?,?,?,?,?,?,?,NOW(),NOW())',
                                'ssssiis',
                                [$title, $slug, $contentBody, $excerpt, $userId, $categoryId, $status]
                            );
                            $flash = 'Post created.';
                            $editId = 0;
                        }
                    }
        } elseif ($action === 'delete') {
            $postId = (int) ($_POST['postid'] ?? 0);
            if ($postId > 0) {
                $db->execute("UPDATE posts SET status='private', updated_at=NOW() WHERE postid=?", 'i', [$postId]);
                $flash = 'Post archived (private).';
            }
        }
    }
}

$posts = $db->queryAll('SELECT postid, title, status, created_at, views FROM posts ORDER BY postid DESC LIMIT 100');
$categories = $db->queryAll("SELECT categoryid, name FROM {$catTable} ORDER BY name ASC");
$editPost = null;
if ($editId > 0) {
    $editPost = $db->queryOne('SELECT * FROM posts WHERE postid=?', 'i', [$editId]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Posts — MultiCMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php
$siteTitle = 'MultiCMS';
$adminNavActive = 'posts';
include __DIR__ . '/_nav.php';
?>
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Posts</h1>
        <a class="btn btn-outline-success btn-sm" href="../index.php?page=blog" target="_blank" rel="noopener">View blog</a>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($flash); ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header"><?php echo $editPost ? 'Edit post #' . (int) $editPost['postid'] : 'New post'; ?></div>
                <div class="card-body">
                    <form method="post">
                        <?php echo multicms_csrf_field(); ?>
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="postid" value="<?php echo (int) ($editPost['postid'] ?? 0); ?>">
                        <div class="mb-2">
                            <label class="form-label">Title</label>
                            <input class="form-control" name="title" required value="<?php echo htmlspecialchars($editPost['title'] ?? ''); ?>">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Excerpt</label>
                            <textarea class="form-control" name="excerpt" rows="2"><?php echo htmlspecialchars($editPost['excerpt'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Content</label>
                            <textarea class="form-control" name="content" rows="8" required><?php echo htmlspecialchars($editPost['content'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id">
                                <option value="0">— none —</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo (int) $c['categoryid']; ?>"
                                        <?php echo ((int) ($editPost['category_id'] ?? 0) === (int) $c['categoryid']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <?php foreach (['draft', 'published', 'private'] as $st): ?>
                                    <option value="<?php echo $st; ?>" <?php echo (($editPost['status'] ?? 'draft') === $st) ? 'selected' : ''; ?>><?php echo $st; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Save</button>
                        <?php if ($editPost): ?>
                            <a class="btn btn-link" href="posts.php">Cancel edit</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">Recent posts</div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>ID</th><th>Title</th><th>Status</th><th>Views</th><th></th></tr></thead>
                        <tbody>
                        <?php if (empty($posts)): ?>
                            <tr><td colspan="5" class="text-muted">No posts yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($posts as $p): ?>
                                <tr>
                                    <td><?php echo (int) $p['postid']; ?></td>
                                    <td><?php echo htmlspecialchars($p['title']); ?></td>
                                    <td><?php echo htmlspecialchars($p['status']); ?></td>
                                    <td><?php echo (int) $p['views']; ?></td>
                                    <td class="text-nowrap">
                                        <a href="posts.php?edit=<?php echo (int) $p['postid']; ?>">Edit</a>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Archive this post?');">
                                            <?php echo multicms_csrf_field(); ?>
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="postid" value="<?php echo (int) $p['postid']; ?>">
                                            <button class="btn btn-link btn-sm text-danger p-0" type="submit">Archive</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
