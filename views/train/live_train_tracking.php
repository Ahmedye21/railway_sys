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
                    
                    <!-- Recent Searches -->
                    <div class="search-history d-none" id="recentSearches">
                        <h6 class="mb-3">Recent Searches:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="recent-search-item">
                                <i class="bi bi-clock-history me-2"></i> 12565 - Bihar Sampark Kranti
                            </div>
                            <div class="recent-search-item">
                                <i class="bi bi-clock-history me-2"></i> 12301 - Howrah Rajdhani
                            </div>
                            <div class="recent-search-item">
                                <i class="bi bi-clock-history me-2"></i> 22691 - Rajdhani Express
                            </div>
                        </div>
                    </div>
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
            const trainInput = document.getElementById('trainInput').value.trim();
            
            if (trainInput === '') {
                alert('Please enter a train number or name');
                return;
            }
            
            // Show results section (in a real application, this would be after data fetch)
            document.getElementById('trainStatusSection').classList.remove('d-none');
            
            // Show recent searches
            document.getElementById('recentSearches').classList.remove('d-none');
            
            // Simulate data loading (would be replaced with real API call)
            const trackButton = document.getElementById('trackButton');
            trackButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Tracking...';
            trackButton.disabled = true;
            
            setTimeout(() => {
                trackButton.innerHTML = '<i class="bi bi-search me-1"></i> Track';
                trackButton.disabled = false;
                
                // Scroll to results
                document.getElementById('trainStatusSection').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // In a real application, you would update the UI with fetched data here
                updateTrainData(trainInput, searchType);
                
            }, 1500);
        });
        
        // Simulated data update function
        function updateTrainData(trainInput, searchType) {
            // This is a mock function that would be replaced with real data in production
            
            // For demo purposes, we're setting static data
            // In a real application, this data would come from your API
            
            // Parse input to determine if it's Rajdhani or something else
            let isRajdhani = trainInput.toLowerCase().includes('rajdhani');
            let trainData;
            
            if (isRajdhani) {
                trainData = {
                    number: '12301',
                    name: 'Howrah Rajdhani Express',
                    route: 'New Delhi to Howrah',
                    status: 'on-time',
                    currentLocation: 'Patna Junction',
                    nextStation: 'Dhanbad Junction',
                    expectedArrival: '11:45 AM (in 35 mins)',
                    lastUpdated: 'Today, 10:35 AM'
                };
            } else {
                trainData = {
                    number: trainInput.length <= 5 ? trainInput : '12565',
                    name: trainInput.length > 5 ? trainInput : 'Bihar Sampark Kranti',
                    route: 'New Delhi to Darbhanga',
                    status: 'delayed',
                    currentLocation: 'Lucknow Junction',
                    nextStation: 'Gorakhpur Junction',
                    expectedArrival: '1:15 PM (delayed by 25 mins)',
                    lastUpdated: 'Today, 10:40 AM'
                };
            }
            
            // Update UI elements
            document.getElementById('trainName').textContent = trainData.number + ' - ' + trainData.name;
            document.getElementById('trainRoute').textContent = trainData.route;
            document.getElementById('currentLocation').textContent = trainData.currentLocation;
            document.getElementById('nextStation').textContent = trainData.nextStation;
            document.getElementById('expectedArrival').textContent = trainData.expectedArrival;
            document.getElementById('lastUpdated').textContent = trainData.lastUpdated;
            
            // Update status badge
            const statusBadge = document.getElementById('trainStatusBadge');
            if (trainData.status === 'on-time') {
                statusBadge.className = 'train-status-badge train-status-ontime';
                statusBadge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> On Time';
            } else {
                statusBadge.className = 'train-status-badge train-status-delayed';
                statusBadge.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> Delayed';
            }
            
            // In a real application, you would also update the journey progress and map view
        }
        
        // Handle refresh button click
        document.getElementById('refreshButton').addEventListener('click', function() {
            const trainInput = document.getElementById('trainInput').value.trim();
            const searchType = document.querySelector('input[name="searchType"]:checked').value;
            
            // Show loading indicator
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Refreshing...';
            this.disabled = true;
            
            // Simulate refresh (would be replaced with real API call)
            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Refresh Status';
                this.disabled = false;
                
                // Update data
                updateTrainData(trainInput, searchType);
                
                // Show toast or notification
                alert('Train status updated successfully!');
            }, 1000);
        });
        
        // Handle share button click
        document.getElementById('shareButton').addEventListener('click', function() {
            // In a real application, this would open a share dialog or copy a link
            alert('Share functionality would be implemented here.');
        });
        
        // Handle recent search clicks
        document.querySelectorAll('.recent-search-item').forEach(item => {
            item.addEventListener('click', function() {
                const searchText = this.textContent.trim().replace('Recent: ', '');
                document.getElementById('trainInput').value = searchText;
                
                // Trigger form submission
                document.querySelector('#trainTrackingForm button[type="submit"]').click();
            });
        });
        
        function navigateTo(action) {
            window.location.href = "index.php?action=" + action;
        }
    </script>
    <style>
        /* Additional Styles for Train Tracking Page */
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 50px;
        }
        
        .tracking-form {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-top: -30px;
        }
        
        .tracking-heading {
            color: #182848;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .train-status {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin-top: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .progress-track {
            margin: 30px 0;
            position: relative;
        }
        
        .station-item {
            position: relative;
            padding-bottom: 50px;
        }
        
        .station-item:last-child {
            padding-bottom: 0;
        }
        
        .station-marker {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #e9ecef;
            border: 3px solid #dee2e6;
            position: absolute;
            left: 0;
            top: 0;
        }
        
        .station-marker.active {
            background-color: #198754;
            border-color: #12633a;
        }
        
        .station-marker.passed {
            background-color: #0d6efd;
            border-color: #0a58ca;
        }
        
        .station-content {
            margin-left: 35px;
            position: relative;
        }
        
        .station-content::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 20px;
            height: calc(100% - 15px);
            width: 2px;
            background-color: #dee2e6;
        }
        
        .station-item:last-child .station-content::before {
            display: none;
        }
        
        .station-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .station-time {
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .train-info-card {
            border-left: 5px solid #0d6efd;
            margin-bottom: 20px;
        }
        
        .train-info-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .train-icon {
            font-size: 2rem;
            color: #0d6efd;
            margin-right: 15px;
        }
        
        .train-details h5 {
            margin-bottom: 0;
            color: #0d6efd;
        }
        
        .train-status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 10px;
        }
        
        .train-status-ontime {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        
        .train-status-delayed {
            background-color: #fff3cd;
            color: #664d03;
        }
        
        .search-history {
            margin-top: 30px;
        }
        
        .recent-search-item {
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .recent-search-item:hover {
            background-color: #e9ecef;
        }
        
        .map-container {
            height: 300px;
            background-color: #e9ecef;
            border-radius: 10px;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .map-placeholder {
            height: 100%;
            width: 100%;
            background: linear-gradient(45deg, #e9ecef 25%, #f8f9fa 25%, #f8f9fa 50%, #e9ecef 50%, #e9ecef 75%, #f8f9fa 75%, #f8f9fa 100%);
            background-size: 20px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        
        footer {
            background-color: #182848;
            color: #f8f9fa;
            padding: 50px 0 20px;
            margin-top: 70px;
        }
        
        footer a {
            color: #f8f9fa;
            text-decoration: none;
        }
        
        footer a:hover {
            color: #0d6efd;
        }
        
        .footer-brand {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .social-icons {
            margin-top: 20px;
        }
        
        .social-icons a {
            display: inline-block;
            margin-right: 15px;
            font-size: 1.2rem;
        }
        
        footer h5 {
            color: white;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        footer ul li {
            margin-bottom: 10px;
        }
    </style>


</body>
</html>

