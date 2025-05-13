<?php
// controllers/train/SearchController.php

require_once MODELS_PATH . '/train/Train.php';
require_once MODELS_PATH . '/station/Stations.php';

class SearchController {
    private $trainModel;
    private $stationModel;
    private $pdo;

    public function __construct() {
        $this->pdo = DatabaseConfig::getConnection();
        $this->trainModel   = new Train($this->pdo);
        $this->stationModel = new StationModel();
    }

    public function search() {
        $departureStation           = $_POST['departureStation'] ?? '';
        $arrivalStation             = $_POST['arrivalStation'] ?? '';
        $departureDate              = $_POST['departureDate'] ?? '';
        $tripType                   = $_POST['tripType'] ?? 'oneWay';
        $travelClass                = $_POST['travelClass'] ?? 'second';
        $returnDate                 = $_POST['returnDate'] ?? '';

        $errors = $this->validateInput($departureStation, $arrivalStation, $departureDate, $tripType, $returnDate);
        $availableTrains = [];
        
        if (empty($errors)) {
            $departureDateObj = new DateTime($departureDate);
            $dayOfWeek = $departureDateObj->format('l');
            
            $trainResults = $this->trainModel->searchTrains($departureStation, $arrivalStation, $dayOfWeek);
            
            foreach ($trainResults as $train) {
                $stations = $this->stationModel->getRouteStations(
                    $train['schedule_id'],
                    $train['route_id'],
                    $train['departure_sequence'],
                    $train['arrival_sequence']
                );
                
                $availableTrains[] = $this->formatTrainData($train, $stations, $departureStation, $arrivalStation);
            }
        }

        $formattedDepartureDate = $this->formatDate($departureDate);
        $formattedReturnDate = ($tripType === 'roundTrip' && !empty($returnDate)) ? $this->formatDate($returnDate) : '';

        require_once VIEWS_PATH . '/search_results.php';
    }

    private function validateInput($departureStation, $arrivalStation, $departureDate, $tripType, $returnDate) {
        $errors = [];
        if (empty($departureStation)) $errors[]                 = 'Please select a departure station.';
        if (empty($arrivalStation)) $errors[]                   = 'Please select an arrival station.';
        if ($departureStation === $arrivalStation) $errors[]    = 'Departure and arrival stations cannot be the same.';
        if (empty($departureDate)) $errors[] = 'Please select a departure date.';
        if ($tripType === 'roundTrip' && empty($returnDate)) $errors[] = 'Please select a return date.';
        return $errors;
    }

    private function formatTrainData($train, $stations, $departureStation, $arrivalStation) {
        $stationNames = array_column($stations, 'station_name');
        
        $departureTime = new DateTime($train['departure_time']);
        $arrivalTime = new DateTime($train['arrival_time']);
        
        if ($arrivalTime < $departureTime) {
            $arrivalTime->modify('+1 day');
        }
        
        $interval = $departureTime->diff($arrivalTime);
        $travelTime = sprintf("%d:%02d", $interval->h, $interval->i);
        
        return [
            'train_id'                  => $train['train_id'],
            'train_number'              => $train['train_number'],
            'train_name'                => $train['train_name'],
            'route_name'                => $train['route_name'],
            'route_id'                  => $train['route_id'],
            'schedule_id'               => $train['schedule_id'],
            'departure_station'         => $departureStation,
            'arrival_station'           => $arrivalStation,
            'departure_time'            => $departureTime->format('H:i'),
            'arrival_time'              => $arrivalTime->format('H:i'),
            'travel_time'               => $travelTime,
            'intermediate_stops'        => count($stations) - 2,
            'first_class_price'         => $train['first_class_price'],
            'second_class_price'        => $train['second_class_price'],
            'has_wifi'                  => $train['has_wifi'],
            'has_food'                  => $train['has_food'],
            'has_power_outlets'         => $train['has_power_outlets'],
            'stations'                  => $stationNames
        ];
    }

    private function formatDate($dateString) {
        if (empty($dateString)) return '';
        $dateObj = new DateTime($dateString);
        return $dateObj->format('D, M j, Y');
    }


    public function getAllStations() {
        return $this->stationModel->getAllStations();
    }
}