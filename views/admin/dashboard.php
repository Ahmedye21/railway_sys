<?php
// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?action=login');
    exit;
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If the user is not an admin, redirect to the index page
    // You can also show an error message if needed
    header('Location: index.php');

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | RailConnect</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/assets/css/index.css">
<style>
        .dashboard-stat {
            border-radius: 10px;
            padding: 1.5rem;
            transition: all 0.3s;
        }
        .dashboard-stat:hover {
            transform: translateY(-5px);
        }
        .admin-sidebar {
            min-height: calc(100vh - 70px);
            background-color: #343a40;
        }
        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1rem;
            border-radius: 0;
            border-left: 3px solid transparent;
        }
        .admin-sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .admin-sidebar .nav-link.active {
            color: #fff;
            border-left-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.2);
        }
        .admin-content {
            min-height: calc(100vh - 70px);
            background-color: #f8f9fa;
        }
        .table-responsive {
            min-height: 300px;
        }
        .user-avatar.admin {
            background-color: #dc3545 !important;
        }

        .admin-user-dropdown {
        position: relative;
    }

    .user-profile-btn {
        background: transparent;
        border: none;
        color: white;
        padding: 0.5rem;
        transition: all 0.2s;
    }

    .user-profile-btn:hover, .user-profile-btn:focus {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .user-profile-btn::after {
        display: none;
    }

    .admin-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #dc3545;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .user-info {
        line-height: 1.2;
    }

    .user-name {
        
        font-weight: 500;
        font-size: 0.9rem;
    }

    .user-role {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .dropdown-menu {
        min-width: 220px;
        border: none;
        border-radius: 8px;
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        margin: 0.15rem 0.5rem;
        width: auto;
    }

    .dropdown-item:hover {
        background: #f8f9fa;
    }

    .dropdown-item i {
        width: 18px;
        text-align: center;
    }

</style>


</head>
<body>
    <!-- Navigation Bar -->
    <?php 
        // Include header partial - add a forward slash
        include_once PARTIALS_PATH . 'admin_navbar.php';
        ?>

<div class="container-fluid">
        <div class="row">
        <?php 
            // Include header partial - add a forward slash
            include_once PARTIALS_PATH . 'admin_sidebar.php';
            ?>                  
            <!-- Main Content -->
            <div class="col-lg-10 p-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">Admin Dashboard</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <span class="badge bg-primary p-2">
                            <?php echo date('D, d M Y'); ?>
                        </span>
                    </div>
                </div>
                
                <!-- Stats Row -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card dashboard-stat bg-primary text-white h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h3 class="fs-5">Total Bookings</h3>
                                        <h2 class="display-5 mb-0">1,254</h2>
                                        <small>+12% from last month</small>
                                    </div>
                                    <i class="bi bi-ticket-perforated-fill fs-1 ms-auto"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card dashboard-stat bg-info text-white h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h3 class="fs-5">Active Users</h3>
                                        <h2 class="display-5 mb-0"><?php echo $userCount; ?></h2>
                                        <small>+24 new today</small>
                                    </div>
                                    <i class="bi bi-people-fill fs-1 ms-auto"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card dashboard-stat bg-warning text-white h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h3 class="fs-5">Active Trains</h3>
                                        <h2 class="display-5 mb-0">42</h2>
                                        <small>3 delayed trains</small>
                                    </div>
                                    <i class="bi bi-train-front-fill fs-1 ms-auto"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                
                <!-- Quick Actions and System Alerts -->
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a  href="<?php echo BASE_URL; ?>/index.php?action=manage_trains" class="btn btn-primary">
                                        <i class="bi bi-train-front me-2"></i> Add New Train
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/index.php?action=manage_schedules" class="btn btn-info text-white">
                                        <i class="bi bi-calendar-plus me-2"></i> Create Schedule
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/index.php?action=manage_users" class="btn btn-success">
                                        <i class="bi bi-person-plus me-2"></i> Add New User
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/index.php?action=manage_reports" class="btn btn-secondary">
                                        <i class="bi bi-file-earmark-bar-graph me-2"></i> Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">System Alerts</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Train #12556 Delayed</h6>
                                            <small class="text-danger">30 mins ago</small>
                                        </div>
                                        <p class="mb-1">Duronto Express delayed by 45 minutes due to signal issue.</p>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Low Seat Availability</h6>
                                            <small class="text-warning">2 hours ago</small>
                                        </div>
                                        <p class="mb-1">Rajdhani Express (Delhi to Mumbai) has less than 10% seats left.</p>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">System Maintenance</h6>
                                            <small class="text-info">5 hours ago</small>
                                        </div>
                                        <p class="mb-1">Scheduled maintenance tonight from 02:00 AM to 04:00 AM.</p>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Database Backup Complete</h6>
                                            <small class="text-success">Yesterday</small>
                                        </div>
                                        <p class="mb-1">Weekly database backup completed successfully.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Setup booking statistics chart
        const ctx = document.getElementById('bookingChart').getContext('2d');
        const bookingChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Confirmed', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [65, 25, 10],
                    backgroundColor: [
                        '#198754',  // green - success
                        '#ffc107',  // yellow - warning
                        '#dc3545'   // red - danger
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>