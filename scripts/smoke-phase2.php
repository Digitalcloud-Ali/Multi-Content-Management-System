<?php
/**
 * Phase 2 acceptance smoke test (CLI). Expects Docker MySQL:
 *   docker run ... -e MYSQL_ROOT_PASSWORD=smoke_test_pass -e MYSQL_DATABASE=multicms_smoke -p 3307:3306 mysql:8.0
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

$root = dirname(__DIR__);
chdir($root);

$fail = 0;
function ok($name, $pass, $detail = '') {
    global $fail;
    if (!$pass) {
        $fail++;
    }
    echo ($pass ? '[PASS] ' : '[FAIL] ') . $name . ($detail !== '' ? " — $detail" : '') . PHP_EOL;
}

$host = '127.0.0.1';
$port = 3307;
$user = 'root';
$pass = 'smoke_test_pass';
$dbName = 'multicms_smoke';

foreach (['includes/db_config.php', 'includes/installed.lock', 'includes/active_site.json'] as $f) {
    if (is_file($root . '/' . $f)) {
        @unlink($root . '/' . $f);
    }
}

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($host, $user, $pass, '', $port);
if ($conn->connect_error) {
    fwrite(STDERR, "DB connect failed: {$conn->connect_error}\n");
    exit(2);
}
$conn->query("DROP DATABASE IF EXISTS `$dbName`");
$conn->query("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($dbName);
$conn->set_charset('utf8mb4');

require_once $root . '/includes/LegacyAuth.php';
require_once $root . '/includes/PluginManager.php';

$ddls = [
    "CREATE TABLE `settings` (
        `settingid` int(11) NOT NULL AUTO_INCREMENT,
        `host` varchar(255) NOT NULL,
        `username` varchar(255) NOT NULL,
        `password` varchar(255) NOT NULL,
        `database` varchar(255) NOT NULL,
        `selecttopic` varchar(50) NOT NULL DEFAULT 'default',
        `installed` enum('yes','no') NOT NULL DEFAULT 'no',
        `site_title` varchar(255) NOT NULL,
        `site_description` text,
        `admin_email` varchar(255) NOT NULL,
        `timezone` varchar(50) NOT NULL DEFAULT 'UTC',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`settingid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE `users` (
        `userid` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL UNIQUE,
        `email` varchar(255) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `role` enum('admin','user','moderator') NOT NULL DEFAULT 'user',
        `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`userid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE `core_categories` (
        `categoryid` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `slug` varchar(100) NOT NULL UNIQUE,
        `description` text,
        `parent_id` int(11) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`categoryid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE `posts` (
        `postid` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL UNIQUE,
        `content` longtext NOT NULL,
        `excerpt` text,
        `author_id` int(11) NOT NULL,
        `category_id` int(11) DEFAULT NULL,
        `status` enum('published','draft','private') NOT NULL DEFAULT 'draft',
        `featured_image` varchar(255) DEFAULT NULL,
        `views` int(11) NOT NULL DEFAULT 0,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`postid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];
foreach ($ddls as $sql) {
    if (!$conn->query($sql)) {
        ok('create modern tables', false, $conn->error);
        exit(1);
    }
}
ok('create modern tables', true);

$hash = password_hash('SmokeTest1!', PASSWORD_DEFAULT);
$conn->query("INSERT INTO settings (host, username, password, `database`, selecttopic, installed, site_title, site_description, admin_email, timezone)
    VALUES ('127.0.0.1', 'root', '', 'multicms_smoke', 'default', 'yes', 'Smoke CMS', 'Smoke test', 'admin@example.com', 'UTC')");
$conn->query("INSERT INTO users (username, email, password, role, status) VALUES ('admin', 'admin@example.com', '" . $conn->real_escape_string($hash) . "', 'admin', 'active')");
$conn->query("INSERT INTO core_categories (name, slug) VALUES ('General', 'general')");
ok('seed admin + settings', true);

file_put_contents($root . '/includes/db_config.php', "<?php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3307);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'smoke_test_pass');
define('DB_NAME', 'multicms_smoke');
define('DB_CHARSET', 'utf8mb4');
define('SITE_INSTALLED', true);
");
file_put_contents($root . '/includes/installed.lock', date('c'));
ok('db_config written', is_file($root . '/includes/db_config.php'));

$authorId = (int) $conn->query("SELECT userid FROM users WHERE username='admin'")->fetch_assoc()['userid'];
$title = 'Smoke Published Post';
$slug = 'smoke-published-post';
$content = 'This is a smoke-test published post body long enough.';
$excerpt = 'Smoke excerpt';
$stmt = $conn->prepare("INSERT INTO posts (title, slug, content, excerpt, author_id, category_id, status) VALUES (?,?,?,?,?,1,'published')");
$stmt->bind_param('ssssi', $title, $slug, $content, $excerpt, $authorId);
ok('create published post', $stmt->execute(), $stmt->error);
$stmt->close();

$pub = $conn->query("SELECT postid, status FROM posts WHERE slug='smoke-published-post'")->fetch_assoc();
ok('post published in DB', ($pub['status'] ?? '') === 'published', json_encode($pub));

$membersBefore = $conn->query("SHOW TABLES LIKE 'members'");
ok('members missing before apply', $membersBefore && $membersBefore->num_rows === 0);

$apply = PluginManager::applySiteAsMain('blog', $conn);
ok('applySiteAsMain(blog)', !empty($apply['success']), json_encode($apply));

ok('members table created', $conn->query("SHOW TABLES LIKE 'members'")->num_rows === 1);
ok('blog table created', $conn->query("SHOW TABLES LIKE 'blog'")->num_rows === 1);
$topic = $conn->query("SELECT selecttopic FROM settings WHERE settingid=1")->fetch_assoc();
ok('selecttopic=blog', ($topic['selecttopic'] ?? '') === 'blog', json_encode($topic));
ok('core_categories still present', $conn->query("SHOW TABLES LIKE 'core_categories'")->num_rows === 1);
ok('legacy categories present', $conn->query("SHOW TABLES LIKE 'categories'")->num_rows === 1);

try {
    ob_start();
    $_SERVER['REQUEST_URI'] = '/';
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_GET = [];
    $okDispatch = PluginManager::dispatchActiveSite('blog', '');
    $packHtml = (string) ob_get_clean();
    ok('dispatchActiveSite(blog) serves content', $okDispatch === true && strlen($packHtml) > 50, 'bytes=' . strlen($packHtml));
    ok('no Location redirect to /plugins/.../www/', stripos($packHtml, 'plugins/blog/www') === false || stripos($packHtml, 'Location:') === false);
} catch (Throwable $e) {
    if (ob_get_level()) {
        ob_end_clean();
    }
    ok('dispatchActiveSite(blog) serves content', false, $e->getMessage());
    ok('no Location redirect to /plugins/.../www/', false, $e->getMessage());
}

// Core listing path
$conn->query("UPDATE settings SET selecttopic='default' WHERE settingid=1");
PluginManager::applyFreshDefault($conn);

require_once $root . '/includes/bootstrap.php';
try {
    $row = getDB()->queryOne("SELECT title, status FROM posts WHERE slug=? AND status='published'", 's', ['smoke-published-post']);
    ok('published post available for theme/blog', ($row['title'] ?? '') === 'Smoke Published Post', json_encode($row));
    $cs = new ContentService();
    $recent = $cs->getRecentPosts(10);
    $found = false;
    foreach ((array) $recent as $p) {
        if (($p['slug'] ?? '') === 'smoke-published-post') {
            $found = true;
            break;
        }
    }
    ok('ContentService lists published post', $found, 'count=' . count((array) $recent));
} catch (Throwable $e) {
    ok('published post available for theme/blog', false, $e->getMessage());
    ok('ContentService lists published post', false, $e->getMessage());
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
ok('CSRF rejects bad token', multicms_csrf_validate('invalid') === false);
$token = multicms_csrf_token();
ok('CSRF accepts good token', multicms_csrf_validate($token) === true);

$tmpDir = sys_get_temp_dir() . '/multicms_smoke_up';
@mkdir($tmpDir, 0777, true);
$dest = $root . '/images/members';
@mkdir($dest, 0755, true);
$tmpFile = $tmpDir . '/evil.php';
file_put_contents($tmpFile, '<?php echo 1;');
$_FILES['photo'] = [
    'name' => 'evil.php',
    'type' => 'application/x-php',
    'tmp_name' => $tmpFile,
    'error' => UPLOAD_ERR_OK,
    'size' => filesize($tmpFile),
];
$up = multicms_safe_upload('photo', $dest);
ok('upload rejects .php', empty($up['success']) && stripos($up['message'] ?? '', 'not allowed') !== false, json_encode($up));

$tmp2 = $tmpDir . '/x.jpg';
file_put_contents($tmp2, 'not-really-image');
$_FILES['photo'] = [
    'name' => 'shell.php.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => $tmp2,
    'error' => UPLOAD_ERR_OK,
    'size' => filesize($tmp2),
];
$up2 = multicms_safe_upload('photo', $dest);
ok('upload rejects .php. in filename', empty($up2['success']), json_encode($up2));

ok('multicms_plain strips tags', multicms_plain('<b>Hi</b> & x') === 'Hi &amp; x');

// Re-apply blog to exercise Phase 4 seed hook
$conn->query("DELETE FROM blog");
require_once $root . '/includes/Hooks.php';
$apply2 = PluginManager::applySiteAsMain('blog', $conn);
ok('re-apply blog for seed', !empty($apply2['success']));
$blogCount = (int) ($conn->query('SELECT COUNT(*) AS c FROM blog')->fetch_assoc()['c'] ?? 0);
ok('blog sample post seeded', $blogCount >= 1, 'count=' . $blogCount);

ok('composer.json present', is_file($root . '/composer.json'));
ok('php-lint workflow present', is_file($root . '/.github/workflows/php-lint.yml'));
ok('demo-ready checklist present', is_file($root . '/docs/DEMO_READY.md'));
ok('Hooks API loaded', function_exists('do_action') && function_exists('apply_filters'));

$hookFired = false;
add_action('multicms_smoke_probe', function () use (&$hookFired) {
    $hookFired = true;
});
do_action('multicms_smoke_probe');
ok('hooks do_action fires', $hookFired === true);
add_filter('multicms_smoke_filter', function ($v) { return $v . 'b'; });
$filtered = apply_filters('multicms_smoke_filter', 'a');
ok('hooks apply_filters works', $filtered === 'ab');

passthru('php "' . $root . '/scripts/php-lint-smoke.php"', $lintCode);
ok('php-lint-smoke', $lintCode === 0, 'exit=' . $lintCode);

echo PHP_EOL . ($fail === 0 ? 'ALL PASS' : "$fail FAILED") . PHP_EOL;

// Leave smoke artifacts for optional HTTP follow-up; wipe secrets from db_config note in summary
exit($fail === 0 ? 0 : 1);
