<?php
    session_start();
    require_once '../paths.php';

    // Clear all session data
    session_unset();
    session_destroy();

    // Redirect to home page
    header('Location: ' . BASE_URL . 'views/index.php');
    exit();