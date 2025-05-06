<?php
    session_start();
    include_once "../config.php";

    $pdo = DatabaseConfig::getConnection();
    try {
        $stationsStmt = $pdo->query("SELECT station_id, name FROM stations WHERE is_active = 1 ORDER BY name");
        $stations = $stationsStmt->fetchAll();
    } catch(PDOException $e) {
        $stations = [];
        // Handle error (could set an error message here)
    }