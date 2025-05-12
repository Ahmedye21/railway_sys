<!-- views/station/arrivals.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Arrivals - <?php echo htmlspecialchars($stationName); ?></title>
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
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .station-selector {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        select, button {
            padding: 8px 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #3498db;
            color: white;
            cursor: pointer;
            margin-left: 10px;
        }
        button:hover {
            background-color: #2980b9;
        }
        .trains-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .trains-table th, .trains-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .trains-table th {
            background-color: #3498db;
            color: white;
        }
        .trains-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .trains-table tr:hover {
            background-color: #e6f2ff;
        }
        .on-time {
            color: #27ae60;
            font-weight: bold;
        }
        .delayed {
            color: #e74c3c;
            font-weight: bold;
        }
        .no-trains {
            padding: 20px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Train Arrivals</h1>
        
        <div class="station-selector">
            <form method="get" action=" <?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="station">Select Station:</label>
                <select id="station" name="station" required>
                    <option value="">-- Select a station --</option>
                    <?php foreach ($allStations as $station): ?>
                        <option value="<?php echo htmlspecialchars($station['name']); ?>"
                            <?php if ($station['name'] === $stationName) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($station['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Show Arrivals</button>
            </form>
        </div>

        <?php if (!empty($stationName)): ?>
            <h2>Arrivals at <?php echo htmlspecialchars($stationName); ?></h2>
            
            <?php if (!empty($trains)): ?>
                <table class="trains-table">
                    <thead>
                        <tr>
                            <th>Train Number</th>
                            <th>Train Name</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Scheduled Arrival</th>
                            <th>Status</th>
                            <th>Estimated Arrival</th>
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
                                <td class="<?php echo $train['status'] === 'DELAYED' ? 'delayed' : 'on-time'; ?>">
                                    <?php 
                                    echo htmlspecialchars($train['status']); 
                                    if ($train['status'] === 'DELAYED' && !empty($train['delay_minutes'])) {
                                        echo ' (' . htmlspecialchars($train['delay_minutes']) . ' mins)';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if (!empty($train['estimated_arrival'])) {
                                        echo htmlspecialchars($train['estimated_arrival']);
                                        if ($train['status'] === 'DELAYED' && !empty($train['delay_minutes'])) {
                                            echo ' <span class="delayed">(+' . htmlspecialchars($train['delay_minutes']) . ' mins)</span>';
                                        }
                                    } else {
                                        echo htmlspecialchars($train['scheduled_arrival']);
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-trains">
                    No trains scheduled to arrive at this station.
                </div>
            <?php endif; ?>
        <?php elseif (!empty($_GET['station'])): ?>
            <div class="no-trains">
                Station not found. Please select another station from the dropdown.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
