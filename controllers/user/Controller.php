<?php
require_once MODELS_PATH . '/user/User.php';
require_once MODELS_PATH . '/ticket/Ticket.php';

class UserDashboardController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $ticketModel = new Ticket();
        $upcomingTrip = $ticketModel->getTicketByUserId($_SESSION['user_id']);



        $data = [
        'ticket' => $upcomingTrip
        ];
        
        $userModel = $this->userModel;
        extract($data);

        require_once VIEWS_PATH . '/user/dashboard.php';
    }

    public function cancelTicket() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
        
        if (!$bookingId) {
            http_response_code(400); 
            echo json_encode(['success' => false, 'message' => 'Invalid booking ID']);
            exit;
        }
        
        $ticketModel = new Ticket();
        $ticket = $ticketModel->getTicketById($bookingId);
        
        if (!$ticket || $ticket['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'You are not authorized to cancel this ticket']);
            exit;
        }
        
        // Cancel the ticket
        $result = $ticketModel->cancelTicket($bookingId);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Ticket cancelled successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to cancel ticket']);
        }
        exit;
    }
    
    public function funds() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRecharge();
        }
        
        $_SESSION['balance'] = $this->userModel->getBalance($_SESSION['user_id']);
        
        require_once VIEWS_PATH . '/user/recharge.php';
    }
    
    private function processRecharge() {
        try {
            $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);

            if ($amount === false || $amount < 100) {
                throw new Exception("Minimum recharge amount is EGP 100");
            }

            $rechargedAmount = $this->userModel->rechargeBalance($_SESSION['user_id'], $amount);
            
            $_SESSION['balance'] = $this->userModel->getBalance($_SESSION['user_id']);

            $_SESSION['success'] = "Successfully added EGP " . number_format($rechargedAmount, 2) . " to your balance";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: index.php?action=funds");
        exit;
    }

    public function toggleNotifications() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        
        $currentSetting = $this->userModel->getNotificationPreference($_SESSION['user_id']);
        $newSetting = !$currentSetting;
        
        if ($this->userModel->updateNotificationPreference($_SESSION['user_id'], $newSetting)) {
            $_SESSION['success'] = $newSetting 
                ? "You have successfully opted in to system notifications." 
                : "You have successfully opted out of system notifications.";
        } else {
            $_SESSION['error'] = "Failed to update notification preferences.";
        }
        
        // Redirect back to dashboard
        header("Location: index.php?action=profile");
        exit;
    }
}