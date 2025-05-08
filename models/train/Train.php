<?php


class Train{
    private $pdo;
    
    public function __construct() {
        $this->pdo = DatabaseConfig::getConnection();
    }

    public function searchTrains($departureStation, $arrivalStation, $dayOfWeek) {
        $sql = "SELECT DISTINCT 
                    r.route_id,
                    r.name AS route_name,
                    t.train_id,
                    t.train_number,
                    t.name AS train_name,
                    s.schedule_id,
                    t.has_wifi,
                    t.has_food,
                    t.has_power_outlets,
                    p.first_class_price,
                    p.second_class_price,
                    dep_sched.departure_time,
                    arr_sched.arrival_time,
                    dep_rs.sequence_number AS departure_sequence,
                    arr_rs.sequence_number AS arrival_sequence
                FROM 
                    routes r
                JOIN 
                    route_stations dep_rs ON r.route_id = dep_rs.route_id
                JOIN 
                    stations dep_s ON dep_rs.station_id = dep_s.station_id AND dep_s.name = :departureStation
                JOIN 
                    route_stations arr_rs ON r.route_id = arr_rs.route_id
                JOIN 
                    stations arr_s ON arr_rs.station_id = arr_s.station_id AND arr_s.name = :arrivalStation
                JOIN 
                    trains t ON r.route_id = t.route_id
                JOIN 
                    schedules s ON t.train_id = s.train_id AND s.day_of_week = :dayOfWeek
                JOIN 
                    schedule_details dep_sched ON s.schedule_id = dep_sched.schedule_id AND dep_sched.station_id = dep_s.station_id
                JOIN 
                    schedule_details arr_sched ON s.schedule_id = arr_sched.schedule_id AND arr_sched.station_id = arr_s.station_id
                JOIN 
                    pricing p ON r.route_id = p.route_id AND p.from_station_id = dep_s.station_id AND p.to_station_id = arr_s.station_id
                WHERE 
                    dep_rs.sequence_number < arr_rs.sequence_number
                    AND t.is_active = true
                    AND s.is_active = true
                ORDER BY 
                    dep_sched.departure_time";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':departureStation' => $departureStation,
            ':arrivalStation' => $arrivalStation,
            ':dayOfWeek' => $dayOfWeek
        ]);
        
        return $stmt->fetchAll();
    }

    public function getAllTrains() {
        $sql = "SELECT 
                    train_id,
                    train_number,
                    name,
                    total_first_class_seats,
                    total_second_class_seats,
                    has_wifi,
                    has_food,
                    has_power_outlets
                FROM 
                    trains
                WHERE 
                    is_active = true
                ORDER BY 
                    train_number";
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTrainsWithRoutes() {
        $sql = "SELECT 
                    t.train_id,
                    t.train_number,
                    t.name,
                    t.total_first_class_seats,
                    t.total_second_class_seats,
                    t.has_wifi,
                    t.has_food,
                    t.has_power_outlets,
                    r.route_id,
                    r.name AS route_name
                FROM 
                    trains t
                JOIN 
                    routes r ON t.route_id = r.route_id
                WHERE 
                    t.is_active = true
                ORDER BY 
                    r.name, t.train_number";
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}
