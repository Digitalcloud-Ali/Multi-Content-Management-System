<?php
/**
 * Optional Blog pack hooks (Phase 3–4).
 */
add_action('multicms_site_applied', function ($slug, $site) {
    if ($slug !== 'blog') {
        return;
    }
    try {
        $conn = null;
        if (function_exists('getDB')) {
            $conn = getDB()->getConnection();
        }
        if (!$conn instanceof mysqli) {
            return;
        }

        // Ensure a blog category exists
        $check = $conn->query("SHOW TABLES LIKE 'categories'");
        if ($check && $check->num_rows > 0) {
            $exists = $conn->query("SELECT cateid FROM categories WHERE selecttopic='blog' LIMIT 1");
            if (!$exists || $exists->num_rows === 0) {
                $conn->query("INSERT INTO categories (catename, selecttopic) VALUES ('General', 'blog')");
            }
        }

        // Seed one sample blog post so the pack home is not empty
        $blogTable = $conn->query("SHOW TABLES LIKE 'blog'");
        if ($blogTable && $blogTable->num_rows > 0) {
            $count = $conn->query('SELECT COUNT(*) AS c FROM blog');
            $n = $count ? (int) ($count->fetch_assoc()['c'] ?? 0) : 0;
            if ($n === 0) {
                $cols = [];
                $cr = $conn->query('SHOW COLUMNS FROM blog');
                while ($cr && ($row = $cr->fetch_assoc())) {
                    $cols[] = $row['Field'];
                }
                $title = 'Welcome to MultiCMS Blog';
                $desc = 'This is a sample post created when the Blog ready-made site was applied. Edit or delete it from the pack UI or admin.';
                $cat = 'General';
                $status = 'published';
                $users = 'admin';
                $dates = date('Y-m-d');
                // Build insert from known columns
                $fields = [];
                $values = [];
                $types = '';
                $map = [
                    'title' => $title,
                    'description' => $desc,
                    'photo' => '',
                    'dates' => $dates,
                    'metadesc' => 'Sample MultiCMS blog post',
                    'metakey' => 'multicms, blog, sample',
                    'catename' => $cat,
                    'status' => $status,
                    'position' => '1',
                    'users' => $users,
                    'rating' => 0,
                    'views' => 0,
                ];
                foreach ($map as $col => $val) {
                    if (in_array($col, $cols, true)) {
                        $fields[] = '`' . $col . '`';
                        $values[] = $val;
                        $types .= is_int($val) ? 'i' : 's';
                    }
                }
                if ($fields) {
                    $sql = 'INSERT INTO blog (' . implode(',', $fields) . ') VALUES (' . implode(',', array_fill(0, count($fields), '?')) . ')';
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param($types, ...$values);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }
    } catch (Throwable $e) {
        // non-fatal
    }
}, 10);
