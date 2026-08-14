<?php
/**
 * Flagship site packages — 1-click complete starter sites on MultiCMS core.
 * Packages live under /sites/<slug>/ (manifest.json + content.json).
 */
class FlagshipSite {
    public static function sitesPath() {
        return dirname(__DIR__) . '/sites';
    }

    /**
     * @return array<int, array{slug:string,name:string,description:string,version:string,path:string}>
     */
    public static function listPackages() {
        $dir = self::sitesPath();
        $out = [];
        if (!is_dir($dir)) {
            return $out;
        }
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $base = $dir . '/' . $item;
            $manifest = $base . '/manifest.json';
            if (!is_dir($base) || !is_file($manifest)) {
                continue;
            }
            $raw = (string) file_get_contents($manifest);
            if (strncmp($raw, "\xEF\xBB\xBF", 3) === 0) {
                $raw = substr($raw, 3);
            }
            $data = json_decode($raw, true);
            if (!is_array($data)) {
                continue;
            }
            $out[] = [
                'slug' => $data['slug'] ?? $item,
                'name' => $data['name'] ?? $item,
                'description' => $data['description'] ?? '',
                'version' => $data['version'] ?? '1.0.0',
                'path' => $base,
            ];
        }
        usort($out, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });
        return $out;
    }

    public static function getPackage($slug) {
        $slug = basename((string) $slug);
        foreach (self::listPackages() as $pkg) {
            if ($pkg['slug'] === $slug) {
                return $pkg;
            }
        }
        return null;
    }

    /**
     * Apply flagship package onto modern core tables.
     * @return array{success:bool,message:string,slug?:string}
     */
    public static function apply($slug, $mysqli = null, $authorId = 1) {
        $pkg = self::getPackage($slug);
        if (!$pkg) {
            return ['success' => false, 'message' => 'Flagship package not found: ' . $slug];
        }

        $contentFile = $pkg['path'] . '/content.json';
        if (!is_file($contentFile)) {
            return ['success' => false, 'message' => 'Package missing content.json'];
        }
        $raw = (string) file_get_contents($contentFile);
        if (strncmp($raw, "\xEF\xBB\xBF", 3) === 0) {
            $raw = substr($raw, 3);
        }
        $content = json_decode($raw, true);
        if (!is_array($content)) {
            return ['success' => false, 'message' => 'Invalid content.json'];
        }

        $conn = null;
        if ($mysqli instanceof mysqli) {
            $conn = $mysqli;
        } elseif (function_exists('getDB')) {
            try {
                $conn = getDB()->getConnection();
            } catch (Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }
        if (!$conn instanceof mysqli) {
            return ['success' => false, 'message' => 'No database connection'];
        }

        self::ensurePagesTable($conn);

        $authorId = max(1, (int) $authorId);
        $catMap = [];

        // Categories
        foreach ((array) ($content['categories'] ?? []) as $cat) {
            $name = trim((string) ($cat['name'] ?? ''));
            $cslug = trim((string) ($cat['slug'] ?? ''));
            if ($name === '' || $cslug === '') {
                continue;
            }
            $desc = (string) ($cat['description'] ?? '');
            $existing = null;
            $stmt = $conn->prepare('SELECT categoryid FROM core_categories WHERE slug = ? LIMIT 1');
            if ($stmt) {
                $stmt->bind_param('s', $cslug);
                $stmt->execute();
                $res = $stmt->get_result();
                $existing = $res ? $res->fetch_assoc() : null;
                $stmt->close();
            }
            if ($existing) {
                $catMap[$cslug] = (int) $existing['categoryid'];
            } else {
                $stmt = $conn->prepare('INSERT INTO core_categories (name, slug, description) VALUES (?,?,?)');
                if ($stmt) {
                    $stmt->bind_param('sss', $name, $cslug, $desc);
                    $stmt->execute();
                    $catMap[$cslug] = (int) $stmt->insert_id;
                    $stmt->close();
                }
            }
        }

        // Posts
        $postsAdded = 0;
        foreach ((array) ($content['posts'] ?? []) as $post) {
            $title = trim((string) ($post['title'] ?? ''));
            $pslug = trim((string) ($post['slug'] ?? ''));
            $body = (string) ($post['content'] ?? '');
            if ($title === '' || $pslug === '' || $body === '') {
                continue;
            }
            $check = $conn->prepare('SELECT postid FROM posts WHERE slug = ? LIMIT 1');
            if ($check) {
                $check->bind_param('s', $pslug);
                $check->execute();
                $exists = $check->get_result()->fetch_assoc();
                $check->close();
                if ($exists) {
                    continue;
                }
            }
            $excerpt = (string) ($post['excerpt'] ?? '');
            $status = in_array($post['status'] ?? '', ['published', 'draft', 'private'], true)
                ? $post['status'] : 'published';
            $catSlug = (string) ($post['category'] ?? '');
            $categoryId = $catMap[$catSlug] ?? 0;
            $stmt = $conn->prepare(
                'INSERT INTO posts (title, slug, content, excerpt, author_id, category_id, status, created_at, updated_at)
                 VALUES (?,?,?,?,?,?,?,NOW(),NOW())'
            );
            if ($stmt) {
                $stmt->bind_param('ssssiis', $title, $pslug, $body, $excerpt, $authorId, $categoryId, $status);
                if ($stmt->execute()) {
                    $postsAdded++;
                }
                $stmt->close();
            }
        }

        // Pages
        $pagesAdded = 0;
        foreach ((array) ($content['pages'] ?? []) as $page) {
            $title = trim((string) ($page['title'] ?? ''));
            $pslug = trim((string) ($page['slug'] ?? ''));
            $body = (string) ($page['content'] ?? '');
            if ($title === '' || $pslug === '') {
                continue;
            }
            $check = $conn->prepare('SELECT pageid FROM pages WHERE slug = ? LIMIT 1');
            if ($check) {
                $check->bind_param('s', $pslug);
                $check->execute();
                $exists = $check->get_result()->fetch_assoc();
                $check->close();
                if ($exists) {
                    continue;
                }
            }
            $status = ($page['status'] ?? 'published') === 'draft' ? 'draft' : 'published';
            $stmt = $conn->prepare(
                'INSERT INTO pages (title, slug, content, status, created_at, updated_at) VALUES (?,?,?,?,NOW(),NOW())'
            );
            if ($stmt) {
                $stmt->bind_param('ssss', $title, $pslug, $body, $status);
                if ($stmt->execute()) {
                    $pagesAdded++;
                }
                $stmt->close();
            }
        }

        // Site settings overlay
        $site = (array) ($content['site'] ?? []);
        if ($site) {
            $title = trim((string) ($site['title'] ?? ''));
            $desc = trim((string) ($site['description'] ?? ''));
            if ($title !== '' || $desc !== '') {
                // Update modern + legacy columns when present
                $cols = [];
                $vals = [];
                $types = '';
                $show = $conn->query('SHOW COLUMNS FROM settings');
                $have = [];
                if ($show) {
                    while ($r = $show->fetch_assoc()) {
                        $have[$r['Field']] = true;
                    }
                }
                if ($title !== '' && isset($have['site_title'])) {
                    $cols[] = 'site_title=?';
                    $vals[] = $title;
                    $types .= 's';
                }
                if ($title !== '' && isset($have['title'])) {
                    $cols[] = 'title=?';
                    $vals[] = $title;
                    $types .= 's';
                }
                if ($desc !== '' && isset($have['site_description'])) {
                    $cols[] = 'site_description=?';
                    $vals[] = $desc;
                    $types .= 's';
                }
                if ($desc !== '' && isset($have['metadesc'])) {
                    $cols[] = 'metadesc=?';
                    $vals[] = $desc;
                    $types .= 's';
                }
                if ($cols) {
                    $sql = 'UPDATE settings SET ' . implode(',', $cols) . ' WHERE settingid=1';
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param($types, ...$vals);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }

        // Keep selecttopic on modern default theme
        $default = 'default';
        $stmt = $conn->prepare('UPDATE settings SET selecttopic = ? WHERE settingid = 1');
        if ($stmt) {
            $stmt->bind_param('s', $default);
            $stmt->execute();
            $stmt->close();
        }

        @file_put_contents(dirname(__DIR__) . '/includes/active_site.json', json_encode([
            'slug' => $pkg['slug'],
            'name' => $pkg['name'],
            'mode' => 'flagship',
            'applied_at' => date('c'),
        ], JSON_PRETTY_PRINT));

        if (function_exists('do_action')) {
            do_action('multicms_flagship_applied', $pkg['slug'], $pkg);
        }

        return [
            'success' => true,
            'slug' => $pkg['slug'],
            'message' => sprintf(
                'Applied “%s”: %d posts, %d pages seeded.',
                $pkg['name'],
                $postsAdded,
                $pagesAdded
            ),
        ];
    }

    public static function ensurePagesTable(mysqli $conn) {
        $conn->query("CREATE TABLE IF NOT EXISTS `pages` (
            `pageid` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `content` longtext NOT NULL,
            `status` enum('published','draft') NOT NULL DEFAULT 'published',
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`pageid`),
            UNIQUE KEY `slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }
}
