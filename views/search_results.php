<?php
require_once '../paths.php';
require_once BACKEND_PATH . 'search_results.php';
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
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <?php include(PARTIALS_PATH . 'navbar.php'); ?>

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
                                    <?php echo $departureStation; ?> <i class="bi bi-arrow-right mx-2"></i> <?php echo $arrivalStation; ?>
                                </div>
                                <div class="text-muted">
                                    <?php echo $formattedDepartureDate; ?> 
                                    <?php if ($tripType === 'roundTrip' && !empty($formattedReturnDate)): ?>
                                    <span class="mx-1">|</span> Return: <?php echo $formattedReturnDate; ?>
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
                        <li><?php echo $error; ?></li>
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
                                                    <span class="badge bg-primary mb-2"><?php echo $train['train_number']; ?></span>
                                                    <h5 class="mb-1"><?php echo $train['train_name']; ?></h5>
                                                    <div class="text-muted small">
                                                        <?php echo $train['intermediate_stops'] > 0 ? 
                                                            $train['intermediate_stops'] . ' intermediate stop'.($train['intermediate_stops'] > 1 ? 's' : '') : 
                                                            'Direct train'; ?>
                                                    </div>
                                                    <div class="text-muted small"><?php echo $train['route_name']; ?></div>
                                                </div>
                                                
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between text-muted mb-2">
                                                        <span>Travel time:</span>
                                                        <span class="fw-medium"><?php echo $train['travel_time']; ?> hrs</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Route/Time Info -->
                                        <div class="col-md-5 py-3">
                                            <div class="d-flex mb-3">
                                                <div class="me-4">
                                                    <div class="text-muted">Departure</div>
                                                    <div class="fs-5 fw-bold"><?php echo $train['departure_time']; ?></div>
                                                    <div><?php echo $train['departure_station']; ?></div>
                                                </div>
                                                <div class="px-3 flex-grow-1 text-center">
                                                    <div class="text-muted small"><?php echo $train['travel_time']; ?> hrs</div>
                                                    <i class="bi bi-arrow-right my-2 d-block"></i>
                                                    <div class="text-muted small"><?php echo count($train['stations']); ?> stations</div>
                                                </div>
                                                <div>
                                                    <div class="text-muted">Arrival</div>
                                                    <div class="fs-5 fw-bold"><?php echo $train['arrival_time']; ?></div>
                                                    <div><?php echo $train['arrival_station']; ?></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Route Visualization -->
                                            <div class="train-route-visual mt-3">
                                                <?php foreach ($train['stations'] as $station): ?>
                                                    <div class="station <?php 
                                                    if ($station === $train['departure_station']) echo 'departure';
                                                    if ($station === $train['arrival_station']) echo 'arrival';
                                                    ?>">
                                                        <?php echo $station; ?>
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
                                                            <div class="fs-3 fw-bold text-primary">EGP<?php echo number_format($train['first_class_price'], 2); ?></div>
                                                        <?php else: ?>
                                                            <div class="text-muted small">Second Class</div>
                                                            <div class="fs-3 fw-bold text-primary">EGP<?php echo number_format($train['second_class_price'], 2); ?></div>
                                                        <?php endif; ?>
                                                        <div class="text-muted small">per person</div>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <a href="booking.php?train_id=<?php echo $train['train_id']; ?>&schedule_id=<?php echo $train['schedule_id']; ?>&departure=<?php echo urlencode($departureStation); ?>&arrival=<?php echo urlencode($arrivalStation); ?>&date=<?php echo urlencode($departureDate); ?>&class=<?php echo urlencode($travelClass); ?>&type=<?php echo urlencode($tripType); ?>&return_date=<?php echo urlencode($returnDate); ?>" class="btn btn-primary btn-lg w-100">
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

    <!-- Footer and Search Modal (include as before) -->
    <!-- ... -->
    <?php include(PARTIALS_PATH . 'search_train_modal.php'); ?>

</body>
</html>