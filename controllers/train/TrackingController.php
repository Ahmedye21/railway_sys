<?php

require_once MODELS_PATH . '/train/Train.php';

class TrackingController {
    private $trainModel;
    private $stationModel;
    private $pdo;

    public function __construct() {

    }

    public function index() {

        require_once VIEWS_PATH . 'train/live_train_tracking.php';
    }

}