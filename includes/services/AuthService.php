<?php
/**
 * Modern Authentication Service — uses install.php `users` table as source of truth.
 */

class AuthService {
    private $db;
    private $validator;
    
    public function __construct() {
        $this->db = getDB();
        $this->validator = getValidator();
    }
    
    public function login($username, $password) {
        try {
            $rules = [
                'username' => 'required|min:3|max:50',
                'password' => 'required|min:6'
            ];
            
            if (!$this->validator->validate(['username' => $username, 'password' => $password], $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            $username = Validator::sanitize($username);
            
            $user = $this->db->queryOne(
                "SELECT * FROM users WHERE username = ? AND status = 'active'",
                's',
                [$username]
            );
            
            if (!$user) {
                return ['success' => false, 'message' => 'Invalid username or password'];
            }
            
            if (!$this->verifyPassword($password, $user['password'])) {
                return ['success' => false, 'message' => 'Invalid username or password'];
            }

            // Upgrade legacy MD5 hashes on successful login
            if ($this->isLegacyMd5Hash($user['password'])) {
                $this->upgradePasswordHash((int) $user['userid'], $password);
            }
            
            $role = $user['role'] ?? 'user';
            
            Session::set('MM_Username', $user['username']);
            Session::set('MM_UserGroup', $role);
            Session::set('MM_UserID', $user['userid']);
            Session::set('MM_Email', $user['email']);
            Session::set('login_time', time());
            
            logError("User {$username} logged in successfully", 'INFO');
            
            return ['success' => true, 'user' => $user];
            
        } catch (Exception $e) {
            logError("Login error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Login failed. Please try again.'];
        }
    }
    
    public function register($userData) {
        try {
            $rules = [
                'username' => 'required|min:3|max:50|alphanumeric',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'confirm_password' => 'required'
            ];
            
            if (!$this->validator->validate($userData, $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            if ($userData['password'] !== $userData['confirm_password']) {
                return ['success' => false, 'message' => 'Passwords do not match'];
            }
            
            $existingUser = $this->db->queryOne("SELECT userid FROM users WHERE username = ?", 's', [$userData['username']]);
            if ($existingUser) {
                return ['success' => false, 'message' => 'Username already exists'];
            }
            
            $existingEmail = $this->db->queryOne("SELECT userid FROM users WHERE email = ?", 's', [$userData['email']]);
            if ($existingEmail) {
                return ['success' => false, 'message' => 'Email already exists'];
            }
            
            // Role is never taken from client input
            $hashedPassword = $this->hashPassword($userData['password']);
            
            $result = $this->db->execute(
                "INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, 'user', 'active')",
                'sss',
                [
                    $userData['username'],
                    $userData['email'],
                    $hashedPassword
                ]
            );
            
            if ($result['affected_rows'] > 0) {
                logError("New user registered: {$userData['username']}", 'INFO');
                return ['success' => true, 'message' => 'Registration successful'];
            }
            return ['success' => false, 'message' => 'Registration failed'];
            
        } catch (Exception $e) {
            logError("Registration error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }
    }
    
    public function logout() {
        $username = Session::get('MM_Username');
        Session::clear();
        logError("User {$username} logged out", 'INFO');
        return true;
    }
    
    public function isAuthenticated() {
        return Session::isLoggedIn();
    }
    
    public function hasRole($role) {
        return Session::hasRole($role);
    }
    
    public function requireAuth() {
        Session::requireAuth();
    }
    
    public function requireRole($role) {
        Session::requireRole($role);
    }
    
    public function getCurrentUser() {
        if (!$this->isAuthenticated()) {
            return null;
        }
        
        $userId = Session::get('MM_UserID');
        return $this->db->queryOne("SELECT * FROM users WHERE userid = ?", 'i', [$userId]);
    }
    
    public function updateProfile($userId, $data) {
        try {
            $rules = [
                'email' => 'email'
            ];
            
            if (!$this->validator->validate($data, $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            $allowed = ['email'];
            $updateFields = [];
            $types = '';
            $values = [];
            
            foreach ($data as $field => $value) {
                if ($value === '' || $value === null) {
                    continue;
                }
                if (!in_array($field, $allowed, true)) {
                    continue;
                }
                $updateFields[] = "{$field} = ?";
                $types .= 's';
                $values[] = $value;
            }
            
            if (empty($updateFields)) {
                return ['success' => false, 'message' => 'No data to update'];
            }
            
            $values[] = $userId;
            $types .= 'i';
            
            $result = $this->db->execute(
                "UPDATE users SET " . implode(', ', $updateFields) . " WHERE userid = ?",
                $types,
                $values
            );
            
            if ($result['affected_rows'] > 0) {
                return ['success' => true, 'message' => 'Profile updated successfully'];
            }
            return ['success' => false, 'message' => 'No changes made'];
            
        } catch (Exception $e) {
            logError("Profile update error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Profile update failed'];
        }
    }
    
    public function changePassword($userId, $currentPassword, $newPassword) {
        try {
            $rules = [
                'current_password' => 'required|min:6',
                'new_password' => 'required|min:6'
            ];
            
            if (!$this->validator->validate([
                'current_password' => $currentPassword,
                'new_password' => $newPassword
            ], $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            $user = $this->db->queryOne("SELECT password FROM users WHERE userid = ?", 'i', [$userId]);
            if (!$user) {
                return ['success' => false, 'message' => 'User not found'];
            }
            
            if (!$this->verifyPassword($currentPassword, $user['password'])) {
                return ['success' => false, 'message' => 'Current password is incorrect'];
            }
            
            $hashedPassword = $this->hashPassword($newPassword);
            $result = $this->db->execute(
                "UPDATE users SET password = ? WHERE userid = ?",
                'si',
                [$hashedPassword, $userId]
            );
            
            if ($result['affected_rows'] > 0) {
                return ['success' => true, 'message' => 'Password changed successfully'];
            }
            return ['success' => false, 'message' => 'Password change failed'];
            
        } catch (Exception $e) {
            logError("Password change error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Password change failed'];
        }
    }
    
    private function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private function isLegacyMd5Hash($hash) {
        return is_string($hash) && strlen($hash) === 32 && ctype_xdigit($hash);
    }
    
    private function verifyPassword($password, $hash) {
        if ($this->isLegacyMd5Hash($hash)) {
            return md5($password) === $hash;
        }
        // Plaintext legacy (quarantine packs / old dumps) — exact match only if not a bcrypt hash
        if (is_string($hash) && $hash !== '' && strpos($hash, '$') !== 0 && strlen($hash) < 60) {
            return hash_equals($hash, $password);
        }
        return password_verify($password, $hash);
    }
    
    public function upgradePasswordHash($userId, $password) {
        try {
            $hashedPassword = $this->hashPassword($password);
            $result = $this->db->execute(
                "UPDATE users SET password = ? WHERE userid = ?",
                'si',
                [$hashedPassword, $userId]
            );
            return $result['affected_rows'] > 0;
        } catch (Exception $e) {
            logError("Password hash upgrade error: " . $e->getMessage(), 'ERROR');
            return false;
        }
    }
}
