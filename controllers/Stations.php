<?php
// controllers/station/Stations.php

require_once MODELS_PATH . '/station/Stations.php';

class StationsController {
    private $model;

    public function __construct() {
        $this->model = new StationModel();
    }

    /**
     * Show station arrivals/departures
     */
    public function arrivals() {
        $stationName = $_GET['station'] ?? '';
        $trains = [];
        
        if (!empty($stationName)) {
            // Get station by name
            $station = $this->model->getStationByName($stationName);
            
            if ($station) {
                // Get trains arriving at this station
                $trains = $this->model->getTrainsByStation($station['station_id']);
            } else {
                $_SESSION['error'] = "Station not found";
            }
        }
        
        // Get all stations for autocomplete
        $allStations = $this->model->getAllStations();
        
        require_once VIEWS_PATH . '/schedules.php';
    }

    /**
     * Search stations (for AJAX requests)
     */
    public function search() {
        $query = $_GET['query'] ?? '';
        $results = $this->model->searchStations($query);
        
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }

    /**
     * Add new station (admin function)
     */
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $code = $_POST['code'] ?? '';
            $city = $_POST['city'] ?? '';
            
            if ($this->model->addStation($name, $code, $city)) {
                $_SESSION['success'] = 'Station added successfully';
                header('Location: ' . BASE_URL . '/index.php?action=admin_stations');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to add station';
            }
        }
        
        require_once VIEWS_PATH . '/admin/add_station.php';
    }

    /**
     * Edit station (admin function)
     */
    public function edit($stationId) {
        $station = $this->model->getStationById($stationId);
        
        if (!$station) {
            $_SESSION['error'] = 'Station not found';
            header('Location: ' . BASE_URL . '/index.php?action=admin_stations');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $code = $_POST['code'] ?? '';
            $city = $_POST['city'] ?? '';
            
            if ($this->model->updateStation($stationId, $name, $code, $city)) {
                $_SESSION['success'] = 'Station updated successfully';
                header('Location: ' . BASE_URL . '/index.php?action=admin_stations');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to update station';
            }
        }
        
        require_once VIEWS_PATH . '/admin/edit_station.php';
    }

    /**
     * Delete station (admin function)
     */
    public function delete($stationId) {
        if ($this->model->deleteStation($stationId)) {
            $_SESSION['success'] = 'Station deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete station';
        }
        
        header('Location: ' . BASE_URL . '/index.php?action=admin_stations');
        exit;
    }
}