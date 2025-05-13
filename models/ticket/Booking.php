<?php
require_once CORE_PATH . '/config.php';

class Booking {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createBooking($userId, $trainId, $scheduleId, $fromStationId, $toStationId, $bookingDate, $travelDate, $travelClass, $totalAmount) {
        $stmt = $this->pdo->prepare("
            INSERT INTO bookings 
            (user_id, train_id, schedule_id, from_station_id, to_station_id, booking_date, travel_date, travel_class, total_amount) 
            VALUES 
            (:user_id, :train_id, :schedule_id, :from_station_id, :to_station_id, :booking_date, :travel_date, :travel_class, :total_amount)
        ");
        
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':train_id', $trainId);
        $stmt->bindParam(':schedule_id', $scheduleId);
        $stmt->bindParam(':from_station_id', $fromStationId);
        $stmt->bindParam(':to_station_id', $toStationId);
        $stmt->bindParam(':booking_date', $bookingDate);
        $stmt->bindParam(':travel_date', $travelDate);
        $stmt->bindParam(':travel_class', $travelClass);
        $stmt->bindParam(':total_amount', $totalAmount);
        
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function getBookingDetails($bookingId) {
        $stmt = $this->pdo->prepare("
            SELECT b.*, 
                   t.train_name, t.train_number,
                   fs.station_name as from_station_name,
                   ts.station_name as to_station_name
            FROM bookings b
            JOIN trains t ON b.train_id = t.train_id
            JOIN stations fs ON b.from_station_id = fs.station_id
            JOIN stations ts ON b.to_station_id = ts.station_id
            WHERE b.booking_id = :booking_id
        ");
        $stmt->bindParam(':booking_id', $bookingId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function beginTransaction() {
    $this->pdo->beginTransaction();
    }

    public function commit() {
        $this->pdo->commit();
    }

    public function rollBack() {
        $this->pdo->rollBack();
    }
}