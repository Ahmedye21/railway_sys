<?php
require_once '../paths.php';
require_once BACKEND_PATH . 'stations.php';

// You'll need to implement getTrainsByStation() in your backend
if (isset($_GET['station'])) {
    $stationName = $_GET['station'];
    $trains = getTrainsByStation($stationName);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Station Arrivals - RailConnect</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .search-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }
        .arrival-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .arrival-card:hover {
            transform: translateY(-5px);
        }
        .train-number {
            font-weight: 600;
            color: #dc3545;
        }
        .station-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 20px;
        }
        .status-on-time {
            color: #28a745;
        }
        .status-delayed {
            color: #ffc107;
        }
        .status-cancelled {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <?php include(PARTIALS_PATH . 'navbar.php'); ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="search-container">
                    <h2 class="text-center mb-4">Check Train Arrivals</h2>
                    <form method="GET" action="station_arrivals.php">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-lg" 
                                   name="station" placeholder="Enter station name" 
                                   value="<?php echo isset($stationName) ? htmlspecialchars($stationName) : ''; ?>" required>
                            <button class="btn btn-danger" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                
                <?php if (isset($stationName)): ?>
                <div class="mt-5">
                    <h3 class="station-name text-center">
                        <i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($stationName); ?>
                    </h3>
                    
                    <?php if (!empty($trains)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Train No.</th>
                                    <th>Train Name</th>
                                    <th>Origin</th>
                                    <th>Destination</th>
                                    <th>Scheduled Arrival</th>
                                    <th>Estimated Arrival</th>
                                    <th>Status</th>
                                    <th>Platform</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($trains as $train): ?>
                                <tr>
                                    <td class="train-number"><?php echo htmlspecialchars($train['number']); ?></td>
                                    <td><?php echo htmlspecialchars($train['name']); ?></td>
                                    <td><?php echo htmlspecialchars($train['origin']); ?></td>
                                    <td><?php echo htmlspecialchars($train['destination']); ?></td>
                                    <td><?php echo htmlspecialchars($train['scheduled_time']); ?></td>
                                    <td><?php echo htmlspecialchars($train['estimated_time']); ?></td>
                                    <td>
                                        <?php if ($train['status'] == 'On Time'): ?>
                                            <span class="status-on-time"><i class="bi bi-check-circle-fill"></i> On Time</span>
                                        <?php elseif ($train['status'] == 'Delayed'): ?>
                                            <span class="status-delayed"><i class="bi bi-exclamation-triangle-fill"></i> Delayed</span>
                                        <?php elseif ($train['status'] == 'Cancelled'): ?>
                                            <span class="status-cancelled"><i class="bi bi-x-circle-fill"></i> Cancelled</span>
                                        <?php else: ?>
                                            <?php echo htmlspecialchars($train['status']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($train['platform'] ?? 'TBD'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info text-center mt-4">
                        No trains found for this station.
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-refresh every 60 seconds
        setTimeout(function(){
            window.location.reload();
        }, 60000);
    </script>
</body>
</html>