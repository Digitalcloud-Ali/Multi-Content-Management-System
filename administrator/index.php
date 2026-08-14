<?php require_once('../includes/rayicecms.php'); ?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
$MM_authorizedUsers = "administrator,admin";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
    header("Location: login.php?accesscheck=" . urlencode($_SERVER['PHP_SELF'] ?? 'index.php'));
    exit;
}
header('Location: dashboard.php');
exit;
