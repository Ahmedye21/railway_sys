<?php
// controllers/base/HomeController.php

class HomeController {
    
    public function __construct() {
        // No need to start session as it's already started in index.php
    }
    
    public function home() {
        // Check if user is logged in
        if(isset($_SESSION['user_id'])) {
            // Redirect based on user type
            if($_SESSION['user_type'] == 'admin') {
                header("Location: index.php?action=admin_dashboard");
            } else {
                header("Location: index.php?action=user_dashboard");
            }
            exit;
        }
        
        require_once BASE_PATH . '/views/home.php';
    }
}
?>