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

    case 'station_master_dashboard':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'station_master') {
            header("Location: index.php");
            exit;
        }
        require_once CONTROLLERS_PATH . '/station_master/Controller.php';
        (new StationMasterController())->index();
        break;

    case 'home':
        require_once CONTROLLERS_PATH . '/base/HomeController.php';
        (new HomeController())->home();
        break;


    case 'search_trains':
        require_once CONTROLLERS_PATH . '/SearchController.php';
        (new SearchController())->search();
        break;

    case 'profile':
        require_once CONTROLLERS_PATH . '/user/Controller.php';
        (new UserDashboardController())->index();
        break;




    case 'funds':
        require_once CONTROLLERS_PATH . '/user/Controller.php';
        (new UserDashboardController())->funds();
        break;

    case 'train_tracking':
        require_once CONTROLLERS_PATH . '/train/TrackingController.php';
        (new TrackingController())->index();
        break;
        
    case 'get_train_status':
    require_once CONTROLLERS_PATH . '/train/TrackingController.php';
    (new TrackingController())->getTrainStatus();
    break;

    case 'arrivals':
        require_once CONTROLLERS_PATH . '/SchedulesController.php';
        (new StationsController())->arrivals();
        break;
        
    
    case 'search_stations':
        require_once CONTROLLERS_PATH . '/station/Stations.php';
        (new StationsController())->search();
        break;
    
    case 'add_station':
        require_once CONTROLLERS_PATH . '/station/Stations.php';
        (new StationsController())->add();
        break;
    
    case 'edit_station':
        $stationId = $_GET['id'] ?? 0;
        require_once CONTROLLERS_PATH . '/station/Stations.php';
        (new StationsController())->edit($stationId);
        break;
    
    case 'delete_station':
        $stationId = $_GET['id'] ?? 0;
        require_once CONTROLLERS_PATH . '/station/Stations.php';
        (new StationsController())->delete($stationId);
        break;

    case 'manage_users':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php");
            exit;
        }
        require_once CONTROLLERS_PATH . '/admin/Controller.php';
        (new AdminDashboardController())->manageUsers();
        break;
    
    case 'admin_add_user':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit;
        }
        require_once CONTROLLERS_PATH . '/admin/Controller.php';
        (new AdminDashboardController())->adminAddUser();
        break;

    case 'admin_edit_user':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit;
        }
        require_once CONTROLLERS_PATH . '/admin/Controller.php';
        (new AdminDashboardController())->adminEditUser();
        break;

    case 'manage_trains':
        require_once CONTROLLERS_PATH . '/admin/Controller.php';
        (new AdminDashboardController())->manageTrains();
        break;

    case 'admin_add_train':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit;
        }
        require_once CONTROLLERS_PATH . '/admin/Controller.php';
        (new AdminDashboardController())->adminAddTrain();
        break;

    case 'admin_edit_train':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit;
        }
        require_once CONTROLLERS_PATH . '/admin/Controller.php';
        (new AdminDashboardController())->adminEditTrain();
        break;
    
    case 'create_alert':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit;
        }
        require_once CONTROLLERS_PATH . '/admin/Controller.php';
        (new AdminDashboardController())->createAlert();
        break;
    
    case 'toggle_notifications':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        require_once CONTROLLERS_PATH . '/user/Controller.php';
        (new UserDashboardController())->toggleNotifications();
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