<?php
// Include the necessary files
''
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
            <h1 class="display-4 fade-in">Live Train Tracking</h1>
            <p class="lead mx-auto fade-in delay-1">Get real-time updates on train locations, arrivals, and departures with our accurate tracking system.</p>
        </div>
    </section>

    <!-- Train Tracking Section -->
    <section class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Tracking Form Card -->
                <div class="tracking-form shadow">
                    <h3 class="tracking-heading">Track Your Train</h3>
                    <form id="trainTrackingForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="searchType" id="searchByNumber" value="number" checked>
                                    <label class="form-check-label" for="searchByNumber">
                                        Search by Train Number
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="searchType" id="searchByName" value="name">
                                    <label class="form-check-label" for="searchByName">
                                        Search by Train Name
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="trainInput" placeholder="Enter train number or name">
                                    <button class="btn btn-primary" type="submit" id="trackButton">
                                        <i class="bi bi-search me-1"></i> Track
                                    </button>
                                </div>
                                <div class="form-text">Examples: 12345, Rajdhani Express</div>
                            </div>
                        </div>
                    </form>
                    

                </div>
                
                <!-- Results Section (Initially Hidden) -->
                <div class="train-status d-none" id="trainStatusSection">
                    <!-- Train Info Card -->
                    <div class="card train-info-card">
                        <div class="card-body">
                            <div class="train-info-header">
                                <i class="bi bi-train-front-fill train-icon"></i>
                                <div class="train-details">
                                    <h5 id="trainName">12301 - Howrah Rajdhani Express</h5>
                                    <small id="trainRoute">New Delhi to Howrah</small>
                                    <div>
                                        <span class="train-status-badge train-status-ontime" id="trainStatusBadge">
                                            <i class="bi bi-check-circle-fill me-1"></i> On Time
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <small class="text-muted">Current Location</small>
                                        <div class="fw-bold" id="currentLocation">Patna Junction</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <small class="text-muted">Next Station</small>
                                        <div class="fw-bold" id="nextStation">Dhanbad Junction</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <small class="text-muted">Expected Arrival</small>
                                        <div class="fw-bold" id="expectedArrival">11:45 AM (in 35 mins)</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <small class="text-muted">Last Updated</small>
                                        <div class="fw-bold" id="lastUpdated">Today, 10:35 AM</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Train Journey Progress -->
                    <h5 class="mt-4 mb-3">Journey Progress</h5>
                    <div class="progress-track">
                        <div class="station-item">
                            <div class="station-marker passed"></div>
                            <div class="station-content">
                                <div class="station-name">New Delhi</div>
                                <div class="station-time">Departed: Yesterday, 5:30 PM</div>
                            </div>
                        </div>
                        
                        <div class="station-item">
                            <div class="station-marker passed"></div>
                            <div class="station-content">
                                <div class="station-name">Kanpur Central</div>
                                <div class="station-time">Departed: Yesterday, 10:45 PM</div>
                            </div>
                        </div>
                        
                        <div class="station-item">
                            <div class="station-marker passed"></div>
                            <div class="station-content">
                                <div class="station-name">Allahabad Junction</div>
                                <div class="station-time">Departed: Yesterday, 1:15 AM</div>
                            </div>
                        </div>
                        
                        <div class="station-item">
                            <div class="station-marker active"></div>
                            <div class="station-content">
                                <div class="station-name">Patna Junction</div>
                                <div class="station-time">Departed: Today, 10:20 AM</div>
                            </div>
                        </div>
                        
                        <div class="station-item">
                            <div class="station-marker"></div>
                            <div class="station-content">
                                <div class="station-name">Dhanbad Junction</div>
                                <div class="station-time">Expected: Today, 11:45 AM</div>
                            </div>
                        </div>
                        
                        <div class="station-item">
                            <div class="station-marker"></div>
                            <div class="station-content">
                                <div class="station-name">Asansol Junction</div>
                                <div class="station-time">Expected: Today, 1:15 PM</div>
                            </div>
                        </div>
                        
                        <div class="station-item">
                            <div class="station-marker"></div>
                            <div class="station-content">
                                <div class="station-name">Howrah Junction</div>
                                <div class="station-time">Arrival: Today, 3:55 PM</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Map View -->
                    <h5 class="mt-5 mb-3">Live Location</h5>
                    <div class="map-container">
                        <div class="map-placeholder">
                            <div class="text-center">
                                <i class="bi bi-map fs-1 mb-2"></i>
                                <p>Map view would display here.<br>Currently showing simulated data.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-outline-primary" id="refreshButton">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Status
                        </button>
                        <button class="btn btn-outline-secondary" id="shareButton">
                            <i class="bi bi-share me-1"></i> Share Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center text-lg-start bg-light text-muted mt-5">
        <?php include(PARTIALS_PATH . 'search_train_modal.php'); ?>
        
    </footer>



    <!-- Bootstrap JS with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Handle form submission
    document.getElementById('trainTrackingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get input values
        const searchType = document.querySelector('input[name="searchType"]:checked').value;
        const searchValue = document.getElementById('trainInput').value.trim();
        
        if (searchValue === '') {
            alert('Please enter a train number or name');
            return;
        }
        
        // Show loading state
        const trackButton = document.getElementById('trackButton');
        trackButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Tracking...';
        trackButton.disabled = true;
        
        // Make AJAX call to controller
        fetch('<?php echo BASE_URL; ?>/index.php?action=get_train_status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `searchType=${encodeURIComponent(searchType)}&searchValue=${encodeURIComponent(searchValue)}`
        })
        .then(response => response.json())
        .then(data => {
            // Reset button state
            trackButton.innerHTML = '<i class="bi bi-search me-1"></i> Track';
            trackButton.disabled = false;
            
            if (data.error) {
                alert(data.error);
                return;
            }
            
            updateTrainUI(data);
            
            document.getElementById('trainStatusSection').classList.remove('d-none');
            document.getElementById('recentSearches').classList.remove('d-none');
            
            document.getElementById('trainStatusSection').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        })
        .catch(error => {
            console.error('Error:', error);
            trackButton.innerHTML = '<i class="bi bi-search me-1"></i> Track';
            trackButton.disabled = false;
        });
    });

    function updateTrainUI(data) {
        document.getElementById('trainName').textContent = `${data.trainInfo.train_number} - ${data.trainInfo.name}`;
        document.getElementById('trainRoute').textContent = data.trainInfo.route;
        
        const statusBadge = document.getElementById('trainStatusBadge');
        statusBadge.className = `train-status-badge train-status-${data.trainStatus.status.toLowerCase().replace(' ', '')}`;
        statusBadge.innerHTML = `<i class="bi ${data.trainStatus.status === 'On Time' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} me-1"></i> ${data.trainStatus.status}`;
        
        document.getElementById('currentLocation').textContent = data.trainStatus.current_station;
        document.getElementById('nextStation').textContent = data.trainStatus.next_station;
        document.getElementById('expectedArrival').textContent = data.trainStatus.expected_arrival_formatted || 'N/A';
        document.getElementById('lastUpdated').textContent = data.trainStatus.last_updated_formatted || 'N/A';
        
        const progressTrack = document.querySelector('.progress-track');
        progressTrack.innerHTML = '';
        
        data.journeyProgress.forEach(station => {
            const stationItem = document.createElement('div');
            stationItem.className = 'station-item';
            
            stationItem.innerHTML = `
                <div class="station-marker ${station.status}"></div>
                <div class="station-content">
                    <div class="station-name">${station.name}</div>
                    <div class="station-time">${station.time}</div>
                </div>
            `;
            
            progressTrack.appendChild(stationItem);
        });
    }

    document.getElementById('refreshButton').addEventListener('click', function() {
        document.getElementById('trainTrackingForm').dispatchEvent(new Event('submit'));
    });
    
    document.getElementById('shareButton').addEventListener('click', function() {
        alert('Share functionality would be implemented here.');
    });
    
    document.querySelectorAll('.recent-search-item').forEach(item => {
        item.addEventListener('click', function() {
            const searchText = this.textContent.trim().replace('Recent: ', '');
            document.getElementById('trainInput').value = searchText;
            document.getElementById('trainTrackingForm').dispatchEvent(new Event('submit'));
        });
    });
</script>

<style>
        /* Train Tracking CSS */
    .tracking-form {
        background-color: white;
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .tracking-heading {
        margin-bottom: 1.5rem;
        font-weight: 600;
        color: #333;
    }

    .recent-search-item {
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .recent-search-item:hover {
        background-color: #e9ecef;
    }

    .search-history {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e9ecef;
    }

    .train-info-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 2rem;
    }

    .train-info-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .train-icon {
        font-size: 2rem;
        color: #0d6efd;
        margin-right: 1rem;
    }

    .train-details {
        flex-grow: 1;
    }

    .train-status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .train-status-ontime {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .train-status-delayed {
        background-color: #fff3cd;
        color: #856404;
    }

    .train-status-cancelled {
        background-color: #f8d7da;
        color: #842029;
    }

    .train-status-arrived {
        background-color: #cfe2ff;
        color: #084298;
    }

    .train-status-unknown {
        background-color: #e2e3e5;
        color: #41464b;
    }

    .progress-track {
        position: relative;
        padding-left: 1rem;
    }

    .progress-track::before {
        content: '';
        position: absolute;
        left: 0.6rem;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: #dee2e6;
        transform: translateX(-50%);
    }

    .station-item {
        display: flex;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .station-marker {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 50%;
        background-color: #dee2e6;
        margin-right: 1rem;
        position: relative;
        z-index: 1;
    }

    .station-marker.passed {
        background-color: #0d6efd;
    }

    .station-marker.active {
        background-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
    }

    .station-content {
        flex-grow: 1;
    }

    .station-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .station-time {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .map-container {
        height: 300px;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .map-placeholder {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    .amenity-badge {
        background-color: #e9ecef;
        border-radius: 50px;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
    }

    .train-amenities {
        border-top: 1px solid #e9ecef;
        padding-top: 1rem;
    }

    .copy-notification {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 12px 24px;
        border-radius: 4px;
        font-size: 14px;
        z-index: 9999;
        transition: transform 0.3s ease-in-out;
    }

    .copy-notification.show {
        transform: translateX(-50%) translateY(0);
    }

    /* Responsive styling */
    @media (max-width: 768px) {
        .tracking-form {
            padding: 1.5rem;
        }
        
        .train-info-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .train-icon {
            margin-bottom: 1rem;
        }
    }
</style>


</body>
</html>

