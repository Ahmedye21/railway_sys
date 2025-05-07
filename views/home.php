<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RailConnect - Smart Railway Ticket System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/assets/css/index.css">
</head>
<body>
    <!-- Back to top button -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand text-primary" href="#">
                <i class="bi bi-train-front-fill me-2"></i>RailConnect
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="time-tracking.html">Train Tracking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="schedule.html">Schedules</a>
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
                                            <span class="user-balance">₹' . number_format($_SESSION['balance'], 2) . '</span>
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

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fade-in">Travel Smart with RailConnect</h1>
            <p class="lead mx-auto fade-in delay-1">Book your train tickets easily, track real-time arrivals, and enjoy a seamless journey experience with our advanced railway management system.</p>
            <button class="btn btn-danger hero-cta fade-in delay-2" data-bs-toggle="modal" data-bs-target="#searchTicketsModal">
                <i class="bi bi-search me-2"></i>Search for Tickets
            </button>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section container mb-5">
        <h2 class="text-center section-title">Our Services</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-clock-history feature-icon"></i>
                        <h4 class="card-title">Real-time Tracking</h4>
                        <p class="card-text">Track your train's location and expected arrival time with our advanced real-time monitoring system.</p>
                        <a href="time-tracking.html" class="btn btn-outline-primary mt-3">Track Now</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-calendar-week feature-icon"></i>
                        <h4 class="card-title">Train Schedules</h4>
                        <p class="card-text">Access comprehensive timetables and plan your journey ahead with accurate predictions.</p>
                        <a href="schedule.html" class="btn btn-outline-primary mt-3">View Schedule</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card feature-card shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-ticket-perforated feature-icon"></i>
                        <h4 class="card-title">Easy Booking</h4>
                        <p class="card-text">Book tickets in just a few clicks with our streamlined and secure booking process.</p>
                        <button class="btn btn-outline-primary mt-3" data-bs-toggle="modal" data-bs-target="#searchTicketsModal">Book Now</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Container -->
    <section class="search-container text-center">
        <div class="container">
            <h2 class="mb-4">Ready to Plan Your Journey?</h2>
            <p class="lead mb-4">Find the perfect train ticket for your next adventure</p>
            <button class="btn btn-danger search-btn shadow" data-bs-toggle="modal" data-bs-target="#searchTicketsModal">
                <i class="bi bi-search me-2"></i>Search for Tickets
            </button>
        </div>
    </section>
    
    <!-- Benefits Section -->
    <section class="benefits-section">
        <div class="container">
            <h2 class="text-center section-title">Why Choose RailConnect</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="benefit-item text-center">
                        <i class="bi bi-lightning-charge-fill benefit-icon"></i>
                        <h5>Fast Booking</h5>
                        <p>Book your tickets in under 2 minutes with our streamlined process</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="benefit-item text-center">
                        <i class="bi bi-shield-check benefit-icon"></i>
                        <h5>Secure Payments</h5>
                        <p>Your payment information is protected with bank-level security</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="benefit-item text-center">
                        <i class="bi bi-graph-up-arrow benefit-icon"></i>
                        <h5>Live Updates</h5>
                        <p>Receive real-time notifications about your journey</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="benefit-item text-center">
                        <i class="bi bi-headset benefit-icon"></i>
                        <h5>24/7 Support</h5>
                        <p>Our customer service team is always available to help</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    


    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <span class="footer-brand text-white">
                        <i class="bi bi-train-front-fill me-2"></i>RailConnect
                    </span>
                    <p>Your trusted partner for safe, reliable, and comfortable railway journeys across the country.</p>
                    <div class="social-icons">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="bi bi-house-door me-2"></i>Home</a></li>
                        <li><a href="live_train_tracking.php"><i class="bi bi-clock me-2"></i>Train Tracking</a></li>
                        <li><a href="schedule.html"><i class="bi bi-calendar me-2"></i>Schedules</a></li>
                        <li><a href="contact.html"><i class="bi bi-chat me-2"></i>Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5>Services</h5>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="bi bi-ticket me-2"></i>Ticket Booking</a></li>
                        <li><a href="#"><i class="bi bi-person-check me-2"></i>Seat Reservation</a></li>
                        <li><a href="#"><i class="bi bi-people me-2"></i>Group Bookings</a></li>
                        <li><a href="#"><i class="bi bi-gift me-2"></i>Vacation Packages</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt me-2"></i>123 Railway Street, City</li>
                        <li><i class="bi bi-telephone me-2"></i>(123) 456-7890</li>
                        <li><i class="bi bi-envelope me-2"></i>info@railconnect.com</li>
                        <li><i class="bi bi-clock me-2"></i>Mon-Sat: 9:00 AM - 6:00 PM</li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 bg-secondary">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2025 RailConnect. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="me-3">Privacy Policy</a>
                    <a href="#" class="me-3">Terms of Service</a>
                    <a href="#">FAQ</a>
                    
                </div>
            </div>
        </div>
    </footer>

    <!-- Search Modal -->
    <div class="modal fade" id="searchTicketsModal" tabindex="-1" aria-labelledby="searchTicketsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchTicketsModalLabel">Find Your Train</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> 
                    <form action="<?php echo BASE_URL; ?>?action=search_trains" method="POST">
                        
                        <!-- Trip Type -->
                        <div class="mb-4">
                            <label class="form-label">Trip Type</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tripType" id="oneWay" value="oneWay" checked
                                        aria-labelledby="oneWayLabel">
                                    <label class="form-check-label" id="oneWayLabel" for="oneWay">
                                        One Way
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tripType" id="roundTrip" value="roundTrip"
                                        aria-labelledby="roundTripLabel">
                                    <label class="form-check-label" id="roundTripLabel" for="roundTrip">
                                        Round Trip
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Class Selection -->
                        <div class="mb-4">
                            <label class="form-label">Travel Class</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="travelClass" id="firstClass" value="first"
                                        aria-labelledby="firstClassLabel">
                                    <label class="form-check-label" id="firstClassLabel" for="firstClass">
                                        First Class
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="travelClass" id="secondClass" value="second" checked
                                        aria-labelledby="secondClassLabel">
                                    <label class="form-check-label" id="secondClassLabel" for="secondClass">
                                        Second Class
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stations -->
                        <div class="row mb-3 align-items-end">
                            <div class="col">
                                <label for="departureStation" class="form-label">Departure</label>
                                <select class="form-select" name="departureStation" id="departureStation" required>
                                    <option value="">Select station</option>
                                    <?php foreach ($stations as $station): ?>
                                        <option value="<?php echo htmlspecialchars($station['name']); ?>"><?php echo htmlspecialchars($station['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-auto px-0">
                                <button type="button" class="btn btn-light swap-btn" id="swapStations">
                                    <i class="bi bi-arrow-left-right"></i>
                                </button>
                            </div>
                            
                            <div class="col">
                                <label for="arrivalStation" class="form-label">Arrival</label>
                                <select class="form-select" name="arrivalStation" id="arrivalStation" required>
                                    <option value="">Select station</option>
                                    <?php foreach ($stations as $station): ?>
                                        <option value="<?php echo htmlspecialchars($station['name']); ?>"><?php echo htmlspecialchars($station['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>  
                        
                        <!-- Dates -->
                        <div class="row" id="dateSelectors">
                            <div class="col-md-6 mb-3">
                                <label for="departureDate" class="form-label">Departure Date</label>
                                <input type="date" class="form-control" name="departureDate" id="departureDate" 
                                    min="<?php echo date('Y-m-d'); ?>" 
                                    value="<?php echo date('Y-m-d'); ?>" 
                                    required>
                            </div>
                            
                            <div class="col-md-6 mb-3" id="returnDateGroup" style="display: none;">
                                <label for="returnDate" class="form-label">Return Date</label>
                                <input type="date" class="form-control" name="returnDate" id="returnDate">
                            </div>
                        </div>
                        
                        <div id="formErrors" class="alert alert-danger d-none mb-3"></div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-4" id="searchButton">
                                <span class="submit-text">Search</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('departureDate').min = today;
        document.getElementById('returnDate').min = today;
        
        // Trip Type Toggle
        const oneWayRadio = document.getElementById('oneWay');
        const roundTripRadio = document.getElementById('roundTrip');
        const returnDateGroup = document.getElementById('returnDateGroup');
        
        oneWayRadio.addEventListener('change', () => {
            returnDateGroup.style.display = 'none';
        });
        
        roundTripRadio.addEventListener('change', () => {
            returnDateGroup.style.display = 'block';
        });
        
        // Swap Stations Functionality
        const swapBtn = document.getElementById('swapStations');
        const departureInput = document.getElementById('departureStation');
        const arrivalInput = document.getElementById('arrivalStation');
        
        swapBtn.addEventListener('click', () => {
            const temp = departureInput.value;
            departureInput.value = arrivalInput.value;
            arrivalInput.value = temp;
        });


        document.getElementById('searchButton').addEventListener('click', function(e) {
        const form = document.querySelector('#searchTicketsModal form');
        const errorDiv = document.getElementById('formErrors');
        
        // Clear previous errors
        errorDiv.classList.add('d-none');
        errorDiv.innerHTML = '';
        
        // Validate stations
        if (form.departureStation.value === form.arrivalStation.value) {
            e.preventDefault();
            errorDiv.classList.remove('d-none');
            errorDiv.innerHTML = 'Departure and arrival stations cannot be the same';
            return;
        }
        
        // Validate return date for round trips
        if (form.tripType.value === 'roundTrip' && !form.returnDate.value) {
            e.preventDefault();
            errorDiv.classList.remove('d-none');
            errorDiv.innerHTML = 'Return date is required for round trips';
            return;
        }
        
        // Show loading spinner
        this.querySelector('.submit-text').textContent = 'Searching...';
        this.querySelector('.spinner-border').classList.remove('d-none');
    });

    </script>
</body>
</html>