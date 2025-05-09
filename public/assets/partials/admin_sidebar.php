<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky">
        <style>
            /* Custom sidebar styling */
            #sidebar {
                position: fixed;
                height: 100vh;
                z-index: 100;
                width: 250px; /* Fixed width */
                transition: all 0.3s;
            }
            
            /* Adjust main content area to make room for sidebar */
            @media (min-width: 768px) {
                .main-content {
                    margin-left: 250px;
                    width: calc(100% - 250px);
                }
            }
            
            /* For mobile view */
            @media (max-width: 767.98px) {
                #sidebar {
                    width: 100%;
                    position: relative;
                    height: auto;
                }
                .main-content {
                    margin-left: 0;
                    width: 100%;
                }
            }
        </style>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/index.php?action=admin_dashboard">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_trains.php' ? 'active' : ''; ?>"  href="<?php echo BASE_URL; ?>/index.php?action=manage_trains">
                    <i class="bi bi-train-front me-2"></i>
                    Trains
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_routes.php' ? 'active' : ''; ?>" href="admin_routes.php">
                    <i class="bi bi-map me-2"></i>
                    Routes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_schedules.php' ? 'active' : ''; ?>" href="admin_schedules.php">
                    <i class="bi bi-calendar-check me-2"></i>
                    Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_stations.php' ? 'active' : ''; ?>" href="admin_stations.php">
                    <i class="bi bi-building me-2"></i>
                    Stations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_bookings.php' ? 'active' : ''; ?>" href="admin_bookings.php">
                    <i class="bi bi-ticket-perforated me-2"></i>
                    Bookings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_users.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/index.php?action=manage_users">
                    <i class="bi bi-people me-2"></i>
                    Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_reports.php' ? 'active' : ''; ?>" href="admin_reports.php">
                    <i class="bi bi-graph-up me-2"></i>
                    Reports
                </a>
            </li>
        </ul>
    </div>
</nav>