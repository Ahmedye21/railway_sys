<?php
// controllers/auth/AuthController.php


require_once MODELS_PATH . '/user/User.php';


class AuthController {
    private $user;
    
    public function __construct() {
        // No need to start session as it's already started in index.php
        $this->user = new User();
    }
    
    public function login() {
        if(isset($_SESSION['user_id'])) {
            $this->redirectBasedOnUserType($_SESSION['role']);
            exit;
        }
        
        $error_msg = "";
        $success_msg = "";
        
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];
            
            if($this->user->authenticate()) {
                $_SESSION['user_id']    = $this->user->id;
                $_SESSION['name']       = $this->user->name;
                $_SESSION['role']  = $this->user->role;
                $_SESSION['balance']    = $this->user->balance;
                
                $this->redirectBasedOnUserType($this->user->role);
                exit;
            } else {
                $error_msg = "Invalid email or password.";
            }
        }
        
        // Load the view
        require_once VIEWS_PATH . '/auth/login.php';
    }
    
    public function signup() {
        if(isset($_SESSION['user_id'])) {
            $this->redirectBasedOnUserType($_SESSION['role']);
            exit;
        }
        
        $error_msg = "";
        $success_msg = "";
        
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $this->user->name = trim($_POST['name']);
                $this->user->email = trim($_POST['email']);
                $this->user->password = $_POST['password'];
                $this->user->role = 'user';
                
                // Validation
                if(empty($this->user->name)) {
                    $error_msg = "Name is required.";
                } elseif(!filter_var($this->user->email, FILTER_VALIDATE_EMAIL)) {
                    $error_msg = "Invalid email format.";
                } elseif($_POST['password'] !== $_POST['confirm_password']) {
                    $error_msg = "Passwords do not match.";
                } elseif(strlen($_POST['password']) < 6) {
                    $error_msg = "Password must be at least 6 characters.";
                } else {
                    if($this->user->signup()) {
                        $_SESSION['user_id'] = $this->user->id;
                        $_SESSION['name'] = $this->user->name;
                        $_SESSION['role'] = $this->user->role;
                        $_SESSION['balance'] = $this->user->balance;
                        
                        $this->redirectBasedOnUserType($this->user->role);
                        exit;
                    } else {
                        $error_msg = "Registration failed. Please try again.";
                    }
                }
            } catch (PDOException $e) {
                $error_msg = "Database Error: " . $e->getMessage();
                error_log("Signup Error: " . $e->getMessage());
            }
        }
        
        require_once VIEWS_PATH . '/auth/signup.php';
    }

    
    public function logout() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to home page
        header("Location: " . BASE_URL);
        exit();
    }
    
    private function redirectBasedOnUserType($role) {
        if ($role == 'admin') {
            header("Location: index.php?action=admin_dashboard");
        } 
        else {
            header("Location: index.php");
        }
        exit;
    }
}
?>