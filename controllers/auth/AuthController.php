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
            $this->redirectBasedOnUserType($_SESSION['user_type']);
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
                $_SESSION['user_type']  = $this->user->user_type;
                $_SESSION['balance']    = $this->user->balance;
                
                $this->redirectBasedOnUserType($this->user->user_type);
                exit;
            } else {
                $error_msg = "Invalid email or password.";
            }
        }
        
        // Load the view
        require_once BASE_PATH . '/views/auth/login.php';
    }
    
    public function signup() {
        if(isset($_SESSION['user_id'])) {
            $this->redirectBasedOnUserType($_SESSION['user_type']);
            exit;
        }
        
        $error_msg = "";
        $success_msg = "";
        
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $this->user->name = trim($_POST['name']);
                $this->user->email = trim($_POST['email']);
                $this->user->password = $_POST['password'];
                $this->user->user_type = 'user';
                
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
                        $_SESSION['user_type'] = $this->user->user_type;
                        $_SESSION['balance'] = $this->user->balance;
                        
                        $this->redirectBasedOnUserType($this->user->user_type);
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
        
        require_once BASE_PATH . '/views/auth/signup.php';
    }

    
    public function logout() {
        $_SESSION = array();
    
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    
        session_destroy();
    
        // Redirect to home page
        header("Location: " . BASE_URL);
        exit;
    }
    
    private function redirectBasedOnUserType($user_type) {
        if ($user_type == 'admin') {
            header("Location: index.php?action=admin_dashboard");
        } 
        else {
            header("Location: index.php");
        }
        exit;
    }
}
?>