<?php


class TrainSearch {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Search for trains between two stations on a specific date
     */
    public function searchTrains($departureStation, $arrivalStation, $departureDate) {
        $availableTrains = [];
        
        try {
            // Convert date to day of week
            $departureDateObj = new DateTime($departureDate);
            $dayOfWeek = $departureDateObj->format('l'); // Full day name

            // Find all possible routes between departure and arrival stations
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
            
            $trainResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($trainResults as $train) {
                // Get all stations on this route for this train
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
                    ':schedule_id' => $train['schedule_id'],
                    ':route_id' => $train['route_id'],
                    ':departure_sequence' => $train['departure_sequence'],
                    ':arrival_sequence' => $train['arrival_sequence']
                ]);
                
                $stations = $stationsStmt->fetchAll(PDO::FETCH_ASSOC);
                $stationNames = array_column($stations, 'station_name');
                
                // Calculate travel time
                $departureTime = new DateTime($train['departure_time']);
                $arrivalTime = new DateTime($train['arrival_time']);
                
                // Handle cases where arrival is on the next day
                if ($arrivalTime < $departureTime) {
                    $arrivalTime->modify('+1 day');
                }
                
                $interval = $departureTime->diff($arrivalTime);
                $travelTime = sprintf("%d:%02d", $interval->h, $interval->i);
                
                // Calculate intermediate stops
                $intermediateStops = count($stations) - 2;
                
                $availableTrains[] = [
                    'train_id' => $train['train_id'],
                    'train_number' => $train['train_number'],
                    'train_name' => $train['train_name'],
                    'route_name' => $train['route_name'],
                    'route_id' => $train['route_id'],
                    'schedule_id' => $train['schedule_id'],
                    'departure_station' => $departureStation,
                    'arrival_station' => $arrivalStation,
                    'departure_time' => $departureTime->format('H:i'),
                    'arrival_time' => $arrivalTime->format('H:i'),
                    'travel_time' => $travelTime,
                    'intermediate_stops' => $intermediateStops,
                    'first_class_price' => $train['first_class_price'],
                    'second_class_price' => $train['second_class_price'],
                    'has_wifi' => $train['has_wifi'],
                    'has_food' => $train['has_food'],
                    'has_power_outlets' => $train['has_power_outlets'],
                    'stations' => $stationNames
                ];
            }
            
            return $availableTrains;
            
        } catch (PDOException $e) {
            throw new Exception('Database error: ' . $e->getMessage());
        }
    }
    
    /**
     * Get all available stations
     */

}
