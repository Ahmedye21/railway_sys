<?php
    include_once "../config.php";
    $pdo = DatabaseConfig::getConnection();
    try {
        $trains = $pdo->query("
            SELECT t.*
            FROM trains t 
            ORDER BY t.train_number
        ")->fetchAll();
    } catch (PDOException $e) {
        $error = "Error fetching trains: " . $e->getMessage();
    }