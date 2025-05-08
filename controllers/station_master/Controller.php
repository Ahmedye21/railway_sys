<?php 

require_once MODELS_PATH . '/train/Train.php';
require_once MODELS_PATH . '/user/User.php';
require_once MODELS_PATH . '/station/Stations.php';


class StationMasterController {
    private $stationModel;
    private $trainModel;
    private $userModel;

    public function __construct() {
        $this->stationModel     = new StationModel();
        $this->trainModel       = new Train();
        $this->userModel        = new User();  

    }

    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'station_master') {
            header("Location: index.php?action=login");
            exit;
        }
        
        $stations = $this->stationModel->getAllStations();
        $stations = $stations ?: []; 
        
        $trains = $this->trainModel->getAllTrains();
        $trains = $trains ?: [];     
        
        $data = [
            'stationCount' => number_format(count($stations)),
            'stations' => $stations,
            'trainCount' => number_format(count($trains)),
            'trains' => $trains,
        ];
        
        extract($data);  // Extract variables for the view
        require_once VIEWS_PATH . '/station_master/dashboard.php';
    }
}