<?php

// controllers/UserDashboardController.php
class UserDashboardController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        
        // Show user dashboard
        require_once VIEWS_PATH . '/user/dashboard.php';
    }
}

// controllers/UserController.php
