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
}