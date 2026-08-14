<?php
/**
 * Shared legacy authorization helpers (Dreamweaver MM_* compatibility).
 * Fixes the historic always-true gate when $strUsers is empty.
 */

if (!function_exists('isAuthorized')) {
    /**
     * @param string $strUsers  Comma-separated usernames allowed (empty = any authenticated user in allowed groups only)
     * @param string $strGroups Comma-separated groups allowed
     * @param string $UserName  Current username
     * @param string $UserGroup Current group/role
     */
    function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) {
        $isValid = false;

        if (empty($UserName)) {
            return false;
        }

        $arrUsers = array_filter(array_map('trim', explode(',', (string) $strUsers)));
        $arrGroups = array_filter(array_map('trim', explode(',', (string) $strGroups)));

        if (!empty($arrUsers) && in_array($UserName, $arrUsers, true)) {
            $isValid = true;
        }

        // Normalize admin aliases
        $normalizedGroup = (string) $UserGroup;
        $groupAliases = [$normalizedGroup];
        if (in_array($normalizedGroup, ['admin', 'administrator'], true)) {
            $groupAliases = ['admin', 'administrator'];
        }

        foreach ($arrGroups as $allowed) {
            if (in_array($allowed, $groupAliases, true)) {
                $isValid = true;
                break;
            }
            // "member" pages: allow any logged-in user with member/user/admin
            if ($allowed === 'member' && $normalizedGroup !== '') {
                $isValid = true;
                break;
            }
        }

        // If groups were specified but user has no group, deny
        // If only groups specified and empty UserGroup — deny (fixes empty MM_UserGroup bypass)
        if (!$isValid && empty($arrUsers) && !empty($arrGroups) && $normalizedGroup === '') {
            return false;
        }

        return $isValid;
    }
}

if (!function_exists('multicms_csrf_token')) {
    function multicms_csrf_token() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('multicms_csrf_field')) {
    function multicms_csrf_field() {
        $t = htmlspecialchars(multicms_csrf_token(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="csrf_token" value="' . $t . '">';
    }
}

if (!function_exists('multicms_csrf_validate')) {
    function multicms_csrf_validate($token = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $token = $token ?? ($_POST['csrf_token'] ?? '');
        if (empty($_SESSION['csrf_token']) || $token === '') {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('multicms_verify_password_flexible')) {
    /** Verify bcrypt, MD5, or legacy plaintext; returns [ok, needs_rehash] */
    function multicms_verify_password_flexible($password, $stored) {
        if (!is_string($stored) || $stored === '') {
            return [false, false];
        }
        if (strlen($stored) === 32 && ctype_xdigit($stored)) {
            return [hash_equals($stored, md5($password)), true];
        }
        if (strpos($stored, '$2y$') === 0 || strpos($stored, '$2a$') === 0 || strpos($stored, '$argon') === 0) {
            $ok = password_verify($password, $stored);
            return [$ok, $ok && password_needs_rehash($stored, PASSWORD_DEFAULT)];
        }
        if (strlen($stored) < 60 && strpos($stored, '$') !== 0) {
            return [hash_equals($stored, $password), true];
        }
        return [false, false];
    }
}

if (!function_exists('multicms_h')) {
    function multicms_h($value) {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('multicms_plain')) {
    /**
     * Strip tags then escape — for legacy description fields that may contain HTML.
     */
    function multicms_plain($value, $maxLen = 0) {
        $text = trim(html_entity_decode(strip_tags((string) $value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($maxLen > 0 && function_exists('mb_substr')) {
            if (mb_strlen($text) > $maxLen) {
                $text = mb_substr($text, 0, $maxLen) . '…';
            }
        } elseif ($maxLen > 0 && strlen($text) > $maxLen) {
            $text = substr($text, 0, $maxLen) . '...';
        }
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('multicms_require_csrf_post')) {
    function multicms_require_csrf_post() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }
        if (!multicms_csrf_validate($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            echo 'Invalid security token.';
            exit;
        }
        return true;
    }
}

if (!function_exists('multicms_safe_upload')) {
    /**
     * Store an uploaded image under $destDir with a random name.
     * @return array{success:bool,filename?:string,message?:string}
     */
    function multicms_safe_upload($fileField, $destDir, $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp']) {
        if (empty($_FILES[$fileField]) || !is_array($_FILES[$fileField])) {
            return ['success' => false, 'message' => 'No file uploaded'];
        }
        $f = $_FILES[$fileField];
        if (($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Upload error'];
        }
        $name = (string) ($f['name'] ?? '');
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $blocked = ['php', 'phtml', 'phar', 'php3', 'php4', 'php5', 'php7', 'php8', 'cgi', 'exe', 'js', 'html', 'htm', 'shtml'];
        if ($ext === '' || in_array($ext, $blocked, true) || !in_array($ext, $allowed, true)) {
            return ['success' => false, 'message' => 'File type not allowed'];
        }
        // Reject double extensions like file.php.jpg partially by checking basename for .php
        if (preg_match('/\.(php|phtml|phar)(\.|$)/i', $name)) {
            return ['success' => false, 'message' => 'File type not allowed'];
        }
        if (!is_dir($destDir)) {
            @mkdir($destDir, 0755, true);
        }
        $safe = bin2hex(random_bytes(16)) . '.' . $ext;
        $dest = rtrim($destDir, '/\\') . DIRECTORY_SEPARATOR . $safe;
        if (!move_uploaded_file($f['tmp_name'], $dest)) {
            return ['success' => false, 'message' => 'Failed to store upload'];
        }
        @chmod($dest, 0644);
        return ['success' => true, 'filename' => $safe];
    }
}
