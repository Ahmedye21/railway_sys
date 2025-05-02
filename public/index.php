<?php
// public/index.php
require_once __DIR__ . '/paths.php';

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Define base URL - adjust according to your server setup
define('BASE_URL', '/SE/public'); // Adjust to your actual base URL
define('APP_URL', 'http://localhost/rail-connect/public');

// Start session
session_start();

// Set default action
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch($action) {
    case 'login':
        require_once CONTROLLERS_PATH . '/auth/AuthController.php';
        $authController = new AuthController();
        $authController->login();
        break;
        
    case 'signup':
        require_once CONTROLLERS_PATH . '/auth/AuthController.php';
        $authController = new AuthController();
        $authController->signup();
        break;
        
    case 'logout':
        require_once CONTROLLERS_PATH . '/auth/AuthController.php';
        $authController = new AuthController();
        $authController->logout();
        break;

    case 'admin_dashboard':
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
            header("Location: index.php");
            exit;
        }
        
        require_once CONTROLLERS_PATH . '/admin/Controller.php';
        $controller = new AdminDashboardController();
        $controller->index();
        break;

    case 'home':
        default:
            require_once VIEWS_PATH . '/home.php';
            break;
        
    // case 'tracking':
    //     require_once '../controllers/train/TrackingController.php';
    //     $trackingController = new TrackingController();
    //     $trackingController->index();
    //     break;
        
    // case 'schedule':
    //     require_once '../controllers/train/ScheduleController.php';
    //     $scheduleController = new ScheduleController();
    //     $scheduleController->index();
    //     break;
        
    // case 'contact':
    //     require_once '../controllers/base/ContactController.php';
    //     $contactController = new ContactController();
    //     $contactController->index();
    //     break;
        
    // case 'profile':
    //     require_once '../controllers/user/ProfileController.php';
    //     $profileController = new ProfileController();
    //     $profileController->index();
    //     break;
        
    // case 'bookings':
    //     require_once '../controllers/booking/BookingController.php';
    //     $bookingController = new BookingController();
    //     $bookingController->myBookings();
    //     break;
        
    // case 'search':
    //     require_once '../controllers/train/SearchController.php';
    //     $searchController = new SearchController();
    //     $searchController->search();
    //     break;
        

}
?>