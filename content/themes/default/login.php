<?php
/**
 * Modern Login Page
 * Uses AuthService and includes CSRF protection
 */

// Generate CSRF token
$csrfToken = Session::generateCsrfToken();

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!Session::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $loginError = 'Invalid request. Please try again.';
    } else {
        // Process login
        $result = $authService->login($_POST['username'] ?? '', $_POST['password'] ?? '');
        
        if ($result['success']) {
            // Login successful, redirect to home page
            safeRedirect('index.php', 'Welcome back! You have been logged in successfully.');
        } else {
            $loginError = $result['message'] ?? 'Login failed. Please try again.';
        }
    }
}
?>

<div class="login-container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">🔐 User Login</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($loginError)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($loginError); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?page=login">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                        
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                   required 
                                   autocomplete="username">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password">
                        </div>
                        
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p>Don't have an account? 
                            <a href="index.php?page=register" class="text-decoration-none">Register here</a>
                        </p>
                        <p>
                            <a href="index.php?page=forgot-password" class="text-decoration-none">Forgot your password?</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.login-container {
    padding: 40px 0;
}

.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 10px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
    border: none;
}

.card-header h3 {
    margin: 0;
    font-weight: 600;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: transform 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.alert {
    border-radius: 8px;
    border: none;
}
</style>
