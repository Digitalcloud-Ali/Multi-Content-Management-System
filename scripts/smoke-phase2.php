<?php
/**
 * Fresh + Flagship acceptance smoke.
 * Docker MySQL: root/smoke_test_pass on 127.0.0.1:3307, database multicms_smoke
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

foreach (['includes/db_config.php', 'includes/installed.lock', 'includes/active_site.json', 'includes/env.php'] as $f) {
    if (is_file($root . '/' . $f)) {
        @unlink($root . '/' . $f);
    }
}

$host = '127.0.0.1';
$port = 3307;
$user = 'root';
$pass = 'smoke_test_pass';
$dbName = 'multicms_smoke';

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($host, $user, $pass, '', $port);
if ($conn->connect_error) {
    fwrite(STDERR, "DB connect failed: {$conn->connect_error}\n");
    exit(2);
}
$conn->query("DROP DATABASE IF EXISTS `$dbName`");
$conn->query("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($dbName);

require_once $root . '/includes/LegacyAuth.php';
require_once $root . '/includes/PluginManager.php';
require_once $root . '/includes/FlagshipSite.php';

foreach ([
    "CREATE TABLE settings (settingid int AUTO_INCREMENT PRIMARY KEY, host varchar(255), username varchar(255), password varchar(255), `database` varchar(255), selecttopic varchar(50) DEFAULT 'default', installed enum('yes','no') DEFAULT 'no', site_title varchar(255), site_description text, admin_email varchar(255), timezone varchar(50) DEFAULT 'UTC')",
    "CREATE TABLE users (userid int AUTO_INCREMENT PRIMARY KEY, username varchar(50) UNIQUE, email varchar(255) UNIQUE, password varchar(255), role enum('admin','user','moderator') DEFAULT 'user', status enum('active','inactive','banned') DEFAULT 'active')",
    "CREATE TABLE core_categories (categoryid int AUTO_INCREMENT PRIMARY KEY, name varchar(100), slug varchar(100) UNIQUE, description text)",
    "CREATE TABLE posts (postid int AUTO_INCREMENT PRIMARY KEY, title varchar(255), slug varchar(255) UNIQUE, content longtext, excerpt text, author_id int, category_id int, status enum('published','draft','private') DEFAULT 'draft', views int DEFAULT 0, created_at timestamp NULL, updated_at timestamp NULL)",
] as $sql) {
    if (!$conn->query($sql)) {
        ok('create tables', false, $conn->error);
        exit(1);
    }
}
ok('create tables', true);

$hash = password_hash('SmokeTest1!', PASSWORD_DEFAULT);
$conn->query("INSERT INTO settings (host, username, password, `database`, selecttopic, installed, site_title, site_description, admin_email, timezone) VALUES ('127.0.0.1','root','','multicms_smoke','default','yes','Smoke CMS','Smoke','admin@example.com','UTC')");
$conn->query("INSERT INTO users (username, email, password, role, status) VALUES ('admin','admin@example.com','" . $conn->real_escape_string($hash) . "','admin','active')");

file_put_contents($root . '/includes/db_config.php', "<?php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3307);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'smoke_test_pass');
define('DB_NAME', 'multicms_smoke');
define('DB_CHARSET', 'utf8mb4');
");
file_put_contents($root . '/includes/installed.lock', date('c'));
file_put_contents($root . '/includes/env.php', "<?php\ndefine('MULTICMS_ENV', 'production');\n");

$authorId = (int) $conn->query("SELECT userid FROM users WHERE username='admin'")->fetch_assoc()['userid'];

PluginManager::applyFreshDefault($conn);
$topic = $conn->query("SELECT selecttopic FROM settings WHERE settingid=1")->fetch_assoc();
ok('selecttopic=default', ($topic['selecttopic'] ?? '') === 'default');

$pkgs = FlagshipSite::listPackages();
$slugs = array_column($pkgs, 'slug');
sort($slugs);
ok('all flagships listed', $slugs === ['blog', 'business', 'clinic', 'nonprofit', 'portfolio'], implode(',', $slugs));

$applied = FlagshipSite::apply('blog', $conn, $authorId);
ok('flagship blog apply', !empty($applied['success']), $applied['message'] ?? '');

$postCount = (int) ($conn->query("SELECT COUNT(*) AS c FROM posts WHERE status='published'")->fetch_assoc()['c'] ?? 0);
ok('flagship posts seeded', $postCount >= 3, "count=$postCount");

$pageCount = (int) ($conn->query("SELECT COUNT(*) AS c FROM pages WHERE status='published'")->fetch_assoc()['c'] ?? 0);
ok('flagship pages seeded', $pageCount >= 2, "count=$pageCount");

$biz = FlagshipSite::apply('business', $conn, $authorId);
ok('flagship business apply', !empty($biz['success']), $biz['message'] ?? '');
$bizPost = $conn->query("SELECT slug FROM posts WHERE slug='welcome-business-site'")->fetch_assoc();
ok('business post present', ($bizPost['slug'] ?? '') === 'welcome-business-site');

$again = FlagshipSite::apply('blog', $conn, $authorId);
$postCount2 = (int) ($conn->query("SELECT COUNT(*) AS c FROM posts")->fetch_assoc()['c'] ?? 0);
ok('re-apply skips duplicates', $postCount2 >= $postCount && !empty($again['success']));

$active = json_decode((string) @file_get_contents($root . '/includes/active_site.json'), true);
ok('active_site flagship', ($active['mode'] ?? '') === 'flagship' && ($active['slug'] ?? '') === 'blog');

ok('uploads dir exists', is_dir($root . '/uploads'));
ok('uploads htaccess deny php', is_file($root . '/uploads/.htaccess'));
ok('root htaccess blocks uploads php', strpos((string) file_get_contents($root . '/.htaccess'), 'uploads') !== false);
ok('no dreamweaver packs', count(glob($root . '/plugins/*/plugin.json')) === 0);
ok('flagship admin ui', is_file($root . '/administrator/flagship_sites.php'));

require_once $root . '/includes/bootstrap.php';
ok('routing helpers loaded', function_exists('mc_url') && function_exists('mc_resolve_request'));
$_GET = ['mc_route' => 'blog'];
$resolved = mc_resolve_request(new ContentService());
ok('resolve /blog', ($resolved['page'] ?? '') === 'blog');
$_GET = ['mc_route' => 'post/welcome-to-multicms-blog'];
$resolved = mc_resolve_request(new ContentService());
ok('resolve post slug', ($resolved['page'] ?? '') === 'single' && !empty($resolved['post']['slug']));
$_GET = [];

$row = getDB()->queryOne("SELECT title, status FROM posts WHERE slug=?", 's', ['welcome-to-multicms-blog']);
ok('post readable via bootstrap', ($row['status'] ?? '') === 'published');
$page = getDB()->queryOne("SELECT title FROM pages WHERE slug=?", 's', ['about']);
ok('about page readable', !empty($page['title']));

ok('CSRF reject', multicms_csrf_validate('bad') === false);
ok('dashboard file', is_file($root . '/administrator/dashboard.php'));

passthru('php "' . $root . '/scripts/php-lint-smoke.php"', $lintCode);
ok('php-lint-smoke', $lintCode === 0);

echo PHP_EOL . ($fail === 0 ? 'ALL PASS' : "$fail FAILED") . PHP_EOL;
exit($fail === 0 ? 0 : 1);
