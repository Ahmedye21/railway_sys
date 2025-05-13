<?php

require_once CORE_PATH . '/config.php';

class StationModel {
    private $pdo;
    
    public function __construct() {
        $this->pdo = DatabaseConfig::getConnection();
    }
    

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

    public function getRouteStations($scheduleId, $routeId, $departureSequence, $arrivalSequence) {
        $stationsSql = "SELECT 
                            s.name AS station_name,
                            rs.sequence_number,
                            sd.arrival_time,
                            sd.departure_time
                        FROM 
                            route_stations rs
                        JOIN 
                            stations s ON rs.station_id = s.station_id
                        LEFT JOIN
                            schedule_details sd ON sd.station_id = s.station_id AND sd.schedule_id = :schedule_id
                        WHERE 
                            rs.route_id = :route_id
                            AND rs.sequence_number BETWEEN :departure_sequence AND :arrival_sequence
                        ORDER BY 
                            rs.sequence_number";
                            
        $stationsStmt = $this->pdo->prepare($stationsSql);
        $stationsStmt->execute([
            ':schedule_id' => $scheduleId,
            ':route_id' => $routeId,
            ':departure_sequence' => $departureSequence,
            ':arrival_sequence' => $arrivalSequence
        ]);
        
        return $stationsStmt->fetchAll();
    }
    
    

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


    public function getTrainsByStation($stationId) {
        try {
            $sql = "SELECT 
                    t.train_id,
                    t.train_number,
                    t.name AS train_name,
                    o.name AS origin,
                    d.name AS destination,
                    sd.arrival_time AS scheduled_arrival,
                    ts.status,
                    ts.delay_minutes,
                    ts.expected_arrival AS estimated_arrival
                FROM schedule_details sd
                JOIN schedules s ON sd.schedule_id = s.schedule_id
                JOIN trains t ON s.train_id = t.train_id
                JOIN route_stations rs_o ON t.route_id = rs_o.route_id AND rs_o.sequence_number = 1
                JOIN stations o ON rs_o.station_id = o.station_id
                JOIN route_stations rs_d ON t.route_id = rs_d.route_id
                JOIN stations d ON rs_d.station_id = d.station_id
                LEFT JOIN train_status ts ON t.train_id = ts.train_id AND s.schedule_id = ts.schedule_id
                WHERE sd.station_id = :stationId
                AND rs_d.sequence_number = (
                    SELECT MAX(sequence_number) 
                    FROM route_stations 
                    WHERE route_id = t.route_id
                )
                ORDER BY sd.arrival_time";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':stationId' => $stationId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting trains by station: " . $e->getMessage());
            return [];
        }
    }



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

        public function getStationIdByName($stationName) {
            try {
                $sql = "SELECT station_id FROM stations WHERE name = :name";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':name' => $stationName]);
                return $stmt->fetchColumn();
            } catch (PDOException $e) {
                error_log("StationModel Error: " . $e->getMessage());
                return false;
            }
        }
}