<?php
// Ensure variables are defined before use
$trains = $trains ?? [];
$stations = $stations ?? [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Station Master Dashboard | RailConnect</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/assets/css/index.css">
    <style>
        .card-dashboard {
            transition: transform 0.3s ease;
        }
        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 0.35em 0.65em;
        }
        .train-number {
            font-family: monospace;
            font-weight: bold;
        }
        .action-buttons .btn {
            min-width: 80px;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navigation Bar -->
    <?php include_once PARTIALS_PATH . 'header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="#">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#trains-section">
                                <i class="bi bi-train-front me-2"></i>Trains
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">
                                <i class="bi bi-clock-history me-2"></i>Schedules
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">
                                <i class="bi bi-exclamation-triangle me-2"></i>Incidents
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-dashboard bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase small">Total Trains</h6>
                                    <h2 class="mb-0"><?= number_format(count($trains)) ?></h2>
                                </div>
                                <i class="bi bi-train-front fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-dashboard bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase small">On Time</h6>
                                    <h2 class="mb-0">
                                        <?= number_format(count(array_filter($trains, fn($t) => ($t['status'] ?? 'on_time') === 'on_time'))) ?>
                                    </h2>
                                </div>
                                <i class="bi bi-check-circle fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-dashboard bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase small">Delayed</h6>
                                    <h2 class="mb-0">
                                        <?= number_format(count(array_filter($trains, fn($t) => ($t['status'] ?? 'on_time') === 'delayed'))) ?>
                                    </h2>
                                </div>
                                <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- Trains Section -->
                <div class="card shadow-sm mb-4" id="trains-section">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Train Management</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#addTrainModal">
                                <i class="bi bi-plus-circle"></i> Add Train
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" id="refreshTrains">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="trainsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Train No.</th>
                                        <th>Name</th>
                                        <th>Current Station</th>
                                        <th>Status</th>
                                        <th>Next Arrival</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($trains)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="bi bi-train text-muted fs-1 mb-2"></i>
                                                    <p class="text-muted">No trains available</p>
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTrainModal">
                                                        Add First Train
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($trains as $train): ?>
                                            <tr>
                                                <td class="train-number"><?= htmlspecialchars($train['train_number']) ?></td>
                                                <td><?= htmlspecialchars($train['name'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($train['current_station'] ?? 'Unknown') ?></td>
                                                <td>
                                                    <?php 
                                                    $statusClass = [
                                                        'on_time' => 'bg-success',
                                                        'delayed' => 'bg-warning',
                                                        'cancelled' => 'bg-danger',
                                                        'departed' => 'bg-info'
                                                    ][$train['status'] ?? 'on_time'] ?? 'bg-secondary';
                                                    ?>
                                                    <span class="badge <?= $statusClass ?> status-badge">
                                                        <?= ucfirst(str_replace('_', ' ', $train['status'] ?? 'N/A')) ?>
                                                    </span>
                                                    <?php if (!empty($train['delay_minutes'])): ?>
                                                        <small class="text-muted ms-1">(+<?= $train['delay_minutes'] ?> min)</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($train['next_arrival'])): ?>
                                                        <?= date('H:i', strtotime($train['next_arrival'])) ?>
                                                        <small class="text-muted d-block"><?= date('M j', strtotime($train['next_arrival'])) ?></small>
                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </td>
                                                <td class="action-buttons">
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" 
                                                                data-bs-toggle="tooltip" 
                                                                title="Mark Arrival">
                                                            <i class="bi bi-box-arrow-in-down"></i>
                                                        </button>
                                                        <button class="btn btn-outline-secondary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#delayTrainModal"
                                                                data-train-id="<?= $train['train_id'] ?>"
                                                                data-train-name="<?= htmlspecialchars($train['name']) ?>"
                                                                data-bs-tooltip="tooltip" 
                                                                title="Delay Train">
                                                            <i class="bi bi-clock-history"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger" 
                                                                data-bs-tooltip="tooltip" 
                                                                title="Mark Departure">
                                                            <i class="bi bi-box-arrow-up"></i>
                                                        </button>
                                                        <button class="btn btn-outline-info" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#trainDetailsModal"
                                                                data-train-id="<?= $train['train_id'] ?>"
                                                                data-bs-tooltip="tooltip" 
                                                                title="View Details">
                                                            <i class="bi bi-info-circle"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Delay Train Modal -->
    <div class="modal fade" id="delayTrainModal" tabindex="-1" aria-labelledby="delayTrainModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delayTrainModalLabel">Delay Train</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="delayTrainForm" action="admin_trains.php" method="POST">
                        <input type="hidden" name="action" value="delay_train">
                        <input type="hidden" name="train_id" id="statusTrainId">
                        
                        <div class="mb-3">
                            <label for="trainName" class="form-label">Train</label>
                            <input type="text" class="form-control" id="trainName" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="delayMinutes" class="form-label">Delay Duration</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="delayMinutes" name="delay_minutes" min="1" max="240" value="15">
                                <span class="input-group-text">minutes</span>
                            </div>
                            <div class="form-text">Maximum delay: 4 hours (240 minutes)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="delayReason" class="form-label">Reason for Delay</label>
                            <select class="form-select" id="delayReason" name="delay_reason">
                                <option value="mechanical_issues">Mechanical Issues</option>
                                <option value="weather_conditions">Weather Conditions</option>
                                <option value="operational_reasons">Operational Reasons</option>
                                <option value="security_concerns">Security Concerns</option>
                                <option value="other">Other (specify below)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="otherReasonContainer" style="display: none;">
                            <label for="otherReason" class="form-label">Specify Reason</label>
                            <textarea class="form-control" id="otherReason" name="other_reason" rows="2"></textarea>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="notifyPassengers" name="notify_passengers" checked>
                            <label class="form-check-label" for="notifyPassengers">Notify passengers via SMS/Email</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="delayTrainForm" class="btn btn-primary">Submit Delay</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Train Details Modal -->
    <div class="modal fade" id="trainDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Train Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="trainDetailsContent">
                    <!-- Content will be loaded via AJAX -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Train Modal (Placeholder) -->
    <div class="modal fade" id="addTrainModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Train</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Train addition functionality would go here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Train</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#trainsTable').DataTable({
                responsive: true,
                order: [[4, 'asc']], // Sort by next arrival by default
                language: {
                    emptyTable: "No trains available to display"
                }
            });

            // Initialize tooltips
            $('[data-bs-tooltip="tooltip"]').tooltip();

            // Delay Train Modal setup
            $('#delayTrainModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const trainId = button.data('train-id');
                const trainName = button.data('train-name');
                
                const modal = $(this);
                modal.find('#statusTrainId').val(trainId);
                modal.find('#trainName').val(trainName);
            });

            // Show/hide other reason field based on selection
            $('#delayReason').change(function() {
                $('#otherReasonContainer').toggle($(this).val() === 'other');
            });

            // Train Details Modal - Load content via AJAX
            $('#trainDetailsModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const trainId = button.data('train-id');
                
                // Show loading spinner
                $('#trainDetailsContent').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
                
                // Simulate AJAX call (replace with actual API call)
                setTimeout(() => {
                    $('#trainDetailsContent').html(`
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Basic Information</h6>
                                <dl class="row">
                                    <dt class="col-sm-4">Train Number</dt>
                                    <dd class="col-sm-8">${trainId}</dd>
                                    
                                    <dt class="col-sm-4">Current Status</dt>
                                    <dd class="col-sm-8"><span class="badge bg-success">On Time</span></dd>
                                    
                                    <dt class="col-sm-4">Current Station</dt>
                                    <dd class="col-sm-8">Central Station</dd>
                                </dl>
                                
                                <h6 class="mt-4">Schedule</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Station</th>
                                                <th>Arrival</th>
                                                <th>Departure</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Central Station</td>
                                                <td>08:00</td>
                                                <td>08:15</td>
                                            </tr>
                                            <tr>
                                                <td>North Station</td>
                                                <td>08:45</td>
                                                <td>08:50</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Crew Information</h6>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Driver
                                        <span class="badge bg-primary rounded-pill">John Smith</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Conductor
                                        <span class="badge bg-primary rounded-pill">Sarah Johnson</span>
                                    </li>
                                </ul>
                                
                                <h6 class="mt-4">Recent Incidents</h6>
                                <div class="alert alert-warning py-2">
                                    <small><i class="bi bi-exclamation-triangle-fill"></i> 15 min delay on 2023-10-15 due to mechanical issues</small>
                                </div>
                            </div>
                        </div>
                    `);
                }, 800);
            });

            // Refresh button functionality
            $('#refreshTrains').click(function() {
                // Simulate refresh (replace with actual refresh logic)
                $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Refreshing...');
                setTimeout(() => {
                    $(this).html('<i class="bi bi-arrow-clockwise"></i> Refresh');
                    // In a real app, you would reload the data here
                }, 1000);
            });
        });
    </script>
</body>
</html>