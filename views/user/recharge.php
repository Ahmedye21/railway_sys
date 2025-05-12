<?php
// views/user/recharge.php

$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;

// Clear the messages after displaying them
unset($_SESSION['error']);
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recharge Balance | RailConnect</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/index.css">
</head>
    <!-- Navigation Bar -->
    <?php 
    // Include header partial - add a forward slash
    include_once PARTIALS_PATH . 'header.php';
    ?>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-wallet2 me-2"></i>Recharge Balance</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form action="index.php?action=funds" method="POST" id="paymentForm">
                            <div class="mb-3">
                                <label class="form-label">Current Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text">EGP </span>
                                    <input type="text" class="form-control" value="<?php echo number_format($_SESSION['balance'], 2); ?>" disabled>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Recharge Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">EGP </span>
                                    <input type="number" class="form-control" name="amount" min="100" required>
                                </div>
                                <small class="text-muted">Minimum recharge amount: EGP 100</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="payment_method" value="credit_card" id="creditCard" checked>
                                            <label class="form-check-label" for="creditCard">
                                                <i class="bi bi-credit-card me-2"></i>Credit/Debit Card
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="payment_method" value="upi" id="upi">
                                            <label class="form-check-label" for="upi">
                                                <i class="bi bi-phone me-2"></i>UPI
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" value="net_banking" id="netBanking">
                                            <label class="form-check-label" for="netBanking">
                                                <i class="bi bi-bank me-2"></i>Net Banking
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-arrow-right-circle me-2"></i>Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>