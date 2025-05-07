<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = DatabaseConfig::getConnection();
        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);

        // Validate amount
        if ($amount < 100) {
            throw new Exception("Minimum recharge amount is EGP100");
        }

        // Start transaction
        $pdo->beginTransaction();

        // Add amount to user's balance
        $stmt = $pdo->prepare("
            UPDATE users 
            SET balance = balance + ? 
            WHERE user_id = ?
        ");
        $stmt->execute([$amount, $_SESSION['user_id']]);

        // Record the transaction
        $stmt = $pdo->prepare("
            INSERT INTO transactions (
                user_id, 
                amount
            ) VALUES (?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $amount]);

        // Update session balance
        $_SESSION['balance'] += $amount;

        // Commit transaction
        $pdo->commit();

        $success = "Successfully added EGP" . number_format($amount, 2) . " to your balance";

    } catch (Exception $e) {
        // Rollback on error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error = $e->getMessage();
    }
}