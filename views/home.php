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


    <!-- Navigation Bar -->
    <?php 
    // Include header partial - add a forward slash
    include_once PARTIALS_PATH . 'header.php';
    ?>


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

    <!-- Notifications Banner -->
    <?php if (!empty($notifications)): ?>
    <section class="container mt-4">
        <div id="notificationCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($notifications as $index => $notification): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="alert alert-info d-flex align-items-center m-0">
                        <i class="bi bi-bell-fill me-2"></i>
                        <div>
                            <strong><?= htmlspecialchars($notification['title']) ?>:</strong> 
                            <?= htmlspecialchars($notification['message']) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="container mt-4 mb-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Egypt Railway Network Map</h3>
        </div>
        <div class="card-body text-center">
            <img src="/railway_sys/public/images/railway_map.png" alt="Egypt Railway Network Map" class="img-fluid">
        </div>
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
                        <li><a href="time-tracking.html"><i class="bi bi-clock me-2"></i>Train Tracking</a></li>
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
    <?php include(PARTIALS_PATH . 'search_train_modal.php'); ?>


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