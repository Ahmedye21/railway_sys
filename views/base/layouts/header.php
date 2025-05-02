<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'RailConnect - Smart Railway Ticket System'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/index.css">
    <?php echo $additionalCSS ?? ''; ?>
</head>
<body>
    <!-- Back to top button -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand text-primary" href="/">
                <i class="bi bi-train-front-fill me-2"></i>RailConnect
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage === 'home') ? 'active' : ''; ?>" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage === 'tracking') ? 'active' : ''; ?>" href="/time-tracking">Train Tracking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage === 'schedule') ? 'active' : ''; ?>" href="/schedule">Schedules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage === 'contact') ? 'active' : ''; ?>" href="/contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    <?php 
                        if(!isset($_SESSION['user_id'])) {
                            echo '
                                <a href="/login">
                                    <button class="btn btn-outline-primary btn-auth">Log In</button>
                                </a>    
                                <a href="/signup">
                                    <button class="btn btn-primary btn-auth">Sign Up</button>
                                </a>
                            ';
                        } else {
                            // Get first letter of user's name for avatar
                            $firstLetter = substr($_SESSION['name'], 0, 1);
                            
                            echo '
                                <div class="dropdown">
                                    <div class="user-account dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="user-avatar">' . $firstLetter . '</div>
                                        <div class="user-info">
                                            <span class="user-name">' . $_SESSION['name'] . '</span>
                                            <span class="user-balance">₹' . number_format($_SESSION['balance'], 2) . '</span>
                                        </div>
                                    </div>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>My Profile</a></li>
                                        <li><a class="dropdown-item" href="/my-bookings"><i class="bi bi-ticket-perforated me-2"></i>My Bookings</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                                    </ul>
                                </div>
                            ';
                        }
                    ?>
                </div>
            </div>
        </div>
    </nav>