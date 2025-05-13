<?php
require_once CORE_PATH . '/config.php';

class Ticket {
    private $pdo;

    public function __construct() {
        $this->pdo = DatabaseConfig::getConnection();
    }

    public function getTicketByUserId($userId) {
        if (!is_numeric($userId)) {
            throw new InvalidArgumentException("User ID must be a numeric value");
        }

        $sql = "SELECT 
            b.booking_id,
            b.user_id,
            b.train_id,
            b.schedule_id,
            b.from_station_id,
            b.to_station_id,
            b.booking_date,
            b.travel_date,
            b.travel_class,
            b.total_amount,
            b.booking_status,
            u.name AS user_name,
            u.email AS user_email,
            t.name,
            t.train_number,
            fs.name AS from_station_name,
            ts.name AS to_station_name
        FROM 
            bookings b
        JOIN 
            users u ON b.user_id = u.id
        JOIN 
            trains t ON b.train_id = t.train_id
        JOIN 
            schedules s ON b.schedule_id = s.schedule_id
        JOIN 
            stations fs ON b.from_station_id = fs.station_id
        JOIN 
            stations ts ON b.to_station_id = ts.station_id
        WHERE 
            b.user_id = :userId
        ORDER BY 
            b.travel_date DESC, b.booking_date DESC;";
    }


        



}