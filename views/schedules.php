<?php
/* 
 * File: views/schedules.php
 * Train Arrivals View
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Arrivals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/assets/css/index.css">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--dark-text);
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }
        
        .container {
            max-width: 1200px;
        }
        
        .page-header {
            background-color: var(--primary-color);
            color: white;
            padding: 30px 0;
            margin-bottom: 40px;
            border-radius: 0 0 var(--radius) var(--radius);
            box-shadow: var(--shadow);
        }
        
        .station-select-container {
            background: linear-gradient(to right, #ffffff, #f0f8ff);
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            border-left: 5px solid var(--secondary-color);
        }
        
        select.form-select {
            padding: 12px 20px;
            border-radius: var(--radius);
            border: 1px solid #d1d9e6;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        
        select.form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            padding: 12px 24px;
            border-radius: var(--radius);
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(52, 152, 219, 0.2);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(52, 152, 219, 0.3);
        }
        
        .card {
            border: none;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: var(--secondary-color);
            padding: 15px 20px;
            border-bottom: none;
        }
        
        .train-table {
            box-shadow: none;
            border-radius: 0;
        }
        
        .train-table th {
            background-color: #edf2f7;
            color: var(--dark-text);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 15px;
            border-top: none;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .train-table tbody tr {
            border-bottom: 1px solid #edf2f7;
            transition: all 0.2s ease;
        }
        
        .train-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
            transform: scale(1.005);
        }
        
        .train-table td {
            padding: 15px;
            vertical-align: middle;
        }
        
        .train-number {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        
        .train-name {
            color: #7f8c8d;
            font-size: 0.85rem;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        
        .status-on-time {
            background-color: rgba(46, 204, 113, 0.15);
            color: #27ae60;
        }
        
        .status-delayed {
            background-color: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }
        
        .countdown {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--primary-color);
        }
        
        .no-trains {
            padding: 60px 20px;
            text-align: center;
            color: #7f8c8d;
        }
        
        .no-trains i {
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .back-btn {
            display: inline-block;
            padding: 10px 24px;
            background-color: white;
            color: var(--primary-color);
            border: 2px solid #e2e8f0;
            border-radius: var(--radius);
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-top: 20px;
        }
        
        .back-btn:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .welcome-container {
            background: linear-gradient(135deg, #fff, #f5f7fa);
            border-radius: var(--radius);
            padding: 50px 30px;
            box-shadow: var(--shadow);
            text-align: center;
            margin: 50px auto;
            max-width: 700px;
        }
        
        .welcome-icon {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .refresh-note {
            display: inline-block;
            background-color: #f1f8ff;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--secondary-color);
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .station-select-container {
                padding: 20px;
            }
            
            .card-header h2 {
                font-size: 1.2rem;
            }
            
            .status-badge {
                padding: 4px 8px;
                font-size: 0.7rem;
            }
        }
    </style>
</head>

<?php
// Include header partial
include_once PARTIALS_PATH . '/header.php';
?>
<body>
    <div class="container my-5">
        <h1 class="mb-4 text-center">
            <i class="fas fa-train me-2"></i>Train Arrivals
        </h1>
        
        <!-- Station Selection Form -->
        <div class="station-select-container">
            <form method="GET" action="<?php echo BASE_URL; ?>/index.php" class="row g-3 align-items-end">
                <input type="hidden" name="action" value="arrivals">
                <div class="col-md-10">
                    <label for="station" class="form-label">Select Station</label>
                    <select name="station" id="station" class="form-select form-select-lg">
                        <option value="">-- Select a station --</option>
                        <?php foreach ($allStations as $station): ?>
                            <option value="<?= htmlspecialchars($station['name']) ?>" 
                                    <?= $stationName === $station['name'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($station['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search me-2"></i>View
                    </button>
                </div>
            </form>
        </div>
        
        <?php if (!empty($stationName)): ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Arrivals at <?= htmlspecialchars($stationName) ?>
                    </h2>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($trains)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 train-table">
                                <thead>
                                    <tr>
                                        <th>Train</th>
                                        <th>Origin</th>
                                        <th>Destination</th>
                                        <th>Scheduled Arrival</th>
                                        <th>Status</th>
                                        <th>Estimated Arrival</th>
                                        <th>Arriving In</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($trains as $train): ?>
                                        <?php 
                                            $isDelayed = isset($train['delay_minutes']) && $train['delay_minutes'] > 0;
                                            $statusClass = $isDelayed ? 'status-delayed' : 'status-on-time';
                                            $statusText = $isDelayed ? 'Delayed' : 'On Time';
                                            
                                            // Calculate time remaining
                                            $arrivalTime = $isDelayed && !empty($train['estimated_arrival']) ? 
                                                strtotime($train['estimated_arrival']) : 
                                                strtotime($train['scheduled_arrival']);
                                            $now = time();
                                            $remainingSeconds = $arrivalTime - $now;
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($train['train_number']) ?></strong><br>
                                                <small><?= htmlspecialchars($train['train_name']) ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($train['origin']) ?></td>
                                            <td><?= htmlspecialchars($train['destination']) ?></td>
                                            <td><?= htmlspecialchars(date('H:i', strtotime($train['scheduled_arrival']))) ?></td>
                                            <td>
                                                <span class="status-badge <?= $statusClass ?>">
                                                    <?= $statusText ?>
                                                    <?= $isDelayed ? ' (+' . htmlspecialchars($train['delay_minutes']) . ' min)' : '' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($isDelayed && !empty($train['estimated_arrival'])): ?>
                                                    <span class="delayed">
                                                        <?= htmlspecialchars(date('H:i', strtotime($train['estimated_arrival']))) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="on-time">
                                                        <?= htmlspecialchars(date('H:i', strtotime($train['scheduled_arrival']))) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="countdown">
                                                <?php if ($remainingSeconds > 0): ?>
                                                    <span data-countdown="<?= $arrivalTime ?>">
                                                        <?= floor($remainingSeconds / 3600) ?>h 
                                                        <?= floor(($remainingSeconds % 3600) / 60) ?>m
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-danger">Arrived/Departed</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-trains">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <h3>No trains scheduled</h3>
                            <p class="text-muted">There are no trains currently scheduled to arrive at this station.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Auto-refresh note -->
            <div class="text-center text-muted">
                <small><i class="fas fa-sync-alt me-1"></i> Data updates automatically every minute</small>
            </div>
            
            <!-- Back to Home button -->
            <div class="text-center mt-3">
                <a href="<?php echo BASE_URL; ?>/index.php?action=home" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-1"></i> Back to Home
                </a>
            </div>
        <?php else: ?>
            <div class="text-center my-5 py-5">
                <div class="display-1 text-muted mb-4">
                    <i class="fas fa-train"></i>
                </div>
                <h2>Welcome to Train Arrivals</h2>
                <p class="lead">Please select a station from the dropdown above to view train arrivals.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to update countdown timers
        function updateCountdowns() {
            const now = Math.floor(Date.now() / 1000);
            const countdowns = document.querySelectorAll('[data-countdown]');
            
            countdowns.forEach(element => {
                const arrivalTime = parseInt(element.getAttribute('data-countdown'));
                const remainingSeconds = arrivalTime - now;
                
                if (remainingSeconds > 0) {
                    const hours = Math.floor(remainingSeconds / 3600);
                    const minutes = Math.floor((remainingSeconds % 3600) / 60);
                    element.textContent = `${hours}h ${minutes}m`;
                } else {
                    element.textContent = 'Arrived/Departed';
                    element.classList.add('text-danger');
                }
            });
        }
        
        // Auto-refresh countdown times
        setInterval(updateCountdowns, 60000); // Update every minute
        
        // Update immediately on page load
        document.addEventListener('DOMContentLoaded', updateCountdowns);
        
        // Auto-submit form when station selection changes
        // Make sure the form submission preserves the action parameter
document.getElementById('station').addEventListener('change', function() {
    if (this.value) {
        this.form.submit();
    }
});
    </script>
</body>
</html>