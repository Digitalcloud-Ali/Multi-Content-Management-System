<?php
/**
 * Modern Registration Page
 * Uses AuthService and includes CSRF protection and validation
 */

// Generate CSRF token
$csrfToken = Session::generateCsrfToken();

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!Session::validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $registerError = 'Invalid request. Please try again.';
    } else {
        // Process registration
        $userData = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'firstname' => $_POST['firstname'] ?? '',
            'lastname' => $_POST['lastname'] ?? ''
        ];
        
        $result = $authService->register($userData);
        
        if ($result['success']) {
            // Registration successful, redirect to login
            safeRedirect('index.php?page=login', 'Registration successful! Please login with your new account.');
        } else {
            $registerError = $result['message'] ?? 'Registration failed. Please try again.';
            $validationErrors = $result['errors'] ?? [];
        }
    }
}
?>

<div class="register-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">📝 Create New Account</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($registerError)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($registerError); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?page=register" id="registerForm">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="firstname">First Name</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="firstname" 
                                           name="firstname" 
                                           value="<?php echo htmlspecialchars($_POST['firstname'] ?? ''); ?>"
                                           required>
                                    <?php if (isset($validationErrors['firstname'])): ?>
                                        <div class="text-danger small"><?php echo htmlspecialchars($validationErrors['firstname'][0]); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="lastname" 
                                           name="lastname" 
                                           value="<?php echo htmlspecialchars($_POST['lastname'] ?? ''); ?>"
                                           required>
                                    <?php if (isset($validationErrors['lastname'])): ?>
                                        <div class="text-danger small"><?php echo htmlspecialchars($validationErrors['lastname'][0]); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                   required 
                                   autocomplete="username">
                            <div class="form-text">Username must be 3-50 characters long and contain only letters and numbers.</div>
                            <?php if (isset($validationErrors['username'])): ?>
                                <div class="text-danger small"><?php echo htmlspecialchars($validationErrors['username'][0]); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="email">Email Address</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   required 
                                   autocomplete="email">
                            <?php if (isset($validationErrors['email'])): ?>
                                <div class="text-danger small"><?php echo htmlspecialchars($validationErrors['email'][0]); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           required 
                                           autocomplete="new-password">
                                    <div class="form-text">Password must be at least 6 characters long.</div>
                                    <?php if (isset($validationErrors['password'])): ?>
                                        <div class="text-danger small"><?php echo htmlspecialchars($validationErrors['password'][0]); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           required 
                                           autocomplete="new-password">
                                    <?php if (isset($validationErrors['confirm_password'])): ?>
                                        <div class="text-danger small"><?php echo htmlspecialchars($validationErrors['confirm_password'][0]); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-user-plus"></i> Create Account
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p>Already have an account? 
                            <a href="index.php?page=login" class="text-decoration-none">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.register-container {
    padding: 40px 0;
}

.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 10px;
}

.card-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: transform 0.2s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.alert {
    border-radius: 8px;
    border: none;
}

.text-danger {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}
</style>

<script>
// Client-side password confirmation validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long!');
        return false;
    }
    
    return true;
});

// Real-time password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strength = getPasswordStrength(password);
    
    // Remove existing strength classes
    this.classList.remove('border-danger', 'border-warning', 'border-success');
    
    // Add appropriate strength class
    if (strength === 'weak') {
        this.classList.add('border-danger');
    } else if (strength === 'medium') {
        this.classList.add('border-warning');
    } else if (strength === 'strong') {
        this.classList.add('border-success');
    }
});

function getPasswordStrength(password) {
    let score = 0;
    
    if (password.length >= 6) score++;
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    
    if (score <= 2) return 'weak';
    if (score <= 4) return 'medium';
    return 'strong';
}
</script>
