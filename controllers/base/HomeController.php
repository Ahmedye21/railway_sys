<?php
// controllers/base/HomeController.php

require_once MODELS_PATH . '/station/Stations.php';

class HomeController {

    public function __construct() {
        // session already started in index.php
    }

    public function home() {

        require_once VIEWS_PATH . '/home.php';
    }
}
