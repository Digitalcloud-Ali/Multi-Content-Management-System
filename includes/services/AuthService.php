<?php
/**
 * Modern Authentication Service
 * Handles user authentication, registration, and security
 */

class AuthService {
    private $db;
    private $validator;
    
    public function __construct() {
        $this->db = getDB();
        $this->validator = getValidator();
    }
    
    /**
     * Authenticate user login
     */
    public function login($username, $password) {
        try {
            // Validate input
            $rules = [
                'username' => 'required|min:3|max:50',
                'password' => 'required|min:6'
            ];
            
            if (!$this->validator->validate(['username' => $username, 'password' => $password], $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            // Sanitize input
            $username = Validator::sanitize($username);
            
            // Query user from database
            $query = "SELECT * FROM members WHERE username = ? AND status = 'active'";
            $user = $this->db->queryOne($query, 's', [$username]);
            
            if (!$user) {
                return ['success' => false, 'message' => 'Invalid username or password'];
            }
            
            // Verify password (assuming passwords are hashed)
            if (!$this->verifyPassword($password, $user['password'])) {
                return ['success' => false, 'message' => 'Invalid username or password'];
            }
            
            // Set session data
            Session::set('MM_Username', $user['username']);
            Session::set('MM_UserGroup', $user['usergroup'] ?? 'user');
            Session::set('MM_UserID', $user['id']);
            Session::set('MM_Email', $user['email']);
            Session::set('login_time', time());
            
            // Log successful login
            logError("User {$username} logged in successfully", 'INFO');
            
            return ['success' => true, 'user' => $user];
            
        } catch (Exception $e) {
            logError("Login error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Login failed. Please try again.'];
        }
    }
    
    /**
     * Register new user
     */
    public function register($userData) {
        try {
            // Validate input
            $rules = [
                'username' => 'required|min:3|max:50|alphanumeric',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'confirm_password' => 'required'
            ];
            
            if (!$this->validator->validate($userData, $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            // Check if passwords match
            if ($userData['password'] !== $userData['confirm_password']) {
                return ['success' => false, 'message' => 'Passwords do not match'];
            }
            
            // Check if username already exists
            $existingUser = $this->db->queryOne("SELECT id FROM members WHERE username = ?", 's', [$userData['username']]);
            if ($existingUser) {
                return ['success' => false, 'message' => 'Username already exists'];
            }
            
            // Check if email already exists
            $existingEmail = $this->db->queryOne("SELECT id FROM members WHERE email = ?", 's', [$userData['email']]);
            if ($existingEmail) {
                return ['success' => false, 'message' => 'Email already exists'];
            }
            
            // Hash password
            $hashedPassword = $this->hashPassword($userData['password']);
            
            // Insert new user
            $query = "INSERT INTO members (username, email, password, usergroup, status, created_at) VALUES (?, ?, ?, 'user', 'active', NOW())";
            $result = $this->db->execute($query, 'sss', [
                $userData['username'],
                $userData['email'],
                $hashedPassword
            ]);
            
            if ($result['affected_rows'] > 0) {
                logError("New user registered: {$userData['username']}", 'INFO');
                return ['success' => true, 'message' => 'Registration successful'];
            } else {
                return ['success' => false, 'message' => 'Registration failed'];
            }
            
        } catch (Exception $e) {
            logError("Registration error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        $username = Session::get('MM_Username');
        Session::clear();
        logError("User {$username} logged out", 'INFO');
        return true;
    }
    
    /**
     * Check if user is authenticated
     */
    public function isAuthenticated() {
        return Session::isLoggedIn();
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole($role) {
        return Session::hasRole($role);
    }
    
    /**
     * Require authentication (redirects if not authenticated)
     */
    public function requireAuth() {
        Session::requireAuth();
    }
    
    /**
     * Require specific role (redirects if not authorized)
     */
    public function requireRole($role) {
        Session::requireRole($role);
    }
    
    /**
     * Get current user data
     */
    public function getCurrentUser() {
        if (!$this->isAuthenticated()) {
            return null;
        }
        
        $userId = Session::get('MM_UserID');
        return $this->db->queryOne("SELECT * FROM members WHERE id = ?", 'i', [$userId]);
    }
    
    /**
     * Update user profile
     */
    public function updateProfile($userId, $data) {
        try {
            // Validate input
            $rules = [
                'email' => 'email',
                'firstname' => 'max:50',
                'lastname' => 'max:50'
            ];
            
            if (!$this->validator->validate($data, $rules)) {
                return ['success' => false, 'errors' => $this->validator->getErrors()];
            }
            
            // Build update query dynamically
            $updateFields = [];
            $types = '';
            $values = [];
            
            foreach ($data as $field => $value) {
                if (!empty($value)) {
                    $updateFields[] = "{$field} = ?";
                    $types .= 's';
                    $values[] = $value;
                }
            }
            
            if (empty($updateFields)) {
                return ['success' => false, 'message' => 'No data to update'];
            }
            
            $values[] = $userId; // For WHERE clause
            $types .= 'i';
            
            $query = "UPDATE members SET " . implode(', ', $updateFields) . " WHERE id = ?";
            $result = $this->db->execute($query, $types, $values);
            
            if ($result['affected_rows'] > 0) {
                logError("Profile updated for user ID: {$userId}", 'INFO');
                return ['success' => true, 'message' => 'Profile updated successfully'];
            } else {
                return ['success' => false, 'message' => 'No changes made'];
            }
            
        } catch (Exception $e) {
            logError("Profile update error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Profile update failed'];
        }
    }
    
    /**
     * Change password
     */
    public function changePassword($userId, $currentPassword, $newPassword) {
        try {
            // Validate input
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
            
            // Get current user
            $user = $this->db->queryOne("SELECT password FROM members WHERE id = ?", 'i', [$userId]);
            if (!$user) {
                return ['success' => false, 'message' => 'User not found'];
            }
            
            // Verify current password
            if (!$this->verifyPassword($currentPassword, $user['password'])) {
                return ['success' => false, 'message' => 'Current password is incorrect'];
            }
            
            // Hash new password
            $hashedPassword = $this->hashPassword($newPassword);
            
            // Update password
            $query = "UPDATE members SET password = ? WHERE id = ?";
            $result = $this->db->execute($query, 'si', [$hashedPassword, $userId]);
            
            if ($result['affected_rows'] > 0) {
                logError("Password changed for user ID: {$userId}", 'INFO');
                return ['success' => true, 'message' => 'Password changed successfully'];
            } else {
                return ['success' => false, 'message' => 'Password change failed'];
            }
            
        } catch (Exception $e) {
            logError("Password change error: " . $e->getMessage(), 'ERROR');
            return ['success' => false, 'message' => 'Password change failed'];
        }
    }
    
    /**
     * Hash password using modern methods
     */
    private function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verify password
     */
    private function verifyPassword($password, $hash) {
        // If hash looks like old MD5, verify it and then upgrade
        if (strlen($hash) === 32 && ctype_xdigit($hash)) {
            if (md5($password) === $hash) {
                // Password is correct, but we should upgrade to modern hashing
                // This will be done on next login
                return true;
            }
            return false;
        }
        
        return password_verify($password, $hash);
    }
    
    /**
     * Upgrade old password hash to modern hash
     */
    public function upgradePasswordHash($userId, $password) {
        try {
            $hashedPassword = $this->hashPassword($password);
            $query = "UPDATE members SET password = ? WHERE id = ?";
            $result = $this->db->execute($query, 'si', [$hashedPassword, $userId]);
            
            if ($result['affected_rows'] > 0) {
                logError("Password hash upgraded for user ID: {$userId}", 'INFO');
                return true;
            }
            return false;
        } catch (Exception $e) {
            logError("Password hash upgrade error: " . $e->getMessage(), 'ERROR');
            return false;
        }
    }
}
?>
