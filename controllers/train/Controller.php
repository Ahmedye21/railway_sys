<?php
require_once MODELS_PATH . '/train/Train.php';
require_once MODELS_PATH . '/station/Stations.php';

class SearchController {
    private $stationModel;
    private $trainModel;
    
    public function __construct() {
        $this->stationModel = new StationModel();
        $this->trainModel = new TrainModel();
    }
    
    /**
     * Show search form with stations
     */
    public function showSearchForm() {
        try {
            $stations = $this->stationModel->getAllStations();
            
            if (empty($stations)) {
                throw new Exception("No stations available. Please try again later.");
            }
            
            // Pass data to view
            $data = [
                'stations' => $stations,
                'page_title' => 'Search Trains'
            ];
            
            include VIEWS_PATH . 'train/search_trains.php';
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }
    
    /**
     * Process search and show results
     */
    public function search() {
        try {
            // Sanitize input
            $input = $this->sanitizeSearchInput($_POST);
            
            // Validate input
            $errors = $this->validateSearchInput(
                $input['departureStation'],
                $input['arrivalStation'],
                $input['departureDate'],
                $input['tripType'],
                $input['returnDate']
            );
            
            if (!empty($errors)) {
                throw new Exception(implode("<br>", $errors));
            }
            
            // Search for trains
            $availableTrains = $this->trainModel->searchTrains(
                $input['departureStation'],
                $input['arrivalStation'],
                $input['departureDate'],
                $input['travelClass']
            );
            
            // Prepare data for view
            $data = [
                'stations' => $this->stationModel->getAllStations(),
                'trains' => $availableTrains,
                'search_params' => $input,
                'formatted_departure_date' => $this->formatDateForDisplay($input['departureDate']),
                'formatted_return_date' => $input['tripType'] === 'roundTrip' 
                    ? $this->formatDateForDisplay($input['returnDate']) 
                    : null,
                'page_title' => 'Search Results'
            ];
            
            include VIEWS_PATH . 'train/search_results.php';
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }
    
    /**
     * Sanitize search input
     */
    private function sanitizeSearchInput($postData) {
        return [
            'departureStation' => trim($postData['departureStation'] ?? ''),
            'arrivalStation' => trim($postData['arrivalStation'] ?? ''),
            'departureDate' => trim($postData['departureDate'] ?? ''),
            'tripType' => trim($postData['tripType'] ?? 'oneWay'),
            'travelClass' => trim($postData['travelClass'] ?? 'second'),
            'returnDate' => trim($postData['returnDate'] ?? '')
        ];
    }
    
    /**
     * Handle errors consistently
     */
    private function handleError($message) {
        $data = [
            'error_message' => $message,
            'stations' => $this->stationModel->getAllStations(),
            'page_title' => 'Error'
        ];
        
        include VIEWS_PATH . 'error.php';
    }
    
    /**
     * Validate search input
     */
    private function validateSearchInput($departureStation, $arrivalStation, $departureDate, $tripType, $returnDate) {
        $errors = [];
        
        if (empty($departureStation)) {
            $errors[] = "Departure station is required.";
        }
        
        if (empty($arrivalStation)) {
            $errors[] = "Arrival station is required.";
        }
        
        if (empty($departureDate)) {
            $errors[] = "Departure date is required.";
        } elseif (!$this->isValidDate($departureDate)) {
            $errors[] = "Invalid departure date format.";
        }
        
        if ($tripType === 'roundTrip' && empty($returnDate)) {
            $errors[] = "Return date is required for round trips.";
        } elseif ($tripType === 'roundTrip' && !$this->isValidDate($returnDate)) {
            $errors[] = "Invalid return date format.";
        }
        
        return $errors;
    }
    
    /**
     * Check if a date is valid
     */
    private function isValidDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Format a date for display
     */
    private function formatDateForDisplay($date) {
        $formattedDate = DateTime::createFromFormat('Y-m-d', $date);
        return $formattedDate ? $formattedDate->format('d M Y') : $date;
    }
    
    // ... keep the existing validateSearchInput and formatDateForDisplay methods
}