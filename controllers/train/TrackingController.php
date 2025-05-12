<?php

require_once MODELS_PATH . '/train/Train.php';
require_once MODELS_PATH . '/train/TrainTracking.php';

class TrackingController {
    private $trainModel;
    private $trainTrackingModel;

    public function __construct() {
        $this->trainModel = new Train();
        $this->trainTrackingModel = new TrainTracking();
    }

    public function index() {
        require_once VIEWS_PATH . '/train/live_train_tracking.php';
    }

    public function getTrainStatus() {
        $searchType = isset($_POST['searchType']) ? $_POST['searchType'] : 'number';
        $searchValue = isset($_POST['searchValue']) ? $_POST['searchValue'] : '';
        
        if (empty($searchValue)) {
            echo json_encode(['error' => 'Please enter a train number or name']);
            return;
        }
        
        // Get train details from the model
        $trainInfo = $this->trainTrackingModel->getTrainInfo($searchType, $searchValue);
        
        if (!$trainInfo) {
            echo json_encode(['error' => 'Train not found']);
            return;
        }
        
        // Get train status information
        $trainStatus = $this->trainTrackingModel->getTrainStatusInfo($trainInfo['train_id']);
        
        // Get journey progress
        $journeyProgress = $this->trainTrackingModel->getJourneyProgress(
            $trainInfo['train_id'], 
            isset($trainStatus['schedule_id']) ? $trainStatus['schedule_id'] : null
        );
        
        // Combine all data and return
        $response = [
            'trainInfo' => $trainInfo,
            'trainStatus' => $trainStatus,
            'journeyProgress' => $journeyProgress
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}