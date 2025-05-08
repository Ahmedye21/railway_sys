<!-- views/station/arrivals.php -->
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
    <?php 
    // Include header partial - add a forward slash
    include_once PARTIALS_PATH . '/header.php';
    ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-clock-history"></i> Station Arrivals</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo BASE_URL; ?>/index.php?action=station_arrivals">
                        <div class="input-group mb-4">
                            <input type="text" class="form-control form-control-lg" 
                                   name="station" placeholder="Enter station name" 
                                   value="<?php echo htmlspecialchars($stationName ?? ''); ?>"
                                   list="stationsList" required>
                            <datalist id="stationsList">
                                <?php foreach ($allStations as $station): ?>
                                    <option value="<?php echo htmlspecialchars($station['name']); ?>">
                                <?php endforeach; ?>
                            </datalist>
                            <button class="btn btn-danger" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($stationName)): ?>
                        <h3 class="text-center mb-4">
                            <i class="bi bi-geo-alt-fill"></i> 
                            <?php echo htmlspecialchars($stationName); ?>
                        </h3>
                        
                        <?php if (!empty($trains)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
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
                                            <td><?php echo htmlspecialchars($train['train_number']); ?></td>
                                            <td><?php echo htmlspecialchars($train['train_name']); ?></td>
                                            <td><?php echo htmlspecialchars($train['origin']); ?></td>
                                            <td><?php echo htmlspecialchars($train['destination']); ?></td>
                                            <td><?php echo htmlspecialchars($train['scheduled_arrival']); ?></td>
                                            <td><?php echo htmlspecialchars($train['estimated_arrival']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $train['status'] == 'On Time' ? 'success' : 
                                                         ($train['status'] == 'Delayed' ? 'warning' : 'danger');
                                                ?>">
                                                    <?php echo htmlspecialchars($train['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($train['platform'] ?? '--'); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center py-4">
                                <i class="bi bi-info-circle-fill fs-4"></i>
                                <h4>No Trains Found</h4>
                                <p>There are no trains scheduled to arrive at this station.</p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../public/assets/js/index.js"></script>
</body>
</html>
