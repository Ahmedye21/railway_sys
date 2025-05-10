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

    public function adminAddUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'role' => $_POST['role'] ?? 'user',
                'balance' => $_POST['balance'] ?? 0.00
            ];

            if (empty($userData['name']) || empty($userData['email']) || empty($userData['password']) || empty($userData['role'])) {
                $_SESSION['error'] = "All fields (Name, Email, Password, Role) are required.";
            } else if ($this->userModel->createUser($userData)) {
                $_SESSION['message'] = "User added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add user. Email might already exist.";
            }
        } else {
            $_SESSION['error'] = "Invalid request method.";
        }
        header("Location: index.php?action=manage_users");
        exit;
    }

    public function adminEditUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'id' => $_POST['user_id'] ?? 0,
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'role' => $_POST['role'] ?? 'user',
                'balance' => $_POST['balance'] ?? 0.00
            ];
            $newPassword = $_POST['password'] ?? null;

            if (empty($userData['id']) || empty($userData['name']) || empty($userData['email']) || empty($userData['role'])) {
                $_SESSION['error'] = "User ID, Name, Email, and Role are required.";
            } else {
                if ($this->userModel->updateUser($userData, !empty($newPassword) ? $newPassword : null)) {
                    $_SESSION['message'] = "User updated successfully.";
                } else {
                    $_SESSION['error'] = "Failed to update user.";
                }
            }
        } else {
            $_SESSION['error'] = "Invalid request method.";
        }
        header("Location: index.php?action=manage_users");
        exit;
    }

    public function adminAddTrain() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trainData = [
                'train_number' => $_POST['train_number'] ?? '',
                'name' => $_POST['name'] ?? '',
                'route_id' => $_POST['route_id'] ?? '',
                'total_first_class_seats' => $_POST['first_class_seats'] ?? '',
                'total_second_class_seats' => $_POST['second_class_seats'] ?? '',
                'has_wifi' => isset($_POST['has_wifi']) ? 1 : 0,
                'has_food' => isset($_POST['has_food']) ? 1 : 0,
                'has_power_outlets' => isset($_POST['has_power_outlets']) ? 1 : 0
            ];

            if (empty($trainData['train_number']) || empty($trainData['name']) || empty($trainData['route_id']) || empty($trainData['total_first_class_seats']) || empty($trainData['total_second_class_seats']))  {
                $_SESSION['error'] = "All fields (Train number, Name, Route, First class seats and Second class seats) are required.";
            } else if ($this->trainModel->createTrain($trainData)) {
                $_SESSION['message'] = "Train added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add train.";
            }
        } else {
            $_SESSION['error'] = "Invalid request method.";
        }
        header("Location: index.php?action=manage_trains");
        exit;
    }


}

