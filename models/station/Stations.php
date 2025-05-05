<?php

require_once CORE_PATH . '/config.php';

class StationModel {
    private $pdo;
    
    public function __construct() {
        $this->pdo = DatabaseConfig::getConnection();
    }
    
    /**
     * Get all available stations
     * 
     * @return array List of stations
     */
    public function getAllStations() {
        try {
            $sql = "SELECT station_id, name FROM stations WHERE is_active = 1 ORDER BY name";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($results)) {
                error_log("No stations found in the database.");
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("StationModel Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get station by ID
     * 
     * @param int $stationId
     * @return array|false Station data or false if not found
     */
    public function getStationById($stationId) {
        try {
            $sql = "SELECT * FROM stations WHERE station_id = :station_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':station_id' => $stationId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("StationModel Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get station by name
     * 
     * @param string $stationName
     * @return array|false Station data or false if not found
     */
    public function getStationByName($stationName) {
        try {
            $sql = "SELECT * FROM stations WHERE name = :name";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':name' => $stationName]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("StationModel Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Search stations by name or code
     * 
     * @param string $query
     * @return array Matching stations
     */
    public function searchStations($query) {
        try {
            $sql = "SELECT station_id, name, code FROM stations 
                    WHERE name LIKE :query OR code LIKE :query 
                    ORDER BY name LIMIT 10";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':query' => "%$query%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("StationModel Error: " . $e->getMessage());
            return [];
        }
    }



    /**
     * Add a new station
     * 
     * @param string $name
     * @param string $code
     * @param string $city
     * @return bool True on success, false on failure
     */
    public function addStation($name, $code, $city) {
        try {
            $sql = "INSERT INTO stations (name, code, city) VALUES (:name, :code, :city)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':name' => $name, ':code' => $code, ':city' => $city]);
        } catch (PDOException $e) {
            error_log("StationModel Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing station
     * 
     * @param int $stationId
     * @param string $name
     * @param string $code
     * @param string $city
     * @return bool True on success, false on failure
     */

    public function updateStation($stationId, $name, $code, $city) {
        try {
            $sql = "UPDATE stations SET name = :name, code = :code, city = :city WHERE station_id = :station_id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':name' => $name, ':code' => $code, ':city' => $city, ':station_id' => $stationId]);
        } catch (PDOException $e) {
            error_log("StationModel Error: " . $e->getMessage());
            return false;
        }
    }

    
        /**
        * Delete a station
        * 
        * @param int $stationId
        * @return bool True on success, false on failure
        */
        public function deleteStation($stationId) {
            try {
                $sql = "DELETE FROM stations WHERE station_id = :station_id";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute([':station_id' => $stationId]);
            } catch (PDOException $e) {
                error_log("StationModel Error: " . $e->getMessage());
                return false;
            }
        }
}