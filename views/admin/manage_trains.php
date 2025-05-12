<?php
$message = $message ?? null;
$error = $error ?? null;
$trains = $trains ?? [];
$routes = $routes ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trains | Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/assets/css/index.css">

</head>
<body>

    <?php 
    // Include header partial - add a forward slash
    include_once PARTIALS_PATH . 'admin_navbar.php';
    ?>
    <div class="container-fluid">
        <div class="row">
            <?php 
            // Include header partial - add a forward slash
            include_once PARTIALS_PATH . 'admin_sidebar.php';
            ?>
            <main class="main-content col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h1 class="h2 mb-4">Manage Trains</h1>


                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Add Train Button -->
                <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addTrainModal">
                    <i class="bi bi-plus-circle me-2"></i>Add New Train
                </button>

                <!-- Trains Table -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Train Number</th>
                                        <th>Name</th>
                                        <th>Route</th>
                                        <th>First Class Seats</th>
                                        <th>Second Class Seats</th>
                                        <th>Amenities</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($trains as $train): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($train['train_number'] ?? '--'); ?></td>
                                            <td><?php echo htmlspecialchars($train['name'] ?? '--'); ?></td>
                                            <td><?php echo htmlspecialchars($train['route_name'] ?? '--'); ?></td>
                                            <td><?php echo $train['total_first_class_seats'] ?? '0'; ?></td>
                                            <td><?php echo $train['total_second_class_seats'] ?? '0'; ?></td>
                                            
                                            <td>
                                                <?php if (($train['has_wifi'] ?? 0) == 1): ?>
                                                    <i class="bi bi-wifi" title="WiFi"></i>
                                                <?php endif; ?>
                                                <?php if (($train['has_food'] ?? 0) == 1): ?>
                                                    <i class="bi bi-cup-hot" title="Food Service"></i>
                                                <?php endif; ?>
                                                <?php if (($train['has_power_outlets'] ?? 0) == 1): ?>
                                                    <i class="bi bi-plug" title="Power Outlets"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editTrainModal"
                                                            data-train-id="<?php echo $train['train_id'] ?? ''; ?>"
                                                            data-train-number="<?php echo htmlspecialchars($train['train_number'] ?? ''); ?>"
                                                            data-train-name="<?php echo htmlspecialchars($train['name'] ?? ''); ?>"
                                                            data-route-id="<?php echo $train['route_id'] ?? ''; ?>"
                                                            data-first-class="<?php echo $train['total_first_class_seats'] ?? 0; ?>"
                                                            data-second-class="<?php echo $train['total_second_class_seats'] ?? 0; ?>"
                                                            data-has-wifi="<?php echo $train['has_wifi'] ?? 0; ?>"
                                                            data-has-food="<?php echo $train['has_food'] ?? 0; ?>"
                                                            data-has-power="<?php echo $train['has_power_outlets'] ?? 0; ?>">
                                                        <i class="bi bi-pencil me-1"></i>Edit
                                                    </button>
                                                    <form action="<?php echo BASE_URL; ?>/index.php?action=delete_train" method="POST" class="d-inline">
                                                        <input type="hidden" name="train_id" value="<?php echo $train['train_id'] ?? ''; ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash me-1"></i>Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Train Modal -->
    <div class="modal fade" id="addTrainModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Train</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php?action=admin_add_train" method="POST">
                        <input type="hidden" name="action" value="add_train">
                        
                        <div class="mb-3">
                            <label class="form-label">Train Number</label>
                            <input type="text" class="form-control" name="train_number" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Train Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Route</label>
                            <select class="form-select" name="route_id" required>
                                <?php foreach ($routes as $route): ?>
                                    <option value="<?php echo $route['route_id']; ?>">
                                        <?php echo htmlspecialchars($route['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Class Seats</label>
                                <input type="number" class="form-control" name="first_class_seats" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Second Class Seats</label>
                                <input type="number" class="form-control" name="second_class_seats" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Amenities</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="has_wifi" id="hasWifi">
                                <label class="form-check-label" for="hasWifi">WiFi</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="has_food" id="hasFood">
                                <label class="form-check-label" for="hasFood">Food Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="has_power_outlets" id="hasPower">
                                <label class="form-check-label" for="hasPower">Power Outlets</label>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Train</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Train Modal -->
    <div class="modal fade" id="editTrainModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Train</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php?action=admin_edit_train" method="POST">
                        
                        <input type="hidden" name="train_id" id="editTrainId">
                        
                        <div class="mb-3">
                            <label class="form-label">Train Number</label>
                            <input type="text" class="form-control" name="train_number" id="editTrainNumber" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Train Name</label>
                            <input type="text" class="form-control" name="name" id="editTrainName" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Route</label>
                            <select class="form-select" name="route_id" id="editRouteId" required>
                                <?php foreach ($routes as $route): ?>
                                    <option value="<?php echo $route['route_id']; ?>">
                                        <?php echo htmlspecialchars($route['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Class Seats</label>
                                <input type="number" class="form-control" name="first_class_seats" id="editFirstClass" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Second Class Seats</label>
                                <input type="number" class="form-control" name="second_class_seats" id="editSecondClass" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Amenities</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="has_wifi" id="editHasWifi">
                                <label class="form-check-label" for="editHasWifi">WiFi</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="has_food" id="editHasFood">
                                <label class="form-check-label" for="editHasFood">Food Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="has_power_outlets" id="editHasPower">
                                <label class="form-check-label" for="editHasPower">Power Outlets</label>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add this to your existing script section
        document.getElementById('editTrainModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const trainId               = button.getAttribute('data-train-id');
            const trainNumber           = button.getAttribute('data-train-number');
            const trainName             = button.getAttribute('data-train-name');
            const routeId               = button.getAttribute('data-route-id');
            const firstClass            = button.getAttribute('data-first-class');
            const secondClass           = button.getAttribute('data-second-class');
            const hasWifi               = button.getAttribute('data-has-wifi') === '1';
            const hasFood               = button.getAttribute('data-has-food') === '1';
            const hasPower              = button.getAttribute('data-has-power') === '1';

            // Set form values
            this.querySelector('#editTrainId').value = trainId;
            this.querySelector('#editTrainNumber').value = trainNumber;
            this.querySelector('#editTrainName').value = trainName;
            this.querySelector('#editRouteId').value = routeId;
            this.querySelector('#editFirstClass').value = firstClass;
            this.querySelector('#editSecondClass').value = secondClass;
            this.querySelector('#editHasWifi').checked = hasWifi;
            this.querySelector('#editHasFood').checked = hasFood;
            this.querySelector('#editHasPower').checked = hasPower;
        });
    </script>
</body>
</html>