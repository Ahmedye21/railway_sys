<?php
require_once CORE_PATH . '/config.php';

class TrainTracking {
    private $pdo;
    
    public function __construct() {
        $this->pdo = DatabaseConfig::getConnection();
    }

    public function getTrainInfo($searchType, $searchValue) {
        try {
            if ($searchType === 'number') {
                $sql = "SELECT 
                            t.train_id, t.train_number, t.name, r.name AS route_name,
                            t.has_wifi, t.has_food, t.has_power_outlets
                        FROM 
                            trains t
                        JOIN 
                            routes r ON t.route_id = r.route_id
                        WHERE 
                            t.train_number = :searchValue
                            AND t.is_active = true";
            } else {
                $sql = "SELECT 
                            t.train_id, t.train_number, t.name, r.name AS route_name,
                            t.has_wifi, t.has_food, t.has_power_outlets
                        FROM 
                            trains t
                        JOIN 
                            routes r ON t.route_id = r.route_id
                        WHERE 
                            t.name LIKE :searchValue
                            AND t.is_active = true";
                $searchValue = "%$searchValue%";
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':searchValue', $searchValue);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                // Get route start and end stations
                $routeSql = "SELECT 
                                s_start.name AS start_station, 
                                s_end.name AS end_station
                             FROM 
                                routes r
                             JOIN route_stations rs_start ON r.route_id = rs_start.route_id
                             JOIN route_stations rs_end ON r.route_id = rs_end.route_id
                             JOIN stations s_start ON rs_start.station_id = s_start.station_id
                             JOIN stations s_end ON rs_end.station_id = s_end.station_id
                             WHERE 
                                r.name = :routeName
                                AND rs_start.sequence_number = (
                                    SELECT MIN(sequence_number) FROM route_stations WHERE route_id = r.route_id
                                )
                                AND rs_end.sequence_number = (
                                    SELECT MAX(sequence_number) FROM route_stations WHERE route_id = r.route_id
                                )";
                
                $routeStmt = $this->pdo->prepare($routeSql);
                $routeStmt->bindParam(':routeName', $result['route_name']);
                $routeStmt->execute();
                $routeInfo = $routeStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($routeInfo) {
                    $result['route'] = $routeInfo['start_station'] . ' to ' . $routeInfo['end_station'];
                } else {
                    $result['route'] = $result['route_name'];
                }
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error fetching train info: " . $e->getMessage());
            return false;
        }
    }
    
    public function getTrainStatusInfo($trainId) {
        try {
            // Get active schedule for today's weekday
            $today = date('l'); // e.g., Monday, Tuesday, etc.
            
            $sql = "SELECT 
                        schedule_id 
                    FROM 
                        schedules 
                    WHERE 
                        train_id = :trainId 
                        AND day_of_week = :today 
                        AND is_active = true";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':trainId', $trainId);
            $stmt->bindParam(':today', $today);
            $stmt->execute();
            
            $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$schedule) {
                // If no schedule for today, get the nearest schedule
                $sql = "SELECT 
                            schedule_id, day_of_week
                        FROM 
                            schedules 
                        WHERE 
                            train_id = :trainId 
                            AND is_active = true
                        LIMIT 1";
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':trainId', $trainId);
                $stmt->execute();
                $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$schedule) {
                    return [
                        'status' => 'Not Running',
                        'delay_minutes' => 0,
                        'last_updated' => date('Y-m-d H:i:s'),
                        'current_station' => 'N/A',
                        'next_station' => 'N/A',
                        'expected_arrival' => 'N/A',
                        'schedule_id' => null
                    ];
                }
            }
            
            // Check if there's an entry in train_status table
            $statusSql = "SELECT 
                            ts.status, ts.delay_minutes, ts.last_updated, 
                            ts.expected_arrival, 
                            cur_s.name AS current_station,
                            next_s.name AS next_station
                        FROM 
                            train_status ts
                        LEFT JOIN 
                            stations cur_s ON ts.current_station_id = cur_s.station_id
                        LEFT JOIN 
                            stations next_s ON ts.next_station_id = next_s.station_id
                        WHERE 
                            ts.train_id = :trainId 
                            AND ts.schedule_id = :scheduleId";
            
            $statusStmt = $this->pdo->prepare($statusSql);
            $statusStmt->bindParam(':trainId', $trainId);
            $statusStmt->bindParam(':scheduleId', $schedule['schedule_id']);
            $statusStmt->execute();
            
            $status = $statusStmt->fetch(PDO::FETCH_ASSOC);
            
            // If no status record, generate some simulated data
            if (!$status) {
                // For demo purposes only - simulating data
                $startTime = strtotime('today 8:00 AM');
                $endTime = strtotime('today 6:00 PM');
                $currentTime = time();
                
                $progress = ($currentTime - $startTime) / ($endTime - $startTime);
                $progress = max(0, min(1, $progress));
                
                // Get route stations for this train
                $routeSql = "SELECT 
                                s.station_id, s.name,
                                sd.arrival_time, sd.departure_time
                             FROM 
                                trains t
                             JOIN routes r ON t.route_id = r.route_id
                             JOIN route_stations rs ON r.route_id = rs.route_id
                             JOIN stations s ON rs.station_id = s.station_id
                             LEFT JOIN schedule_details sd ON sd.schedule_id = :scheduleId AND sd.station_id = s.station_id
                             WHERE 
                                t.train_id = :trainId
                             ORDER BY 
                                rs.sequence_number";
                
                $routeStmt = $this->pdo->prepare($routeSql);
                $routeStmt->bindParam(':trainId', $trainId);
                $routeStmt->bindParam(':scheduleId', $schedule['schedule_id']);
                $routeStmt->execute();
                
                $stations = $routeStmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($stations) > 0) {
                    $stationIndex = floor($progress * (count($stations) - 1));
                    $currentStation = $stations[$stationIndex]['name'];
                    $nextStationIndex = min(count($stations) - 1, $stationIndex + 1);
                    $nextStation = $stations[$nextStationIndex]['name'];
                    
                    $expectedArrival = date('Y-m-d H:i:s', time() + rand(15, 60) * 60);
                    
                    $statusOptions = ['On Time', 'Delayed', 'On Time', 'On Time'];
                    $trainStatus = $statusOptions[array_rand($statusOptions)];
                    
                    $status = [
                        'status' => $trainStatus,
                        'delay_minutes' => ($trainStatus === 'Delayed') ? rand(5, 30) : 0,
                        'last_updated' => date('Y-m-d H:i:s', time() - rand(5, 15) * 60),
                        'current_station' => $currentStation,
                        'next_station' => $nextStation,
                        'expected_arrival' => $expectedArrival
                    ];
                }
            }
            
            if ($status) {
                $status['schedule_id'] = $schedule['schedule_id'];
                
                // Format expected arrival to be more user-friendly
                if (isset($status['expected_arrival']) && $status['expected_arrival'] !== 'N/A') {
                    $arrivalTime = strtotime($status['expected_arrival']);
                    $minutesDiff = round((strtotime($status['expected_arrival']) - time()) / 60);
                    $status['expected_arrival_formatted'] = date('h:i A', $arrivalTime);
                    
                    if ($minutesDiff > 0) {
                        $status['expected_arrival_formatted'] .= " (in " . $minutesDiff . " mins)";
                    } else {
                        $status['expected_arrival_formatted'] .= " (arrived)";
                    }
                }
                
                if (isset($status['last_updated'])) {
                    $updatedTime = strtotime($status['last_updated']);
                    $status['last_updated_formatted'] = "Today, " . date('h:i A', $updatedTime);
                }
            } else {
                $status = [
                    'status' => 'Unknown',
                    'delay_minutes' => 0,
                    'last_updated' => date('Y-m-d H:i:s'),
                    'current_station' => 'N/A',
                    'next_station' => 'N/A',
                    'expected_arrival' => 'N/A',
                    'schedule_id' => $schedule['schedule_id']
                ];
            }
            
            return $status;
        } catch (Exception $e) {
            error_log("Error fetching train status: " . $e->getMessage());
            return [
                'status' => 'Error',
                'delay_minutes' => 0,
                'last_updated' => date('Y-m-d H:i:s'),
                'current_station' => 'N/A',
                'next_station' => 'N/A',
                'expected_arrival' => 'N/A',
                'schedule_id' => null
            ];
        }
    }
    
    public function getJourneyProgress($trainId, $scheduleId) {
        try {
            if (!$scheduleId) {
                return [];
            }
            
            // Get all stations in the route with their schedule details
            $sql = "SELECT 
                        s.station_id, s.name, 
                        sd.arrival_time, sd.departure_time, sd.day_offset,
                        rs.sequence_number
                    FROM 
                        trains t
                    JOIN routes r ON t.route_id = r.route_id
                    JOIN route_stations rs ON r.route_id = rs.route_id
                    JOIN stations s ON rs.station_id = s.station_id
                    LEFT JOIN schedule_details sd ON sd.schedule_id = :scheduleId AND sd.station_id = s.station_id
                    WHERE 
                        t.train_id = :trainId
                    ORDER BY 
                        rs.sequence_number";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':trainId', $trainId);
            $stmt->bindParam(':scheduleId', $scheduleId);
            $stmt->execute();
            
            $stations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Now determine which stations have been passed
            // For simulation purposes, let's say 40% of the journey is done
            $startTime = strtotime('today 5:30 AM'); // Simulation start time
            $endTime = strtotime('today 8:00 PM');   // Simulation end time
            $currentTime = time();
            
            $progress = ($currentTime - $startTime) / ($endTime - $startTime);
            $progress = max(0, min(1, $progress));
            
            $totalStations = count($stations);
            $currentStationIndex = floor($progress * $totalStations);
            
            $result = [];
            foreach ($stations as $index => $station) {
                $stationData = [
                    'name' => $station['name'],
                    'status' => ($index < $currentStationIndex) ? 'passed' : ($index == $currentStationIndex ? 'active' : '')
                ];
                
                // For the first station
                if ($index === 0) {
                    $depTime = $startTime - rand(8, 16) * 3600; // Departed yesterday
                    $stationData['time'] = 'Departed: Yesterday, ' . date('g:i A', $depTime);
                }
                // For the last station
                else if ($index === $totalStations - 1) {
                    $arrTime = $endTime;
                    $stationData['time'] = 'Arrival: Today, ' . date('g:i A', $arrTime);
                }
                // For passed stations
                else if ($index < $currentStationIndex) {
                    // Calculate a time based on position in journey
                    $stationTime = $startTime + ($endTime - $startTime) * ($index / $totalStations);
                    
                    // If it's very early in journey, show as yesterday
                    if ($stationTime < strtotime('today 12:00 AM')) {
                        $stationData['time'] = 'Departed: Yesterday, ' . date('g:i A', $stationTime);
                    } else {
                        $stationData['time'] = 'Departed: Today, ' . date('g:i A', $stationTime);
                    }
                }
                // For current and future stations
                else {
                    // Calculate expected time
                    $stationTime = $startTime + ($endTime - $startTime) * ($index / $totalStations);
                    $stationData['time'] = 'Expected: Today, ' . date('g:i A', $stationTime);
                }
                
                $result[] = $stationData;
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error fetching journey progress: " . $e->getMessage());
            return [];
        }
    }
}