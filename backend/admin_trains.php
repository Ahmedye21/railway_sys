<?php
session_start();
include_once "../config.php";

// Check admin authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pdo = DatabaseConfig::getConnection();
$message = '';
$error = '';

// Handle train operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_train':
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO trains (train_number, name, route_id, 
                            total_first_class_seats, total_second_class_seats, 
                            has_wifi, has_food, has_power_outlets)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    
                    $stmt->execute([
                        $_POST['train_number'],
                        $_POST['name'],
                        $_POST['route_id'],
                        $_POST['first_class_seats'],
                        $_POST['second_class_seats'],
                        isset($_POST['has_wifi']) ? 1 : 0,
                        isset($_POST['has_food']) ? 1 : 0,
                        isset($_POST['has_power_outlets']) ? 1 : 0
                    ]);
                    
                    $message = "Train added successfully!";
                } catch (PDOException $e) {
                    $error = "Error adding train: " . $e->getMessage();
                }
                break;

            case 'update_status':
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO train_status (train_id, schedule_id, current_station_id, 
                            next_station_id, status, delay_minutes, expected_arrival)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE
                            status = VALUES(status),
                            delay_minutes = VALUES(delay_minutes),
                            expected_arrival = VALUES(expected_arrival)
                    ");
                    
                    $expected_arrival = date('Y-m-d H:i:s', strtotime($_POST['expected_arrival']));
                    
                    $stmt->execute([
                        $_POST['train_id'],
                        $_POST['schedule_id'],
                        $_POST['current_station'],
                        $_POST['next_station'],
                        $_POST['status'],
                        $_POST['delay_minutes'],
                        $expected_arrival
                    ]);

                    // Log delay if status is 'Delayed'
                    if ($_POST['status'] === 'Delayed' && $_POST['delay_minutes'] > 0) {
                        $stmt = $pdo->prepare("
                            INSERT INTO delay_logs (train_id, schedule_id, station_id, 
                                scheduled_time, actual_time, delay_minutes, reason, reported_by)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                        ");
                        
                        $scheduled_time = date('Y-m-d H:i:s', strtotime($_POST['scheduled_time']));
                        $actual_time = date('Y-m-d H:i:s', strtotime($scheduled_time . ' + ' . $_POST['delay_minutes'] . ' minutes'));
                        
                        $stmt->execute([
                            $_POST['train_id'],
                            $_POST['schedule_id'],
                            $_POST['current_station'],
                            $scheduled_time,
                            $actual_time,
                            $_POST['delay_minutes'],
                            $_POST['delay_reason'],
                            $_SESSION['user_id']
                        ]);
                    }
                    
                    $message = "Train status updated successfully!";
                } catch (PDOException $e) {
                    $error = "Error updating train status: " . $e->getMessage();
                }
                break;
            
            case 'edit_train':
                try {
                    $stmt = $pdo->prepare("
                        UPDATE trains 
                        SET train_number = ?,
                            name = ?,
                            route_id = ?,
                            total_first_class_seats = ?,
                            total_second_class_seats = ?,
                            has_wifi = ?,
                            has_food = ?,
                            has_power_outlets = ?
                        WHERE train_id = ?
                    ");
                    
                    $stmt->execute([
                        $_POST['train_number'],
                        $_POST['name'],
                        $_POST['route_id'],
                        $_POST['first_class_seats'],
                        $_POST['second_class_seats'],
                        isset($_POST['has_wifi']) ? 1 : 0,
                        isset($_POST['has_food']) ? 1 : 0,
                        isset($_POST['has_power_outlets']) ? 1 : 0,
                        $_POST['train_id']
                    ]);
                    
                    $message = "Train updated successfully!";
                } catch (PDOException $e) {
                    $error = "Error updating train: " . $e->getMessage();
                }
                break;
            
            case 'delete_train':
                try {
                    // First check if train has any bookings
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE train_id = ? AND booking_status != 'Cancelled'");
                    $stmt->execute([$_POST['train_id']]);
                    $bookingCount = $stmt->fetchColumn();

                    if ($bookingCount > 0) {
                        $error = "Cannot delete train: There are active bookings for this train";
                    } else {
                        // Delete train status records
                        $stmt = $pdo->prepare("DELETE FROM train_status WHERE train_id = ?");
                        $stmt->execute([$_POST['train_id']]);

                        // Delete train schedule details
                        $stmt = $pdo->prepare("
                            DELETE sd FROM schedule_details sd
                            INNER JOIN schedules s ON sd.schedule_id = s.schedule_id
                            WHERE s.train_id = ?
                        ");
                        $stmt->execute([$_POST['train_id']]);

                        // Delete train schedules
                        $stmt = $pdo->prepare("DELETE FROM schedules WHERE train_id = ?");
                        $stmt->execute([$_POST['train_id']]);

                        // Finally delete the train
                        $stmt = $pdo->prepare("DELETE FROM trains WHERE train_id = ?");
                        $stmt->execute([$_POST['train_id']]);
                        
                        $message = "Train deleted successfully!";
                    }
                } catch (PDOException $e) {
                    $error = "Error deleting train: " . $e->getMessage();
                }
                break;
        }
    }
}

// Get all trains
try {
    $trains = $pdo->query("
        SELECT t.*, r.name as route_name 
        FROM trains t 
        JOIN routes r ON t.route_id = r.route_id 
        ORDER BY t.train_number
    ")->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching trains: " . $e->getMessage();
}

// Get all routes for the dropdown
try {
    $routes = $pdo->query("SELECT route_id, name FROM routes WHERE is_active = 1")->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching routes: " . $e->getMessage();
}

// Get all stations for the dropdowns
try {
    $stations = $pdo->query("SELECT station_id, name FROM stations WHERE is_active = 1")->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching stations: " . $e->getMessage();
}