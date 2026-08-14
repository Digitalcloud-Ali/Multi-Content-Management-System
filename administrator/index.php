<?php require_once('../includes/rayicecms.php'); ?>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { multicms_require_csrf_post(); } ?>

<?php
/**
 * Admin entry — Fresh core uses dashboard; legacy pack schema uses classic index.
 */
if (!isset($_SESSION)) {
    session_start();
}
$MM_authorizedUsers = "administrator,admin";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
    $MM_referrer = "?accesscheck=" . urlencode($_SERVER['PHP_SELF'] ?? 'index.php');
    header("Location: login.php" . $MM_referrer);
    exit;
}

try {
    $conn = dbconnect();
    $topic = 'default';
    $rs = @mysqli_query($conn, "SELECT selecttopic FROM settings WHERE settingid = 1 LIMIT 1");
    if ($rs && ($topicRow = mysqli_fetch_assoc($rs))) {
        $topic = $topicRow['selecttopic'] ?? 'default';
    }
    $hasComments = @mysqli_query($conn, "SHOW TABLES LIKE 'comments'");
    $hasMembers = @mysqli_query($conn, "SHOW TABLES LIKE 'members'");
    $legacyOk = $hasComments && mysqli_num_rows($hasComments) > 0
        && $hasMembers && mysqli_num_rows($hasMembers) > 0;

    if ($topic === 'default' || !$legacyOk || isset($_GET['core'])) {
        header('Location: dashboard.php');
        exit;
    }
    if (is_file(__DIR__ . '/index_legacy.php')) {
        require __DIR__ . '/index_legacy.php';
        exit;
    }
} catch (Throwable $e) {
    header('Location: dashboard.php');
    exit;
}
header('Location: dashboard.php');
exit;
