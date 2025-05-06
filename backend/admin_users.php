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

// Handle user operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_user':
                try {
                    // Hash the password
                    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    
                    $stmt = $pdo->prepare("
                        INSERT INTO users (name, email, password, role, balance)
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    
                    $stmt->execute([
                        $_POST['name'],
                        $_POST['email'],
                        $hashed_password,
                        $_POST['role'],
                        $_POST['balance'] ?? 0.00
                    ]);
                    
                    $message = "User added successfully!";
                } catch (PDOException $e) {
                    $error = "Error adding user: " . $e->getMessage();
                }
                break;

            case 'edit_user':
                try {
                    $sql = "UPDATE users SET name = ?, email = ?, role = ?, balance = ?";
                    $params = [
                        $_POST['name'],
                        $_POST['email'],
                        $_POST['role'],
                        $_POST['balance']
                    ];

                    // Only update password if a new one is provided
                    if (!empty($_POST['password'])) {
                        $sql .= ", password = ?";
                        $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    }

                    $sql .= " WHERE user_id = ?";
                    $params[] = $_POST['user_id'];

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    
                    $message = "User updated successfully!";
                } catch (PDOException $e) {
                    $error = "Error updating user: " . $e->getMessage();
                }
                break;

            case 'delete_user':
                try {
                    // Check for active bookings
                    $stmt = $pdo->prepare("
                        SELECT COUNT(*) FROM bookings 
                        WHERE user_id = ? AND booking_status = 'Confirmed'
                    ");
                    $stmt->execute([$_POST['user_id']]);
                    $activeBookings = $stmt->fetchColumn();

                    if ($activeBookings > 0) {
                        $error = "Cannot delete user: There are active bookings";
                    } else {
                        // Delete user's transactions
                        $stmt = $pdo->prepare("DELETE FROM transactions WHERE user_id = ?");
                        $stmt->execute([$_POST['user_id']]);

                        // Delete user's notifications
                        $stmt = $pdo->prepare("DELETE FROM notifications WHERE user_id = ?");
                        $stmt->execute([$_POST['user_id']]);

                        // Delete user's bookings
                        $stmt = $pdo->prepare("DELETE FROM bookings WHERE user_id = ?");
                        $stmt->execute([$_POST['user_id']]);

                        // Finally delete the user
                        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
                        $stmt->execute([$_POST['user_id']]);
                        
                        $message = "User deleted successfully!";
                    }
                } catch (PDOException $e) {
                    $error = "Error deleting user: " . $e->getMessage();
                }
                break;
        }
    }
}

// Get all users
try {
    $users = $pdo->query("
        SELECT user_id, name, email, role, balance, 
               (SELECT COUNT(*) FROM bookings WHERE user_id = users.user_id) as total_bookings
        FROM users 
        WHERE is_deleted = FALSE
        ORDER BY name
    ")->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching users: " . $e->getMessage();
}