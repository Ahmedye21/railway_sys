<?php
// public/routes/web.php

$action = $_GET['action'] ?? 'home';

switch ($action) {

    case 'login':
        require_once CONTROLLERS_PATH . '/auth/AuthController.php';
        (new AuthController())->login();
        break;

    case 'signup':
        require_once CONTROLLERS_PATH . '/auth/AuthController.php';
        (new AuthController())->signup();
        break;

    case 'logout':
        require_once CONTROLLERS_PATH . '/auth/AuthController.php';
        (new AuthController())->logout();
        break;

    case 'admin_dashboard':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php");
            exit;
        }
        require_once CONTROLLERS_PATH . '/admin/Controller.php';
        (new AdminDashboardController())->index();
        break;

    case 'home':
        require_once CONTROLLERS_PATH . '/base/HomeController.php';
        (new HomeController())->home();
        break;

    case 'search_trains':
        require_once CONTROLLERS_PATH . '/train/Controller.php';
        (new SearchResultsController())->search();
        break;

    case 'profile':
        require_once CONTROLLERS_PATH . '/user/Controller.php';
        (new UserDashboardController())->index();
        break;


    // You can uncomment these as you implement them
    /*
    case 'tracking':
        require_once CONTROLLERS_PATH . '/train/TrackingController.php';
        (new TrackingController())->index();
        break;

    case 'schedule':
        require_once CONTROLLERS_PATH . '/train/ScheduleController.php';
        (new ScheduleController())->index();
        break;

    case 'contact':
        require_once CONTROLLERS_PATH . '/base/ContactController.php';
        (new ContactController())->index();
        break;

    case 'profile':
        require_once CONTROLLERS_PATH . '/user/ProfileController.php';
        (new ProfileController())->index();
        break;

    case 'bookings':
        require_once CONTROLLERS_PATH . '/booking/BookingController.php';
        (new BookingController())->myBookings();
        break;

    case 'search':
        require_once CONTROLLERS_PATH . '/train/SearchController.php';
        (new SearchController())->search();
        break;
    */

    default:
        http_response_code(404);
        echo "404 Not Found: Invalid route.";
}
