-- Create the database
CREATE DATABASE IF NOT EXISTS railway_sysdb;
USE railway_sysdb;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'station_master') NOT NULL DEFAULT 'user',
    balance DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Stations table
CREATE TABLE stations (
    station_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    location VARCHAR(255),
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Routes table
CREATE TABLE routes (
    route_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- RouteStations table (to define the sequence of stations in a route)
CREATE TABLE route_stations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_id INT NOT NULL,
    station_id INT NOT NULL,
    sequence_number INT NOT NULL,  -- Order of stations in the route
    distance_from_start DECIMAL(10, 2),  -- Distance in km from the start station
    FOREIGN KEY (route_id) REFERENCES routes(route_id) ON DELETE CASCADE,
    FOREIGN KEY (station_id) REFERENCES stations(station_id) ON DELETE CASCADE,
    UNIQUE KEY (route_id, station_id),  -- A station appears only once in a route
    UNIQUE KEY (route_id, sequence_number),  -- Sequence numbers must be unique within a route
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Trains table
CREATE TABLE trains (
    train_id INT AUTO_INCREMENT PRIMARY KEY,
    train_number VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    route_id INT NOT NULL,
    total_first_class_seats INT NOT NULL DEFAULT 0,
    total_second_class_seats INT NOT NULL DEFAULT 0,
    has_wifi BOOLEAN DEFAULT FALSE,
    has_food BOOLEAN DEFAULT FALSE,
    has_power_outlets BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (route_id) REFERENCES routes(route_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Schedules table
CREATE TABLE schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    train_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (train_id) REFERENCES trains(train_id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (train_id, day_of_week)  -- Prevent duplicate schedules for the same train on the same day
);

-- Schedule details table (departure and arrival times at each station)
CREATE TABLE schedule_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    schedule_id INT NOT NULL,
    station_id INT NOT NULL,
    arrival_time TIME,  -- NULL for the first station
    departure_time TIME,  -- NULL for the last station
    day_offset INT DEFAULT 0,  -- Days offset from schedule start (for multi-day journeys)
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id) ON DELETE CASCADE,
    FOREIGN KEY (station_id) REFERENCES stations(station_id),
    UNIQUE KEY (schedule_id, station_id),  -- Each station appears once per schedule
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Train statuses for real-time tracking
CREATE TABLE train_status (
    status_id INT AUTO_INCREMENT PRIMARY KEY,
    train_id INT NOT NULL,
    schedule_id INT NOT NULL,
    current_station_id INT,
    next_station_id INT,
    status ENUM('On Time', 'Delayed', 'Cancelled', 'Arrived', 'Departed') DEFAULT 'On Time',
    delay_minutes INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expected_arrival DATETIME,
    FOREIGN KEY (train_id) REFERENCES trains(train_id),
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id),
    FOREIGN KEY (current_station_id) REFERENCES stations(station_id),
    FOREIGN KEY (next_station_id) REFERENCES stations(station_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Pricing table
CREATE TABLE pricing (
    pricing_id INT AUTO_INCREMENT PRIMARY KEY,
    route_id INT NOT NULL,
    from_station_id INT NOT NULL,
    to_station_id INT NOT NULL,
    first_class_price DECIMAL(10, 2) NOT NULL,
    second_class_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (route_id) REFERENCES routes(route_id),
    FOREIGN KEY (from_station_id) REFERENCES stations(station_id),
    FOREIGN KEY (to_station_id) REFERENCES stations(station_id),
    UNIQUE KEY (route_id, from_station_id, to_station_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    train_id INT NOT NULL,
    schedule_id INT NOT NULL,
    from_station_id INT NOT NULL,
    to_station_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    travel_date DATE NOT NULL,
    travel_class ENUM('first', 'second') NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    booking_status ENUM('Confirmed', 'Pending', 'Cancelled') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (train_id) REFERENCES trains(train_id),
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id),
    FOREIGN KEY (from_station_id) REFERENCES stations(station_id),
    FOREIGN KEY (to_station_id) REFERENCES stations(station_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_booking_status (booking_status),
    INDEX idx_travel_date (travel_date)
);

-- Transactions table
CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    transaction_type ENUM('Deposit', 'Withdrawal', 'Booking', 'Refund') NOT NULL,
    description TEXT,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user_id (user_id),
    INDEX idx_transaction_type (transaction_type)
);

-- Notifications table
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,  -- NULL for system-wide notifications
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read)
);

-- Train delay logs
CREATE TABLE delay_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    train_id INT NOT NULL,
    schedule_id INT NOT NULL,
    station_id INT NOT NULL,
    scheduled_time DATETIME NOT NULL,
    actual_time DATETIME NOT NULL,
    delay_minutes INT GENERATED ALWAYS AS 
        (TIMESTAMPDIFF(MINUTE, scheduled_time, actual_time)) STORED,
    reason TEXT,
    reported_by INT,  -- user_id of station master or admin who reported
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (train_id) REFERENCES trains(train_id),
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id),
    FOREIGN KEY (station_id) REFERENCES stations(station_id),
    FOREIGN KEY (reported_by) REFERENCES users(id),
    INDEX idx_train_id (train_id),
    INDEX idx_schedule_id (schedule_id)
);

-- Seat inventory table (to track available seats for each journey)
CREATE TABLE seat_inventory (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    train_id INT NOT NULL,
    schedule_id INT NOT NULL,
    travel_date DATE NOT NULL,
    first_class_available INT NOT NULL,
    second_class_available INT NOT NULL,
    FOREIGN KEY (train_id) REFERENCES trains(train_id),
    FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id),
    UNIQUE KEY (train_id, schedule_id, travel_date),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample users
INSERT INTO users (name, email, password, role, balance) VALUES
('Admin User', 'admin@railconnect.com', '$2y$10$FTq6EuLk3L92uH0dIOMP4.aHhFvKdrISI2TSMiQ5rT6mMM1R1r/aG', 'admin', 0.00),  -- Password: 123
('Station Master Cairo', 'stationmaster@railconnect.com', '$2y$10$FTq6EuLk3L92uH0dIOMP4.aHhFvKdrISI2TSMiQ5rT6mMM1R1r/aG', 'station_master', 0.00),  -- Password: 123
('User One', 'user@railconnect.com', '$2y$10$FTq6EuLk3L92uH0dIOMP4.aHhFvKdrISI2TSMiQ5rT6mMM1R1r/aG', 'user', 1000.00),  -- Password: 123
('User Two', 'user2@railconnect.com', '$2y$10$FTq6EuLk3L92uH0dIOMP4.aHhFvKdrISI2TSMiQ5rT6mMM1R1r/aG', 'user', 50000.00);  -- Password: 123

-- Insert stations
INSERT INTO stations (name, location, description) VALUES
('Saloum', 'Northwest coast', 'Northwestern coastal station'),
('Gargoub', 'Coastal region', 'Coastal region station'),
('Matruh', 'Western coast', 'Major western coastal city station'),
('Siwa Oasis', 'Western Desert', 'Desert oasis terminal station'),
('El-Alamein', 'Northern coast', 'Historical coastal city station'),
('Alexandria', 'Mediterranean coast', 'Major port city station'),
('October', 'Cairo suburbs', 'Modern suburban station'),
('Ain Sokhna', 'Red Sea coast', 'Red Sea resort station'),
('Luxor', 'Upper Egypt', 'Ancient historical city station'),
('Aswan', 'Southern Egypt', 'Southern terminal station');

-- Insert routes
INSERT INTO routes (name, description) VALUES
('Desert Oasis Express', 'Route connecting the coast to Siwa Oasis'),
('Mediterranean Coastal', 'Coastal route from Saloum to Ain Sokhna'),
('Nile Valley Explorer', 'Complete route from Mediterranean to Upper Egypt');

-- Insert route_stations (route 1: Saloum => Gargoub => Matruh => Siwa Oasis)
INSERT INTO route_stations (route_id, station_id, sequence_number, distance_from_start) VALUES
(1, 1, 1, 0),      -- Saloum (start)
(1, 2, 2, 75),     -- Gargoub
(1, 3, 3, 150),    -- Matruh
(1, 4, 4, 300);    -- Siwa Oasis (end)

-- Insert route_stations (route 2: Saloum => Gargoub => Matruh => El-Alamein => Alexandria => October => Ain Sokhna)
INSERT INTO route_stations (route_id, station_id, sequence_number, distance_from_start) VALUES
(2, 1, 1, 0),      -- Saloum (start)
(2, 2, 2, 75),     -- Gargoub
(2, 3, 3, 150),    -- Matruh
(2, 5, 4, 250),    -- El-Alamein
(2, 6, 5, 350),    -- Alexandria
(2, 7, 6, 500),    -- October
(2, 8, 7, 650);    -- Ain Sokhna (end)

-- Insert route_stations (route 3: Saloum => Gargoub => Matruh => El-Alamein => Alexandria => October => Luxor => Aswan)
INSERT INTO route_stations (route_id, station_id, sequence_number, distance_from_start) VALUES
(3, 1, 1, 0),      -- Saloum (start)
(3, 2, 2, 75),     -- Gargoub
(3, 3, 3, 150),    -- Matruh
(3, 5, 4, 250),    -- El-Alamein
(3, 6, 5, 350),    -- Alexandria
(3, 7, 6, 500),    -- October
(3, 9, 7, 850),    -- Luxor
(3, 10, 8, 1050);  -- Aswan (end)

-- Insert trains
INSERT INTO trains (train_number, name, route_id, total_first_class_seats, total_second_class_seats, has_wifi, has_food, has_power_outlets) VALUES
('TR100', 'Desert Explorer', 1, 50, 150, true, true, true),
('TR101', 'Coastal Express', 2, 60, 180, true, true, true),
('TR102', 'Nile Valley', 3, 80, 240, true, true, true),
('TR103', 'Oasis Direct', 1, 40, 160, false, true, false),
('TR104', 'Mediterranean Line', 2, 55, 165, true, false, true),
('TR105', 'Upper Egypt Express', 3, 75, 225, true, true, true);

-- Insert schedules (simplified, just weekday schedules)
INSERT INTO schedules (train_id, day_of_week) VALUES
(1, 'Monday'), (1, 'Wednesday'), (1, 'Friday'),
(2, 'Monday'), (2, 'Tuesday'), (2, 'Thursday'), (2, 'Saturday'),
(3, 'Tuesday'), (3, 'Friday'), (3, 'Sunday'),
(4, 'Tuesday'), (4, 'Thursday'), (4, 'Saturday'),
(5, 'Monday'), (5, 'Wednesday'), (5, 'Friday'), (5, 'Sunday'),
(6, 'Monday'), (6, 'Thursday'), (6, 'Saturday');

-- Insert schedule details for train 1 (Desert Explorer - Route 1)
INSERT INTO schedule_details (schedule_id, station_id, arrival_time, departure_time) VALUES
(1, 1, NULL, '07:00:00'),             -- Saloum (departure only)
(1, 2, '07:45:00', '07:50:00'),       -- Gargoub
(1, 3, '09:00:00', '09:15:00'),       -- Matruh
(1, 4, '12:00:00', NULL);             -- Siwa Oasis (arrival only)

-- Insert schedule details for train 2 (Coastal Express - Route 2)
INSERT INTO schedule_details (schedule_id, station_id, arrival_time, departure_time) VALUES
(4, 1, NULL, '08:30:00'),             -- Saloum (departure only)
(4, 2, '09:15:00', '09:20:00'),       -- Gargoub
(4, 3, '10:30:00', '10:45:00'),       -- Matruh
(4, 5, '12:15:00', '12:25:00'),       -- El-Alamein
(4, 6, '13:45:00', '14:15:00'),       -- Alexandria
(4, 7, '16:30:00', '16:45:00'),       -- October
(4, 8, '19:00:00', NULL);             -- Ain Sokhna (arrival only)

-- Insert schedule details for train 3 (Nile Valley - Route 3)
INSERT INTO schedule_details (schedule_id, station_id, arrival_time, departure_time) VALUES
(8, 1, NULL, '06:00:00'),             -- Saloum (departure only)
(8, 2, '06:45:00', '06:50:00'),       -- Gargoub
(8, 3, '08:00:00', '08:15:00'),       -- Matruh
(8, 5, '09:45:00', '09:55:00'),       -- El-Alamein
(8, 6, '11:15:00', '11:45:00'),       -- Alexandria
(8, 7, '14:00:00', '14:30:00'),       -- October
(8, 9, '20:00:00', '20:30:00'),       -- Luxor
(8, 10, '23:00:00', NULL);            -- Aswan (arrival only)

-- Insert pricing data (simplified, based on distance)
-- Route 1 - Desert Oasis Express
INSERT INTO pricing (route_id, from_station_id, to_station_id, first_class_price, second_class_price) VALUES
(1, 1, 2, 120.00, 60.00),    -- Saloum to Gargoub
(1, 1, 3, 240.00, 120.00),   -- Saloum to Matruh
(1, 1, 4, 480.00, 240.00),   -- Saloum to Siwa Oasis
(1, 2, 3, 120.00, 60.00),    -- Gargoub to Matruh
(1, 2, 4, 360.00, 180.00),   -- Gargoub to Siwa Oasis
(1, 3, 4, 240.00, 120.00);   -- Matruh to Siwa Oasis

-- Route 2 - Mediterranean Coastal
INSERT INTO pricing (route_id, from_station_id, to_station_id, first_class_price, second_class_price) VALUES
(2, 1, 2, 120.00, 60.00),    -- Saloum to Gargoub
(2, 1, 3, 240.00, 120.00),   -- Saloum to Matruh
(2, 1, 5, 400.00, 200.00),   -- Saloum to El-Alamein
(2, 1, 6, 560.00, 280.00),   -- Saloum to Alexandria
(2, 1, 7, 800.00, 400.00),   -- Saloum to October
(2, 1, 8, 1040.00, 520.00),  -- Saloum to Ain Sokhna
(2, 2, 3, 120.00, 60.00),    -- Gargoub to Matruh
(2, 2, 5, 280.00, 140.00),   -- Gargoub to El-Alamein
(2, 2, 6, 440.00, 220.00),   -- Gargoub to Alexandria
(2, 2, 7, 680.00, 340.00),   -- Gargoub to October
(2, 2, 8, 920.00, 460.00),   -- Gargoub to Ain Sokhna
(2, 3, 5, 160.00, 80.00),    -- Matruh to El-Alamein
(2, 3, 6, 320.00, 160.00),   -- Matruh to Alexandria
(2, 3, 7, 560.00, 280.00),   -- Matruh to October
(2, 3, 8, 800.00, 400.00),   -- Matruh to Ain Sokhna
(2, 5, 6, 160.00, 80.00),    -- El-Alamein to Alexandria
(2, 5, 7, 400.00, 200.00),   -- El-Alamein to October
(2, 5, 8, 640.00, 320.00),   -- El-Alamein to Ain Sokhna
(2, 6, 7, 240.00, 120.00),   -- Alexandria to October
(2, 6, 8, 480.00, 240.00),   -- Alexandria to Ain Sokhna
(2, 7, 8, 240.00, 120.00);   -- October to Ain Sokhna

-- Route 3 - Nile Valley Explorer
INSERT INTO pricing (route_id, from_station_id, to_station_id, first_class_price, second_class_price) VALUES
(3, 1, 2, 120.00, 60.00),    -- Saloum to Gargoub
(3, 1, 3, 240.00, 120.00),   -- Saloum to Matruh
(3, 1, 5, 400.00, 200.00),   -- Saloum to El-Alamein
(3, 1, 6, 560.00, 280.00),   -- Saloum to Alexandria
(3, 1, 7, 800.00, 400.00),   -- Saloum to October
(3, 1, 9, 1360.00, 680.00),  -- Saloum to Luxor
(3, 1, 10, 1680.00, 840.00), -- Saloum to Aswan
(3, 7, 9, 560.00, 280.00),   -- October to Luxor
(3, 7, 10, 880.00, 440.00),  -- October to Aswan
(3, 9, 10, 320.00, 160.00);  -- Luxor to Aswan

-- Insert sample seat inventory
INSERT INTO seat_inventory (train_id, schedule_id, travel_date, first_class_available, second_class_available) VALUES
(1, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 49, 150),  -- One first class seat booked
(2, 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY), 60, 178),  -- Two second class seats booked
(3, 8, DATE_ADD(CURDATE(), INTERVAL 10 DAY), 79, 240); -- One first class seat booked

-- Insert some sample train status updates
INSERT INTO train_status (train_id, schedule_id, current_station_id, next_station_id, status, delay_minutes, expected_arrival) VALUES
(1, 1, 2, 3, 'Departed', 0, DATE_ADD(NOW(), INTERVAL 1 HOUR)),
(2, 4, 3, 5, 'Delayed', 15, DATE_ADD(NOW(), INTERVAL 75 MINUTE)),
(3, 8, 1, 2, 'On Time', 0, DATE_ADD(NOW(), INTERVAL 45 MINUTE));

-- Insert some sample bookings
INSERT INTO bookings (user_id, train_id, schedule_id, from_station_id, to_station_id, booking_date, travel_date, travel_class, ticket_count, total_amount, booking_status) VALUES
(3, 1, 1, 1, 4, DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'first', 1, 480.00, 'Confirmed'),
(3, 2, 4, 1, 6, DATE_SUB(CURDATE(), INTERVAL 3 DAY), DATE_ADD(CURDATE(), INTERVAL 5 DAY), 'second', 2, 560.00, 'Confirmed'),
(4, 3, 8, 6, 10, DATE_SUB(CURDATE(), INTERVAL 7 DAY), DATE_ADD(CURDATE(), INTERVAL 10 DAY), 'first', 1, 1120.00, 'Confirmed');

-- Insert some sample transactions
INSERT INTO transactions (user_id, amount, transaction_type, description) VALUES
(3, 5000.00, 'Deposit', 'Initial account deposit'),
(3, -480.00, 'Booking', 'Booking'),
(3, -560.00, 'Booking', 'Booking'),
(4, 3500.00, 'Deposit', 'Initial account deposit'),
(4, -1120.00, 'Booking', 'Booking');

-- Insert some sample notifications
INSERT INTO notifications (user_id, title, message) VALUES
(3, 'Booking Confirmed', 'Your booking has been confirmed.'),
(3, 'Booking Confirmed', 'Your booking has been confirmed.'),
(4, 'Booking Confirmed', 'Your booking has been confirmed.'),
(NULL, 'System Maintenance', 'The system will undergo maintenance on Sunday from 2 AM to 4 AM.');

-- Insert delay log with computed delay_minutes
INSERT INTO delay_logs (train_id, schedule_id, station_id, scheduled_time, actual_time, reason, reported_by) VALUES
(2, 4, 3, DATE_ADD(CURDATE(), INTERVAL '10:30' HOUR_MINUTE), DATE_ADD(CURDATE(), INTERVAL '10:45' HOUR_MINUTE), 'Signal failure at Matruh station', 2);
