<?php
/**
 * Modern Input Validation Class
 * Comprehensive validation and sanitization
 */

class Validator {
    private $errors = [];
    private $data = [];
    
    /**
     * Validate and sanitize input data
     */
    public function validate($data, $rules) {
        $this->errors = [];
        $this->data = $data;
        
        foreach ($rules as $field => $fieldRules) {
            $this->validateField($field, $fieldRules);
        }
        
        return empty($this->errors);
    }
    
    /**
     * Validate a single field
     */
    private function validateField($field, $rules) {
        $value = $this->data[$field] ?? null;
        $rules = explode('|', $rules);
        
        foreach ($rules as $rule) {
            $ruleParts = explode(':', $rule);
            $ruleName = $ruleParts[0];
            $ruleValue = $ruleParts[1] ?? null;
            
            if (!$this->applyRule($field, $value, $ruleName, $ruleValue)) {
                break; // Stop validation for this field on first error
            }
        }
    }
    
    /**
     * Apply a validation rule
     */
    private function applyRule($field, $value, $ruleName, $ruleValue) {
        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, "The {$field} field is required.");
                    return false;
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "The {$field} must be a valid email address.");
                    return false;
                }
                break;
                
            case 'min':
                if (!empty($value) && strlen($value) < $ruleValue) {
                    $this->addError($field, "The {$field} must be at least {$ruleValue} characters.");
                    return false;
                }
                break;
                
            case 'max':
                if (!empty($value) && strlen($value) > $ruleValue) {
                    $this->addError($field, "The {$field} must not exceed {$ruleValue} characters.");
                    return false;
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "The {$field} must be a number.");
                    return false;
                }
                break;
                
            case 'integer':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, "The {$field} must be an integer.");
                    return false;
                }
                break;
                
            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, "The {$field} must be a valid URL.");
                    return false;
                }
                break;
                
            case 'alpha':
                if (!empty($value) && !ctype_alpha($value)) {
                    $this->addError($field, "The {$field} may only contain letters.");
                    return false;
                }
                break;
                
            case 'alphanumeric':
                if (!empty($value) && !ctype_alnum($value)) {
                    $this->addError($field, "The {$field} may only contain letters and numbers.");
                    return false;
                }
                break;
                
            case 'in':
                $allowedValues = explode(',', $ruleValue);
                if (!empty($value) && !in_array($value, $allowedValues)) {
                    $this->addError($field, "The {$field} must be one of: " . implode(', ', $allowedValues));
                    return false;
                }
                break;
                
            case 'regex':
                if (!empty($value) && !preg_match($ruleValue, $value)) {
                    $this->addError($field, "The {$field} format is invalid.");
                    return false;
                }
                break;
        }
        
        return true;
    }
    
    /**
     * Add validation error
     */
    private function addError($field, $message) {
        $this->errors[$field][] = $message;
    }
    
    /**
     * Get all validation errors
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get errors for a specific field
     */
    public function getFieldErrors($field) {
        return $this->errors[$field] ?? [];
    }
    
    /**
     * Check if field has errors
     */
    public function hasErrors($field = null) {
        if ($field === null) {
            return !empty($this->errors);
        }
        return isset($this->errors[$field]);
    }
    
    /**
     * Get first error for a field
     */
    public function getFirstError($field) {
        $errors = $this->getFieldErrors($field);
        return !empty($errors) ? $errors[0] : '';
    }
    
    /**
     * Sanitize input data
     */
    public static function sanitize($input, $type = 'string') {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        
        switch ($type) {
            case 'string':
                $input = trim($input);
                $input = stripslashes($input);
                $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
                break;
                
            case 'email':
                $input = filter_var($input, FILTER_SANITIZE_EMAIL);
                break;
                
            case 'url':
                $input = filter_var($input, FILTER_SANITIZE_URL);
                break;
                
            case 'int':
                $input = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
                break;
                
            case 'float':
                $input = filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                break;
                
            case 'html':
                $input = strip_tags($input);
                break;
        }
        
        return $input;
    }
    
    /**
     * Validate file upload
     */
    public static function validateFile($file, $allowedTypes = [], $maxSize = 5242880) {
        $errors = [];
        
        if (!isset($file['error']) || is_array($file['error'])) {
            $errors[] = 'Invalid file parameter.';
            return $errors;
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $errors[] = 'The uploaded file exceeds the upload_max_filesize directive.';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = 'The uploaded file exceeds the MAX_FILE_SIZE directive.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors[] = 'The uploaded file was only partially uploaded.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errors[] = 'No file was uploaded.';
                    break;
                default:
                    $errors[] = 'Unknown upload error.';
            }
            return $errors;
        }
        
        if ($file['size'] > $maxSize) {
            $errors[] = 'The uploaded file is too large.';
        }
        
        if (!empty($allowedTypes)) {
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedTypes)) {
                $errors[] = 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes);
            }
        }
        
        return $errors;
    }
    
    /**
     * Generate CSRF token for forms
     */
    public static function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
?>
