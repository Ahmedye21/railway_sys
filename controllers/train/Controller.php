<?php

require_once MODELS_PATH . '/train/Train.php';
require_once MODELS_PATH . '/station/Stations.php';


class SearchResultsController {
    private $trainSearchModel;
    private $searchParams = [];
    private $errors = [];
    private $availableTrains = [];
    
    public function __construct() {
        try {
            $pdo = DatabaseConfig::getConnection();
            $this->trainSearchModel = new TrainSearch($pdo);
            $this->processSearchForm();
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }
    
    /**
     * Process the search form data
     */
    private function processSearchForm() {
        error_log(print_r($_POST, true));
        // Process the search form
        $this->searchParams = [
            'departureStation' => isset($_POST['departureStation']) ? $_POST['departureStation'] : '',
            'arrivalStation' => isset($_POST['arrivalStation']) ? $_POST['arrivalStation'] : '',
            'departureDate' => isset($_POST['departureDate']) ? $_POST['departureDate'] : '',
            'tripType' => isset($_POST['tripType']) ? $_POST['tripType'] : 'oneWay',
            'travelClass' => isset($_POST['travelClass']) ? $_POST['travelClass'] : 'second',
            'returnDate' => isset($_POST['returnDate']) ? $_POST['returnDate'] : ''
        ];
        
        // Validate input
        $this->validateSearchParams();
        
        // Search for trains if no errors
        if (empty($this->errors)) {
            try {
                $this->availableTrains = $this->trainSearchModel->searchTrains(
                    $this->searchParams['departureStation'],
                    $this->searchParams['arrivalStation'],
                    $this->searchParams['departureDate']
                );
            } catch (Exception $e) {
                $this->errors[] = $e->getMessage();
            }
        }
    }
    
    /**
     * Validate the search parameters
     */
    private function validateSearchParams() {
        $this->errors = []; // Reset errors
        
        if (empty($this->searchParams['departureStation'])) {
            $this->errors[] = 'Please select a departure station.';
        }
        
        if (empty($this->searchParams['arrivalStation'])) {
            $this->errors[] = 'Please select an arrival station.';
        }
        
        if (!empty($this->searchParams['departureStation']) && 
            !empty($this->searchParams['arrivalStation']) &&
            $this->searchParams['departureStation'] === $this->searchParams['arrivalStation']) {
            $this->errors[] = 'Departure and arrival stations cannot be the same.';
        }
        
        if (empty($this->searchParams['departureDate'])) {
            $this->errors[] = 'Please select a departure date.';
        }
        
        if ($this->searchParams['tripType'] === 'roundTrip' && empty($this->searchParams['returnDate'])) {
            $this->errors[] = 'Please select a return date.';
        }
    }
    
    /**
     * Format the departure date for display
     */
    public function getFormattedDepartureDate() {
        $formattedDate = '';
        if (!empty($this->searchParams['departureDate'])) {
            $dateObj = new DateTime($this->searchParams['departureDate']);
            $formattedDate = $dateObj->format('D, M j, Y');
        }
        return $formattedDate;
    }
    
    /**
     * Format the return date for display
     */
    public function getFormattedReturnDate() {
        $formattedDate = '';
        if ($this->searchParams['tripType'] === 'roundTrip' && !empty($this->searchParams['returnDate'])) {
            $dateObj = new DateTime($this->searchParams['returnDate']);
            $formattedDate = $dateObj->format('D, M j, Y');
        }
        return $formattedDate;
    }
    
    /**
     * Get all search parameters
     */
    public function getSearchParams() {
        return $this->searchParams;
    }
    
    /**
     * Get all validation errors
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get search results (available trains)
     */
    public function getAvailableTrains() {
        return $this->availableTrains;
    }
    
    /**
     * Render the search results page
     */
    public function renderPage() {
        // Load additional data needed for the view
        $formattedDepartureDate = $this->getFormattedDepartureDate();
        $formattedReturnDate = $this->getFormattedReturnDate();
        
        // Include the view file
        include VIEWS_PATH . '/train/search.php';;
    }
    public function search() {
        $this->processSearchForm();
        $this->renderPage();
    }
}

// Instantiate and run the controller
$controller = new SearchResultsController();
$controller->renderPage();