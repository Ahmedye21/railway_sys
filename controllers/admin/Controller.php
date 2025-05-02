<?php

class AdminDashboardController {
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
            header("Location: index.php?action=login");
            exit;
        }
        
        require_once VIEWS_PATH . '/admin/dashboard.php';
    }
}

