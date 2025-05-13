<?php
require_once MODELS_PATH . '/user/User.php';

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
        
        // Initialize user model to pass to the view
        $userModel = $this->userModel;
        
        require_once VIEWS_PATH . '/user/dashboard.php';
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