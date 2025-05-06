<?php
require_once '../paths.php';
require_once BACKEND_PATH . 'stationmaster_dashboard.php';
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h1 class="h2 mb-4">Manage Trains</h1>

                <!-- Trains Table -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Train Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($trains as $train): ?>
                                        <tr>
                                            <td>
                                                <?php echo htmlspecialchars($train['train_number']); ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-primary">
                                                        Arrival
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#delayTrainModal"
                                                            data-train-id="<?php echo $train['train_id']; ?>"
                                                            data-train-name="<?php echo htmlspecialchars($train['name']); ?>">
                                                        Delay
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger">Deperature</button>
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
    <div class="modal fade" id="delayTrainModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delay Train</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="admin_trains.php" method="POST">
                        <input type="hidden" name="action" value="delay_train">
                        <input type="hidden" name="train_id" id="statusTrainId">
                        
                        <div id="delayDetails">
                            <div class="mb-3">
                                <label class="form-label">Delay (minutes)</label>
                                <input type="number" class="form-control" name="delay_minutes" value="0">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Delay Reason</label>
                                <textarea class="form-control" name="delay_reason" rows="3"></textarea>
                            </div>
                        </div>
                                                
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('delayTrainModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const trainId = button.getAttribute('data-train-id');
            
            this.querySelector('#statusTrainId').value = trainId;
        });
    </script>
</body>

</html>