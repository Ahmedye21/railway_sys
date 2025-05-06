<?php
require_once '../paths.php';
require_once BACKEND_PATH . 'admin_dashboard.php';
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
    <link rel="stylesheet" href="index.css">
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
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <?php include(PARTIALS_PATH . 'admin_navbar.php'); ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include(PARTIALS_PATH . 'admin_sidebar.php'); ?>
            
            <!-- Main Content -->
            <div class="col-lg-10 p-4 admin-content">
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
                        <div class="card dashboard-stat bg-success text-white h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h3 class="fs-5">Revenue</h3>
                                        <h2 class="display-5 mb-0">₹98.5K</h2>
                                        <small>+8.3% from last month</small>
                                    </div>
                                    <i class="bi bi-currency-rupee fs-1 ms-auto"></i>
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
                                        <h2 class="display-5 mb-0">854</h2>
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
                
                <!-- Recent Bookings and Charts -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-8">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Recent Bookings</h5>
                                <button class="btn btn-sm btn-primary">View All</button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>User</th>
                                                <th>Train</th>
                                                <th>Route</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#BK78965</td>
                                                <td>Raj Kumar</td>
                                                <td>Rajdhani Express</td>
                                                <td>Delhi → Mumbai</td>
                                                <td>20 Jun 2025</td>
                                                <td><span class="badge bg-success">Confirmed</span></td>
                                                <td>₹1,450</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownAction1" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownAction1">
                                                            <li><a class="dropdown-item" href="#">View Details</a></li>
                                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                                            <li><a class="dropdown-item text-danger" href="#">Cancel</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#BK78964</td>
                                                <td>Priya Sharma</td>
                                                <td>Shatabdi Express</td>
                                                <td>Bangalore → Chennai</td>
                                                <td>21 Jun 2025</td>
                                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                                <td>₹850</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownAction2" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownAction2">
                                                            <li><a class="dropdown-item" href="#">View Details</a></li>
                                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                                            <li><a class="dropdown-item text-danger" href="#">Cancel</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#BK78963</td>
                                                <td>Amit Patel</td>
                                                <td>Duronto Express</td>
                                                <td>Kolkata → Hyderabad</td>
                                                <td>22 Jun 2025</td>
                                                <td><span class="badge bg-success">Confirmed</span></td>
                                                <td>₹1,820</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownAction3" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownAction3">
                                                            <li><a class="dropdown-item" href="#">View Details</a></li>
                                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                                            <li><a class="dropdown-item text-danger" href="#">Cancel</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#BK78962</td>
                                                <td>Neha Singh</td>
                                                <td>Jan Shatabdi</td>
                                                <td>Pune → Mumbai</td>
                                                <td>23 Jun 2025</td>
                                                <td><span class="badge bg-danger">Cancelled</span></td>
                                                <td>₹550</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownAction4" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownAction4">
                                                            <li><a class="dropdown-item" href="#">View Details</a></li>
                                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                                            <li><a class="dropdown-item text-success" href="#">Restore</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#BK78961</td>
                                                <td>Vikram Mehta</td>
                                                <td>Garib Rath</td>
                                                <td>Delhi → Ahmedabad</td>
                                                <td>24 Jun 2025</td>
                                                <td><span class="badge bg-success">Confirmed</span></td>
                                                <td>₹1,250</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownAction5" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownAction5">
                                                            <li><a class="dropdown-item" href="#">View Details</a></li>
                                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                                            <li><a class="dropdown-item text-danger" href="#">Cancel</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Booking Statistics</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="bookingChart" height="250"></canvas>
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
                                    <a href="admin_trains.php" class="btn btn-primary">
                                        <i class="bi bi-train-front me-2"></i> Add New Train
                                    </a>
                                    <a href="admin_schedules.php" class="btn btn-info text-white">
                                        <i class="bi bi-calendar-plus me-2"></i> Create Schedule
                                    </a>
                                    <a href="admin_users.php" class="btn btn-success">
                                        <i class="bi bi-person-plus me-2"></i> Add New User
                                    </a>
                                    <a href="admin_reports.php" class="btn btn-secondary">
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
                    
                    <div class="col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Top Performing Routes</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Delhi → Mumbai</h6>
                                                <small>Rajdhani Express</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success p-2">₹245,600</span>
                                                <div class="small text-success">+12%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Bangalore → Chennai</h6>
                                                <small>Shatabdi Express</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success p-2">₹183,200</span>
                                                <div class="small text-success">+8%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Kolkata → Delhi</h6>
                                                <small>Duronto Express</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success p-2">₹156,800</span>
                                                <div class="small text-success">+5%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Mumbai → Ahmedabad</h6>
                                                <small>Tejas Express</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-danger p-2">₹124,500</span>
                                                <div class="small text-danger">-3%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Chennai → Hyderabad</h6>
                                                <small>Charminar Express</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success p-2">₹98,700</span>
                                                <div class="small text-success">+2%</div>
                                            </div>
                                        </div>
                                    </div>
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