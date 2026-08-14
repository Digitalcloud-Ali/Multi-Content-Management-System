<?php
/**
 * Modern admin login.
 */
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/LegacyAuth.php';

Session::start();

if (isset($_GET['doLogout'])) {
    $_SESSION = [];
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    header('Location: login.php');
    exit;
}

// Already signed in as admin → dashboard
$isAdmin = (function_exists('hasRole') && (hasRole('admin') || hasRole('administrator')))
    || (!empty($_SESSION['MM_UserGroup']) && in_array($_SESSION['MM_UserGroup'], ['admin', 'administrator'], true));
if ($isAdmin && !empty($_SESSION['MM_Username'])) {
    header('Location: dashboard.php');
    exit;
}

$siteTitle = 'MultiCMS';
try {
    $row = getDB()->queryOne('SELECT site_title, title FROM settings WHERE settingid = 1');
    if ($row) {
        $siteTitle = $row['site_title'] ?? ($row['title'] ?? $siteTitle);
    }
} catch (Throwable $e) {
}

$loginError = '';
if (!empty($_GET['status']) && $_GET['status'] === 'fail') {
    $loginError = 'Invalid username or password.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['datauser'])) {
    if (!multicms_csrf_validate($_POST['csrf_token'] ?? '')) {
        $loginError = 'Invalid security token. Please try again.';
    } else {
        $loginUsername = trim((string) $_POST['datauser']);
        $password = (string) $_POST['datapass'];
        $ok = false;
        $group = '';

        $user = getDB()->queryOne(
            "SELECT userid, username, password, role, status FROM users WHERE username = ? LIMIT 1",
            's',
            [$loginUsername]
        );

        if ($user && ($user['status'] ?? '') === 'active') {
            list($ok, $rehash) = multicms_verify_password_flexible($password, $user['password']);
            if ($ok) {
                $group = $user['role'] ?: 'admin';
                if ($rehash) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    getDB()->execute('UPDATE users SET password = ? WHERE userid = ?', 'si', [$newHash, (int) $user['userid']]);
                }
            }
        } else {
            // Optional legacy members table
            try {
                $legacy = getDB()->queryOne(
                    'SELECT memberid, users, passs, level FROM members WHERE users = ? LIMIT 1',
                    's',
                    [$loginUsername]
                );
                if ($legacy) {
                    list($ok, $rehash) = multicms_verify_password_flexible($password, $legacy['passs']);
                    if ($ok) {
                        $group = $legacy['level'] ?: 'administrator';
                        if ($rehash) {
                            $newHash = password_hash($password, PASSWORD_DEFAULT);
                            getDB()->execute('UPDATE members SET passs = ? WHERE users = ?', 'ss', [$newHash, $loginUsername]);
                        }
                    }
                }
            } catch (Throwable $e) {
                // members table may not exist on Fresh installs
            }
        }

        if ($ok) {
            session_regenerate_id(true);
            $_SESSION['MM_Username'] = $loginUsername;
            $_SESSION['MM_UserGroup'] = $group;
            header('Location: dashboard.php');
            exit;
        }
        header('Location: login.php?status=fail');
        exit;
    }
}

$logoUrl = '../content/assets/logo-normal.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin login — <?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:420px;">
    <div class="text-center mb-4">
        <?php if (is_file(__DIR__ . '/../content/assets/logo-normal.png')): ?>
            <img src="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="" height="64" class="mb-3">
        <?php endif; ?>
        <h1 class="h4 mb-1">MultiCMS Admin</h1>
        <p class="text-muted small mb-0"><?php echo htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <?php if ($loginError): ?>
                <div class="alert alert-danger py-2"><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            <form method="post" action="login.php" autocomplete="on">
                <?php echo multicms_csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label" for="datauser">Username</label>
                    <input class="form-control" type="text" name="datauser" id="datauser" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="datapass">Password</label>
                    <input class="form-control" type="password" name="datapass" id="datapass" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Log in</button>
            </form>
        </div>
    </div>

    <p class="text-center small text-muted mt-3 mb-0">
        <a href="../index.php">← Back to site</a>
        · Developed by <a href="https://digitalcloud.no" target="_blank" rel="noopener">DigitalCloud.no</a>
    </p>
</div>
</body>
</html>
