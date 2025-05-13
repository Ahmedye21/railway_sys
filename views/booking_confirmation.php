<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation | RailConnect</title>
    <!-- Include your CSS files -->
</head>
<body>
    <?php include_once PARTIALS_PATH . 'header.php'; ?>

    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0"><i class="bi bi-check-circle"></i> Booking Confirmed</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h4 class="alert-heading">Thank you for your booking!</h4>
                    <p>Your booking reference is: <strong>RC-<?php echo str_pad($booking['booking_id'], 6, '0', STR_PAD_LEFT); ?></strong></p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5>Journey Details</h5>
                        <div class="mb-3">
                            <div class="fw-bold"><?php echo $booking['train_name']; ?> (<?php echo $booking['train_number']; ?>)</div>
                            <div><?php echo $booking['from_station_name']; ?> to <?php echo $booking['to_station_name']; ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold">Travel Date</div>
                            <div><?php echo date('l, F j, Y', strtotime($booking['travel_date'])); ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold">Class</div>
                            <div><?php echo ($booking['travel_class'] === 'first') ? 'First Class' : 'Second Class'; ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Payment Details</h5>
                        <div class="mb-3">
                            <div class="fw-bold">Total Amount</div>
                            <div class="fs-4 text-success">EGP <?php echo number_format($booking['total_amount'], 2); ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold">Booking Date</div>
                            <div><?php echo date('F j, Y', strtotime($booking['booking_date'])); ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold">Status</div>
                            <span class="badge bg-success"><?php echo $booking['booking_status']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="/" class="btn btn-primary">Back to Home</a>
                    <a href="/user/bookings" class="btn btn-outline-secondary">View My Bookings</a>
                    <button class="btn btn-outline-success" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once PARTIALS_PATH . 'footer.php'; ?>
</body>
</html>