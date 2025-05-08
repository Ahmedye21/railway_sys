<!-- views/train/search_results.php -->
<?php include(PARTIALS_PATH . 'navbar.php'); ?>

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

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (empty($errors)): ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Available Trains</h2>
            <span class="results-count">
                <?php echo count($availableTrains); ?> trains found
            </span>
        </div>

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
            <div class="row g-4">
                <?php foreach ($availableTrains as $train): ?>
                    <!-- Train card HTML remains the same -->
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include(PARTIALS_PATH . 'search_train_modal.php'); ?>

