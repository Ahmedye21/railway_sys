<?php
require_once MODELS_PATH . '/Booking.php';
require_once MODELS_PATH . '/train/Train.php';
require_once MODELS_PATH . '/station/Stations.php';

class BookingController {
    private $booking;
    private $user;
    private $train;
    private $station;
    
    public function __construct() {
        global $pdo;
        $this->booking  = new Booking($pdo);
        $this->user     = new User($pdo);
        $this->train    = new Train($pdo);
        $this->station  = new StationModel($pdo);
    }
    
    public function processBooking() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "You must be logged in to book a ticket";
            header("Location: index.php?action=login");
            exit;
        }
        
        $trainId                = isset($_GET['train_id']) ? $_GET['train_id'] : null;
        $scheduleId             = isset($_GET['schedule_id']) ? $_GET['schedule_id'] : null;
        $departureStation       = isset($_GET['departure']) ? $_GET['departure'] : null;
        $arrivalStation         = isset($_GET['arrival']) ? $_GET['arrival'] : null;
        $travelDate             = isset($_GET['date']) ? $_GET['date'] : null;
        $travelClass            = isset($_GET['class']) ? $_GET['class'] : 'second';
        $tripType               = isset($_GET['type']) ? $_GET['type'] : 'one_way';
        $returnDate             = isset($_GET['return_date']) ? $_GET['return_date'] : null;
        
        if (!$trainId || !$scheduleId || !$departureStation || !$arrivalStation || !$travelDate) {
            $_SESSION['error'] = "Missing required booking information";
            header("Location: index.php?action=search_form");
            exit;
        }
        
        $trainDetails = $this->train->getTrainById($trainId);
        if (!$trainDetails) {
            $_SESSION['error'] = "Train not found";
            header("Location: index.php?action=search_form");
            exit;
        }
        
        $fromStationId = $this->station->getStationIdByName($departureStation);
        $toStationId = $this->station->getStationIdByName($arrivalStation);
        
        if (!$fromStationId || !$toStationId) {
            $_SESSION['error'] = "Invalid station information";
            header("Location: index.php?action=search_form");
            exit;
        }
        
        $ticketPrice = ($travelClass === 'first') ? $trainDetails['first_class_price'] : $trainDetails['second_class_price'];
        
        $userId = $_SESSION['user_id'];
        $userBalance = $this->user->getUserBalance($userId);
        
        if ($userBalance < $ticketPrice) {
            $_SESSION['error'] = "Insufficient balance. Please add funds to your account.";
            header("Location: index.php?action=user_profile");
            exit;
        }
        
        try {
            $this->booking->beginTransaction();
            
            $balanceDeducted = $this->user->deductBalance($userId, $ticketPrice);
            
            if (!$balanceDeducted) {
                throw new Exception("Failed to deduct balance");
            }
            
            $bookingDate = date('Y-m-d H:i:s');
            $bookingId = $this->booking->createBooking(
                $userId, 
                $trainId, 
                $scheduleId, 
                $fromStationId, 
                $toStationId, 
                $bookingDate, 
                $travelDate, 
                $travelClass, 
                $ticketPrice
            );
            
            if (!$bookingId) {
                throw new Exception("Failed to create booking");
            }
            
            $this->booking->commit();
            
            header("Location: index.php?action=booking_confirmation&booking_id=" . $bookingId);
            exit;
            
        } catch (Exception $e) {
            $this->booking->rollBack();
            $_SESSION['error'] = "Booking failed: " . $e->getMessage();
            header("Location: index.php?action=search_trains");
            exit;
        }
    }



    public function showConfirmation() {
        $bookingId = isset($_GET['booking_id']) ? $_GET['booking_id'] : null;
        
        if (!$bookingId) {
            $_SESSION['error'] = "Booking ID not found";
            header("Location: index.php");
            exit;
        }
        
        $bookingDetails = $this->booking->getBookingDetails($bookingId);
        
        if (!$bookingDetails) {
            $_SESSION['error'] = "Booking not found";
            header("Location: index.php");
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $updatedBalance = $this->user->getUserBalance($userId);
        
        $data = [
            'booking' => $bookingDetails,
            'updated_balance' => $updatedBalance
        ];
        
        require_once VIEWS_PATH . '/booking_confirmation.php';
    }
}