<?php
// This should be at the top of your search_results.php file
// The controller will handle all the data processing
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | RailConnect</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/index.css">
    <style>
        .trip-card {
            transition: all 0.3s ease;
        }
        .trip-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .train-route-visual {
            position: relative;
            padding-left: 30px;
            margin-bottom: 10px;
        }
        .train-route-visual::before {
            content: '';
            position: absolute;
            left: 14px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #dee2e6;
        }
        .station {
            position: relative;
            padding: 8px 0;
        }
        .station::before {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #dee2e6;
            left: -24px;
            top: 12px;
        }
        .station.departure::before {
            background-color: #198754;
            width: 14px;
            height: 14px;
        }
        .station.arrival::before {
            background-color: #dc3545;
            width: 14px;
            height: 14px;
        }
        .search-summary {
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .results-count {
            color: #6c757d;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand text-primary" href="<?php echo BASE_URL; ?>">
                <i class="bi bi-train-front-fill me-2"></i>RailConnect
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>time-tracking">Train Tracking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>schedule">Schedules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    <?php 
                        if(!isset($_SESSION['user_id'])) {
                            echo '
                                <a href="'.BASE_URL.'login">
                                    <button class="btn btn-outline-primary btn-auth">Log In</button>
                                </a>    
                                <a href="'.BASE_URL.'signup">
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
                                        <li><a class="dropdown-item" href="'.BASE_URL.'profile"><i class="bi bi-person me-2"></i>My Profile</a></li>
                                        <li><a class="dropdown-item" href="'.BASE_URL.'my-bookings"><i class="bi bi-ticket-perforated me-2"></i>My Bookings</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="'.BASE_URL.'logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                                    </ul>
                                </div>
                            ';
                        }
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search Results Section -->
    <div class="container my-5">
        <!-- Search Summary -->
        <div class="card mb-4 search-summary">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="mb-3">Your Search</h4>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="fs-5 fw-bold">
                                    <?php echo htmlspecialchars($departureStation); ?> <i class="bi bi-arrow-right mx-2"></i> <?php echo htmlspecialchars($arrivalStation); ?>
                                </div>
                                <div class="text-muted">
                                    <?php echo htmlspecialchars($formattedDepartureDate); ?> 
                                    <?php if ($tripType === 'roundTrip' && !empty($formattedReturnDate)): ?>
                                    <span class="mx-1">|</span> Return: <?php echo htmlspecialchars($formattedReturnDate); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#searchTicketsModal">
                                Modify Search
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="text-muted mb-1">Travel class</div>
                        <div class="fw-bold">
                            <?php echo ($travelClass === 'first') ? 'First Class' : 'Second Class'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Results Count -->
        <?php if (empty($errors)): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Available Trains</h2>
                <span class="results-count">
                    <?php echo count($availableTrains); ?> trains found
                </span>
            </div>

            <!-- No Results -->
            <?php if (empty($availableTrains)): ?>
                <div class="alert alert-info text-center p-5">
                    <i class="bi bi-info-circle-fill fs-1 d-block mb-3"></i>
                    <h4>No Trains Found</h4>
                    <p class="mb-4">We couldn't find any trains for your search criteria.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchTicketsModal">
                        Try A Different Search
                    </button>
                </div>
            <?php else: ?>
                <!-- Train Results -->
                <div class="row g-4">
                    <?php foreach ($availableTrains as $train): ?>
                        <div class="col-12">
                            <div class="card shadow-sm mb-4 trip-card">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Train Info -->
                                        <div class="col-md-3 border-end">
                                            <div class="d-flex flex-column h-100">
                                                <div class="mb-3">
                                                    <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($train['train_number']); ?></span>
                                                    <h5 class="mb-1"><?php echo htmlspecialchars($train['train_name']); ?></h5>
                                                    <div class="text-muted small">
                                                        <?php echo $train['intermediate_stops'] > 0 ? 
                                                            $train['intermediate_stops'] . ' intermediate stop'.($train['intermediate_stops'] > 1 ? 's' : '') : 
                                                            'Direct train'; ?>
                                                    </div>
                                                    <div class="text-muted small"><?php echo htmlspecialchars($train['route_name']); ?></div>
                                                </div>
                                                
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between text-muted mb-2">
                                                        <span>Travel time:</span>
                                                        <span class="fw-medium"><?php echo htmlspecialchars($train['travel_time']); ?> hrs</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Route/Time Info -->
                                        <div class="col-md-5 py-3">
                                            <div class="d-flex mb-3">
                                                <div class="me-4">
                                                    <div class="text-muted">Departure</div>
                                                    <div class="fs-5 fw-bold"><?php echo htmlspecialchars($train['departure_time']); ?></div>
                                                    <div><?php echo htmlspecialchars($train['departure_station']); ?></div>
                                                </div>
                                                <div class="px-3 flex-grow-1 text-center">
                                                    <div class="text-muted small"><?php echo htmlspecialchars($train['travel_time']); ?> hrs</div>
                                                    <i class="bi bi-arrow-right my-2 d-block"></i>
                                                    <div class="text-muted small"><?php echo count($train['stations']); ?> stations</div>
                                                </div>
                                                <div>
                                                    <div class="text-muted">Arrival</div>
                                                    <div class="fs-5 fw-bold"><?php echo htmlspecialchars($train['arrival_time']); ?></div>
                                                    <div><?php echo htmlspecialchars($train['arrival_station']); ?></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Route Visualization -->
                                            <div class="train-route-visual mt-3">
                                                <?php foreach ($train['stations'] as $station): ?>
                                                    <div class="station <?php 
                                                    if ($station === $train['departure_station']) echo 'departure';
                                                    if ($station === $train['arrival_station']) echo 'arrival';
                                                    ?>">
                                                        <?php echo htmlspecialchars($station); ?>
                                                        <?php if ($station === $train['departure_station']): ?>
                                                            <span class="text-success small ms-2">Departure</span>
                                                        <?php elseif ($station === $train['arrival_station']): ?>
                                                            <span class="text-danger small ms-2">Arrival</span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Price/Booking Info -->
                                        <div class="col-md-4">
                                            <div class="text-end h-100 d-flex flex-column justify-content-between py-3">
                                                <div>
                                                    <div class="mb-3">
                                                        <?php if ($travelClass === 'first'): ?>
                                                            <div class="text-muted small">First Class</div>
                                                            <div class="fs-3 fw-bold text-primary">₹<?php echo number_format($train['first_class_price'], 2); ?></div>
                                                        <?php else: ?>
                                                            <div class="text-muted small">Second Class</div>
                                                            <div class="fs-3 fw-bold text-primary">₹<?php echo number_format($train['second_class_price'], 2); ?></div>
                                                        <?php endif; ?>
                                                        <div class="text-muted small">per person</div>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <a href="<?php echo BASE_URL; ?>booking?train_id=<?php echo $train['train_id']; ?>&schedule_id=<?php echo $train['schedule_id']; ?>&departure=<?php echo urlencode($departureStation); ?>&arrival=<?php echo urlencode($arrivalStation); ?>&date=<?php echo urlencode($departureDate); ?>&class=<?php echo urlencode($travelClass); ?>&type=<?php echo urlencode($tripType); ?>&return_date=<?php echo urlencode($returnDate); ?>" class="btn btn-primary btn-lg w-100">
                                                        Select
                                                    </a>
                                                    
                                                    <div class="mt-2 d-flex justify-content-between text-muted small">
                                                        <?php if ($train['has_wifi']): ?>
                                                            <span><i class="bi bi-wifi me-1"></i> WiFi</span>
                                                        <?php endif; ?>
                                                        <?php if ($train['has_food']): ?>
                                                            <span><i class="bi bi-cup-hot me-1"></i> Meals</span>
                                                        <?php endif; ?>
                                                        <?php if ($train['has_power_outlets']): ?>
                                                            <span><i class="bi bi-outlet me-1"></i> Power</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Include the search modal again for modification -->
    <div class="modal fade" id="searchTicketsModal" tabindex="-1" aria-labelledby="searchTicketsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchTicketsModalLabel">Find Your Train</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo BASE_URL; ?>search/search" method="POST">
                        <input type="hidden" name="modify_search" value="1">
                        <!-- Pre-fill the form with previous search values -->
                        <div class="mb-4">
                            <label class="form-label">Trip Type</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tripType" id="oneWay" value="oneWay" <?php echo ($tripType === 'oneWay') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="oneWay">
                                        One Way
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tripType" id="roundTrip" value="roundTrip" <?php echo ($tripType === 'roundTrip') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="roundTrip">
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
                                    <input class="form-check-input" type="radio" name="travelClass" id="firstClass" value="first" <?php echo ($travelClass === 'first') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="firstClass">
                                        First Class
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="travelClass" id="secondClass" value="second" <?php echo ($travelClass === 'second') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="secondClass">
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
                                        <option value="<?php echo htmlspecialchars($station['name']); ?>" <?php echo ($station['name'] === $departureStation) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($station['name']); ?>
                                        </option>
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
                                        <option value="<?php echo htmlspecialchars($station['name']); ?>" <?php echo ($station['name'] === $arrivalStation) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($station['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>                        
                        <!-- Dates -->
                        <div class="row" id="dateSelectors">
                            <div class="col-md-6 mb-3">
                                <label for="departureDate" class="form-label">Departure Date</label>
                                <input type="date" class="form-control" name="departureDate" id="departureDate" value="<?php echo htmlspecialchars($departureDate); ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3" id="returnDateGroup" style="<?php echo ($tripType === 'roundTrip') ? '' : 'display: none;'; ?>">
                                <label for="returnDate" class="form-label">Return Date</label>
                                <input type="date" class="form-control" name="returnDate" id="returnDate" value="<?php echo htmlspecialchars($returnDate); ?>">
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

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3">RailConnect</h5>
                    <p>Your trusted partner for train travel across the country. Book tickets, check schedules, and track trains in real-time.</p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>" class="text-white text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>time-tracking" class="text-white text-decoration-none">Train Tracking</a></li>
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>schedule" class="text-white text-decoration-none">Schedules</a></li>
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>contact" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="mb-3">Support</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Help Center</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">FAQs</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Terms & Conditions</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-3">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> support@railconnect.com</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> +1 (555) 123-4567</li>
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> 123 Railway St, City, Country</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> RailConnect. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/hide return date based on trip type
        document.querySelectorAll('input[name="tripType"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const returnDateGroup = document.getElementById('returnDateGroup');
                if (this.value === 'roundTrip') {
                    returnDateGroup.style.display = 'block';
                    document.getElementById('returnDate').setAttribute('required', '');
                } else {
                    returnDateGroup.style.display = 'none';
                    document.getElementById('returnDate').removeAttribute('required');
                }
            });
        });

        // Swap departure and arrival stations
        document.getElementById('swapStations').addEventListener('click', function() {
            const departureSelect = document.getElementById('departureStation');
            const arrivalSelect = document.getElementById('arrivalStation');
            const tempValue = departureSelect.value;
            departureSelect.value = arrivalSelect.value;
            arrivalSelect.value = tempValue;
        });

        // Set minimum date for departure and return dates
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('departureDate').min = today;
        document.getElementById('returnDate').min = today;
    </script>
</body>
</html>