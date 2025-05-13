<?php

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user is normal user (not admin)
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | RailConnect</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/index.css">
    <style>
        .dashboard-stat {
            border-radius: 10px;
            padding: 1.5rem;
            transition: all 0.3s;
        }
        .dashboard-stat:hover {
            transform: translateY(-5px);
        }
        .activity-item {
            border-left: 3px solid #3b71ca;
            padding-left: 1rem;
            margin-bottom: 1rem;
            position: relative;
        }
        .activity-item::before {
            content: "";
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #3b71ca;
            left: -7px;
            top: 5px;
        }
        .upcoming-trip {
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s;
        }
        .upcoming-trip:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            padding: 0.8rem 1rem;
            color: #444;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link.active {
            background-color: #3b71ca;
            color: white;
        }
        .sidebar .nav-link:hover {
            background-color: #f0f0f0;
        }
        .sidebar .nav-link.active:hover {
            background-color: #3b71ca;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <?php 
    // Include header partial - add a forward slash
    include_once PARTIALS_PATH . 'header.php';
    ?>

    <!-- Dashboard Content -->
    <div class="container mt-4 mb-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="p-4 text-center border-bottom">
                            <div class="d-inline-block rounded-circle bg-primary text-white p-3 mb-3" style="width: 80px; height: 80px; font-size: 2rem; line-height: 40px;">
                                <?php echo $firstLetter; ?>
                            </div>
                            <h5 class="mb-0"><?php echo $_SESSION['name']; ?></h5>
                            <p class="text-muted mb-0">Regular Traveler</p>
                            <div class="mt-3 d-flex justify-content-center">
                                <span class="badge bg-success p-2">
                                    <i class="bi bi-wallet2 me-1"></i>
                                    Balance: EGP  <?php echo number_format($_SESSION['balance'], 2); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="sidebar p-3">
                            <nav class="nav flex-column">
                                <a class="nav-link active mb-2" href="user_dashboard.php">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                                <a class="nav-link mb-2" href="my-bookings.php">
                                    <i class="bi bi-ticket-perforated me-2"></i> My Bookings
                                </a>
                                <a class="nav-link mb-2" href="#" data-bs-toggle="modal" data-bs-target="#searchTicketsModal">
                                    <i class="bi bi-search me-2"></i> Search Tickets
                                </a>
                                <a class="nav-link mb-2" href="<?php echo BASE_URL; ?>/index.php?action=funds">
                                    <i class="bi bi-wallet2 me-2"></i> Wallet
                                </a>
                                <a class="nav-link mb-2" href="profile.php">
                                    <i class="bi bi-person-circle me-2"></i> My Profile
                                </a>
                                <a class="nav-link" href="logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="mb-0">Welcome back, <?php echo explode(' ', $_SESSION['name'])[0]; ?>!</h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchTicketsModal">
                                <i class="bi bi-plus-lg me-2"></i> Book New Journey
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Row -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="dashboard-stat bg-primary bg-opacity-10 h-100">
                            <h3 class="fs-5 text-primary">Upcoming Trips</h3>
                            <div class="d-flex align-items-center">
                                <h2 class="display-4 mb-0 me-2">2</h2>
                                <i class="bi bi-calendar-check fs-1 text-primary ms-auto"></i>
                            </div>
                            <small>Next trip in <strong>3 days</strong></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-stat bg-success bg-opacity-10 h-100">
                            <h3 class="fs-5 text-success">Wallet Balance</h3>
                            <div class="d-flex align-items-center">
                                <h2 class="display-4 mb-0 me-2">EGP  <?php echo number_format($_SESSION['balance'], 0); ?></h2>
                                <i class="bi bi-wallet2 fs-1 text-success ms-auto"></i>
                            </div>
                            <small><a href="<?php echo BASE_URL; ?>/index.php?action=funds" class="text-success">Add funds</a></small>
                            </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-stat bg-info bg-opacity-10 h-100">
                            <h3 class="fs-5 text-info">Total Journeys</h3>
                            <div class="d-flex align-items-center">
                                <h2 class="display-4 mb-0 me-2">7</h2>
                                <i class="bi bi-train-front fs-1 text-info ms-auto"></i>
                            </div>
                            <small>Traveled <strong>1,423 km</strong> with us</small>
                        </div>
                    </div>
                </div>





<div class="row">
    <?php if (!empty($ticket)): ?>
        <?php foreach ($ticket as $t): ?>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary">Upcoming</span>
                                <span class="ms-2 text-muted">Booking ID: <?= htmlspecialchars($t['booking_id']) ?></span>
                            </div>
                            <div class="text-end">
                                <strong class="text-success"><?= htmlspecialchars($t['booking_status']) ?></strong>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p>Train: <?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['train_number']) ?>)</p>
                            <p>Route: <?= htmlspecialchars($t['from_station_name']) ?> to <?= htmlspecialchars($t['to_station_name']) ?></p>
                            <p>Travel Date: <?= htmlspecialchars($t['travel_date']) ?></p>
                            <p>Class: <?= htmlspecialchars($t['travel_class']) ?></p>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-3">
                            <!-- Cancel Button (opens modal) -->
                            <button class="btn btn-outline-danger me-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#cancelModal"
                                    data-booking-id="<?= $t['booking_id'] ?>">
                                <i class="fas fa-times-circle"></i> Cancel Ticket
                            </button>
                            
                            <!-- Print Button (download as PDF) -->
                            <a href="generate_pdf.php?booking_id=<?= $t['booking_id'] ?>" 
                               class="btn btn-outline-primary" 
                               target="_blank">
                                <i class="fas fa-file-pdf"></i> Download Ticket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">No upcoming trips found.</div>
        </div>
    <?php endif; ?>
</div>

<!-- Cancel Ticket Confirmation Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Cancellation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel ticket <strong id="modalBookingId"></strong>?</p>
                <p class="text-danger">Cancellation fees may apply.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmCancel">Yes, Cancel Ticket</button>
            </div>
        </div>
    </div>
</div>

                            



                <!-- Cancel Ticket Confirmation Modal -->
                <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Cancellation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to cancel this ticket?</p>
                                <p class="text-danger">Cancellation fees may apply.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-danger" id="confirmCancel">Yes, Cancel Ticket</button>
                            </div>
                        </div>
                    </div>
                </div>
                

            </div>
        </div>
    </div>

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
                        <li><a href=""><i class="bi bi-house-door me-2"></i>Home</a></li>
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
    <div class="modal fade" id="searchTicketsModal" tabindex="-1" aria-labelledby="searchTicketsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchTicketsModalLabel">Find Your Train</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="search_results.php" method="POST">
                        <!-- Trip Type Selection -->
                        <div class="mb-4">
                            <label class="form-label">Trip Type</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tripType" id="oneWay" value="oneWay" checked>
                                    <label class="form-check-label" for="oneWay">
                                        One Way
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tripType" id="roundTrip" value="roundTrip">
                                    <label class="form-check-label" for="roundTrip">
                                        Round Trip
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stations -->
                        <div class="row mb-3 align-items-end">
                            <div class="col">
                                <label for="departureStation" class="form-label">Departure</label>
                                <input type="text" class="form-control" name="departureStation" id="departureStation" placeholder="From" required>
                            </div>
                            
                            <div class="col-auto px-0">
                                <button type="button" class="btn btn-light swap-btn" id="swapStations">
                                    <i class="bi bi-arrow-left-right"></i>
                                </button>
                            </div>
                            
                            <div class="col">
                                <label for="arrivalStation" class="form-label">Arrival</label>
                                <input type="text" class="form-control" name="arrivalStation" id="arrivalStation" placeholder="To" required>
                            </div>
                        </div>
                        
                        <!-- Dates -->
                        <div class="row" id="dateSelectors">
                            <div class="col-md-6 mb-3">
                                <label for="departureDate" class="form-label">Departure Date</label>
                                <input type="date" class="form-control" name="departureDate" id="departureDate" required>
                            </div>
                            
                            <div class="col-md-6 mb-3" id="returnDateGroup" style="display: none;">
                                <label for="returnDate" class="form-label">Return Date</label>
                                <input type="date" class="form-control" name="returnDate" id="returnDate">
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-4">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set booking ID when modal opens
    $('#cancelModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var bookingId = button.data('booking-id'); // Extract booking ID
        $('#modalBookingId').text(bookingId);
        
        // Update confirm button to include booking ID
        $('#confirmCancel').off('click').on('click', function() {
            window.location.href = 'cancel_ticket.php?booking_id=' + bookingId;
        });
    });
});
</script>
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
    </script>

    <script>
// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get the modal element
    const cancelModal = document.getElementById('cancelModal');
    
    // Add event listener for when the modal is shown
    cancelModal.addEventListener('show.bs.modal', function(event) {
        // Get the button that triggered the modal
        const button = event.relatedTarget;
        
        // Extract booking ID from data-* attribute
        const bookingId = button.getAttribute('data-booking-id');
        
        // Update the modal's content
        document.getElementById('modalBookingId').textContent = bookingId;
        
        // Store the booking ID in the confirm button's data attribute
        document.getElementById('confirmCancel').setAttribute('data-booking-id', bookingId);
    });
    
    // Add event listener for the confirm cancel button
    document.getElementById('confirmCancel').addEventListener('click', function() {
        // Get the booking ID from the button's data attribute
        const bookingId = this.getAttribute('data-booking-id');
        
        // Create form data for the AJAX request
        const formData = new FormData();
        formData.append('booking_id', bookingId);
        
        // Send AJAX request to cancel the ticket
        fetch('index.php?action=cancel_ticket', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Close the modal
            const modal = bootstrap.Modal.getInstance(cancelModal);
            modal.hide();
            
            if (data.success) {
                // Show success message
                alert(data.message);
                
                // Reload the page to refresh the ticket list
                window.location.reload();
            } else {
                // Show error message
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while cancelling the ticket.');
        });
    });
});
</script>

</body>
</html>
