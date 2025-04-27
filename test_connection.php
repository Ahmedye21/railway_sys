<?php
require_once "config.php";

try {
    $conn = DatabaseConfig::getConnection();
    echo "Connected successfully to database: " . DatabaseConfig::getDbName();
    
    // Test query
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    echo "<pre>Tables: " . print_r($tables, true) . "</pre>";
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>