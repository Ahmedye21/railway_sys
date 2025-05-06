<?php
session_start();

if(isset($_SESSION['user_id'])) {
    if($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } elseif($_SESSION['role'] == 'station_master') {
        header("Location: stationmaster_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit;
}

include_once "User.php";

$error_msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    
    
    if($user->login()) {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['name'] = $user->name;
        $_SESSION['role'] = $user->role;
        $_SESSION['balance'] = $user->balance;
        
        if($user->role == 'admin') {
            header("Location: admin_dashboard.php");
        } elseif($_SESSION['role'] == 'station_master') {
            header("Location: stationmaster_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        $error_msg = "Invalid email or password.";
    }
}