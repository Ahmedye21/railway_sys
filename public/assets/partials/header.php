<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand text-primary"href="<?php echo BASE_URL; ?>/index.php?action=home">
                <i class="bi bi-train-front-fill me-2"></i>RailConnect
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/index.php?action=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  href="<?php echo BASE_URL; ?>/index.php?action=train_tracking">Train Tracking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/index.php?action=arrivals">Schedules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.html">Contact</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    <?php 
                        if (!isset($_SESSION['user_id'])) {
                            echo '
                                <button class="btn btn-outline-primary btn-auth" onclick="navigateTo(\'login\')">Log In</button>
                                <button class="btn btn-primary btn-auth" onclick="navigateTo(\'signup\')">Sign Up</button>
                                <script>
                                function navigateTo(action) {
                                    window.location.href = "index.php?action=" + action;
                                }
                                </script>
                            ';
                        } else {

                            $firstLetter = substr($_SESSION['name'], 0, 1);
                            
                            echo '
                                <div class="dropdown">
                                    <div class="user-account dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="user-avatar">' . $firstLetter . '</div>
                                        <div class="user-info">
                                            <span class="user-name">' . $_SESSION['name'] . '</span>
                                            <span class="user-balance">EGP ' . number_format($_SESSION['balance'], 2) . '</span>
                                        </div>
                                    </div>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <li><a class="dropdown-item" href="index.php?action=profile"><i class="bi bi-person me-2"></i>My Profile</a></li>
                                        <li><a class="dropdown-item" href="index.php?action=my_bookings"><i class="bi bi-ticket-perforated me-2"></i>My Bookings</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="'.BASE_URL.'?action=logout" "><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                                    </ul>
                                </div>

                            ';
                        }
                    ?>
                </div>
            </div>
        </div>
    </nav>