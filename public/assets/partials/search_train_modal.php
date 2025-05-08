<!-- Search Modal -->
<div class="modal fade" id="searchTicketsModal" tabindex="-1" aria-labelledby="searchTicketsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchTicketsModalLabel">Find Your Train</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php?action=search_trains" method="POST">
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
                        
                        <!-- Class Selection -->
                        <div class="mb-4">
                            <label class="form-label">Travel Class</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="travelClass" id="firstClass" value="first">
                                    <label class="form-check-label" for="firstClass">
                                        First Class
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="travelClass" id="secondClass" value="second" checked>
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