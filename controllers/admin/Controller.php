<?php

require_once MODELS_PATH . '/user/User.php';
require_once MODELS_PATH . '/train/Train.php';
require_once MODELS_PATH . '/routes/Routes.php';


class AdminDashboardController {
    private $trainModel;
    private $routeModel;
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        $this->trainModel = new Train(DatabaseConfig::getConnection());
        $this->routeModel = new Route(DatabaseConfig::getConnection());
    }
    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header("Location: index.php?action=login");
            exit;
        }
        $userModel  = new User();
        $users      = $userModel->getAllUsers();
        if (!$users) {
            $users = [];
        }
        $userCount = count($users);

        $userCount  = number_format($userCount);
        

        $data = [
            'userCount' => $userCount,
        ];
        
        require_once VIEWS_PATH . '/admin/dashboard.php';
    }

    public function manageUsers() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header("Location: index.php?action=login");
            exit;
        }
        $userModel      = new User();
        $users          = $userModel->getAllUsers();
        if (!$users) {
            $users = [];
        }
        $userCount      = count($users);
        $userBalance    = 0;
        foreach ($users as $user) {
            $userBalance += $user['balance'];
        }
        $userBalance = number_format($userBalance, 2);
        $userCount  = number_format($userCount);
        

        $data = [
            'users'      => $users,
            'userCount'  => $userCount,
            'userBalance' => $userBalance,
        ];
        $message    = $_SESSION['message'] ?? null;
        $error      = $_SESSION['error'] ?? null;
        unset($_SESSION['message'], $_SESSION['error']);

        
        require_once VIEWS_PATH . '/admin/manage_users.php';
    }

    public function manageTrains() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: " . BASE_URL . "/index.php?action=login");
            exit;
        }
    
        try {
            $trains = $this->trainModel->getAllTrainsWithRoutes();
            $routes = $this->routeModel->getAllRoutes();
            
            $trains = array_map(function($train) {
                return array_merge([
                    'train_id' => null,
                    'train_number' => '--',
                    'name' => '--',
                    'route_name' => '--',
                    'total_first_class_seats' => 0,
                    'total_second_class_seats' => 0,
                    'has_wifi' => 0,
                    'has_food' => 0,
                    'has_power_outlets' => 0,
                    'route_id' => null
                ], $train);
            }, $trains ?: []);
    
            $data = [
                'trains' => $trains,
                'routes' => $routes ?: [],
                'message' => $_SESSION['message'] ?? null,
                'error' => $_SESSION['error'] ?? null
            ];
            
            unset($_SESSION['message'], $_SESSION['error']);
            
            require_once VIEWS_PATH . '/admin/manage_trains.php';
            
        } catch (Exception $e) {
            error_log("Error in manageTrains: " . $e->getMessage());
            $_SESSION['error'] = "An error occurred while loading train data";
            header("Location: " . BASE_URL . "/index.php?action=manage_trains");
            exit;
        }
    }


}

