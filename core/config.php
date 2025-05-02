<?php
class DatabaseConfig {
    private static $host = "localhost";
    private static $dbname = "railway_sysdb";
    private static $username = "root";
    private static $pass = "";
    private static $conn = null;
    
    public static function getConnection() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname, 
                    self::$username, 
                    self::$pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch(PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
    
    public static function getDbName() {
        return self::$dbname;
    }
}
?>
