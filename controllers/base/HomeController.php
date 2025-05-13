<?php
// controllers/base/HomeController.php

require_once MODELS_PATH . '/station/Stations.php';
require_once MODELS_PATH . '/user/User.php';

class HomeController {

    public function __construct() {
        // session already started in index.php
    }

    public function home() {
        $stationModel = new StationModel();
        $stations = $stationModel->getAllStations();
        $notifications = [];
        
        // Only fetch notifications if user hasn't opted out
        $userModel = new User();
        if (!isset($_SESSION['user_id']) ) {
            $notifications = $this->getNotifications();
        }
        
        require_once VIEWS_PATH . '/home.php';
    }

    private function getNotifications() {
        $conn = DatabaseConfig::getConnection();
        $notifications = [];
        
        try {
            $stmt = $conn->prepare("
                SELECT title, message, created_at 
                FROM notifications 
                WHERE user_id IS NULL 
                ORDER BY created_at DESC 
                LIMIT 5
            ");
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching notifications: " . $e->getMessage());
        }
        
        return $notifications;
    }
}
