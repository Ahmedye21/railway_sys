<?php
// controllers/base/HomeController.php

require_once MODELS_PATH . '/station/Stations.php';

class HomeController {

    public function __construct() {
        // session already started in index.php
    }

    public function home() {
        // Redirect if user is logged in
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') {
                header("Location: index.php?action=admin_dashboard");
                exit;
            } else {
                header("Location: index.php?action=user_dashboard");
                exit;
            }
        }

        // Get all stations
        $stationModel = new StationModel();
        $stations = $stationModel->getAllStations();

        // Make $stations available to view
        // either:
        // extract(['stations' => $stations]);
        // or:
        global $stations;

        require_once VIEWS_PATH . '/home.php';
    }
}
