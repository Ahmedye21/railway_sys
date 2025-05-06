<?php
session_start();

if(isset($_SESSION['user_id'])) {
    if($_SESSION['user_type'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit;
}

include_once "User.php";

$error_msg = '';
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();

    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $user->user_type = 'user';
    
    if($_POST['password'] !== $_POST['confirm_password']) {
        $error_msg = "Passwords do not match.";
    } elseif(strlen($_POST['password']) < 6) {
        $error_msg = "Password must be at least 6 characters.";
    } elseif($user->signup()) {
        $success_msg = "Registration successful! You can now <a href='login.php'>login</a>.";
    } else {
        $error_msg = "Email already exists or there was an error during registration.";
    }
}
