-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 03:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `railway_sysdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `train_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `from_station_id` int(11) NOT NULL,
  `to_station_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `travel_date` date NOT NULL,
  `travel_class` enum('first','second') NOT NULL,
  `ticket_count` int(11) NOT NULL DEFAULT 1,
  `total_amount` decimal(10,2) NOT NULL,
  `booking_status` enum('Confirmed','Pending','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pnr_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `train_id`, `schedule_id`, `from_station_id`, `to_station_id`, `booking_date`, `travel_date`, `travel_class`, `ticket_count`, `total_amount`, `booking_status`, `created_at`, `pnr_number`) VALUES
(1, 3, 1, 1, 1, 4, '2025-04-27', '2025-05-04', 'first', 1, 480.00, 'Confirmed', '2025-05-02 14:53:44', 'PNR18745621'),
(2, 3, 2, 4, 1, 6, '2025-04-29', '2025-05-07', 'second', 2, 560.00, 'Confirmed', '2025-05-02 14:53:44', 'PNR18745622'),
(3, 4, 3, 8, 6, 10, '2025-04-25', '2025-05-12', 'first', 1, 1120.00, 'Confirmed', '2025-05-02 14:53:44', 'PNR18745623');

-- --------------------------------------------------------

--
-- Table structure for table `delay_logs`
--

CREATE TABLE `delay_logs` (
  `log_id` int(11) NOT NULL,
  `train_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `scheduled_time` datetime NOT NULL,
  `actual_time` datetime NOT NULL,
  `delay_minutes` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `reported_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delay_logs`
--

INSERT INTO `delay_logs` (`log_id`, `train_id`, `schedule_id`, `station_id`, `scheduled_time`, `actual_time`, `delay_minutes`, `reason`, `reported_by`, `created_at`) VALUES
(1, 2, 4, 3, '2025-05-02 10:30:00', '2025-05-02 00:10:00', 15, 'Signal failure at Matruh station', 2, '2025-05-02 14:53:44');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 3, 'Booking Confirmed', 'Your booking (PNR18745621) has been confirmed.', 0, '2025-05-02 14:53:44'),
(2, 3, 'Booking Confirmed', 'Your booking (PNR18745622) has been confirmed.', 0, '2025-05-02 14:53:44'),
(3, 4, 'Booking Confirmed', 'Your booking (PNR18745623) has been confirmed.', 0, '2025-05-02 14:53:44'),
(4, NULL, 'System Maintenance', 'The system will undergo maintenance on Sunday from 2 AM to 4 AM.', 0, '2025-05-02 14:53:44');

-- --------------------------------------------------------

--
-- Table structure for table `pricing`
--

CREATE TABLE `pricing` (
  `pricing_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `from_station_id` int(11) NOT NULL,
  `to_station_id` int(11) NOT NULL,
  `first_class_price` decimal(10,2) NOT NULL,
  `second_class_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pricing`
--

INSERT INTO `pricing` (`pricing_id`, `route_id`, `from_station_id`, `to_station_id`, `first_class_price`, `second_class_price`) VALUES
(1, 1, 1, 2, 120.00, 60.00),
(2, 1, 1, 3, 240.00, 120.00),
(3, 1, 1, 4, 480.00, 240.00),
(4, 1, 2, 3, 120.00, 60.00),
(5, 1, 2, 4, 360.00, 180.00),
(6, 1, 3, 4, 240.00, 120.00),
(7, 2, 1, 2, 120.00, 60.00),
(8, 2, 1, 3, 240.00, 120.00),
(9, 2, 1, 5, 400.00, 200.00),
(10, 2, 1, 6, 560.00, 280.00),
(11, 2, 1, 7, 800.00, 400.00),
(12, 2, 1, 8, 1040.00, 520.00),
(13, 2, 2, 3, 120.00, 60.00),
(14, 2, 2, 5, 280.00, 140.00),
(15, 2, 2, 6, 440.00, 220.00),
(16, 2, 2, 7, 680.00, 340.00),
(17, 2, 2, 8, 920.00, 460.00),
(18, 2, 3, 5, 160.00, 80.00),
(19, 2, 3, 6, 320.00, 160.00),
(20, 2, 3, 7, 560.00, 280.00),
(21, 2, 3, 8, 800.00, 400.00),
(22, 2, 5, 6, 160.00, 80.00),
(23, 2, 5, 7, 400.00, 200.00),
(24, 2, 5, 8, 640.00, 320.00),
(25, 2, 6, 7, 240.00, 120.00),
(26, 2, 6, 8, 480.00, 240.00),
(27, 2, 7, 8, 240.00, 120.00),
(28, 3, 1, 2, 120.00, 60.00),
(29, 3, 1, 3, 240.00, 120.00),
(30, 3, 1, 5, 400.00, 200.00),
(31, 3, 1, 6, 560.00, 280.00),
(32, 3, 1, 7, 800.00, 400.00),
(33, 3, 1, 9, 1360.00, 680.00),
(34, 3, 1, 10, 1680.00, 840.00),
(35, 3, 7, 9, 560.00, 280.00),
(36, 3, 7, 10, 880.00, 440.00),
(37, 3, 9, 10, 320.00, 160.00);

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `route_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`route_id`, `name`, `description`, `is_active`) VALUES
(1, 'Desert Oasis Express', 'Route connecting the coast to Siwa Oasis', 1),
(2, 'Mediterranean Coastal', 'Coastal route from Saloum to Ain Sokhna', 1),
(3, 'Nile Valley Explorer', 'Complete route from Mediterranean to Upper Egypt', 1);

-- --------------------------------------------------------

--
-- Table structure for table `route_stations`
--

CREATE TABLE `route_stations` (
  `id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `sequence_number` int(11) NOT NULL,
  `distance_from_start` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `route_stations`
--

INSERT INTO `route_stations` (`id`, `route_id`, `station_id`, `sequence_number`, `distance_from_start`) VALUES
(1, 1, 1, 1, 0.00),
(2, 1, 2, 2, 75.00),
(3, 1, 3, 3, 150.00),
(4, 1, 4, 4, 300.00),
(5, 2, 1, 1, 0.00),
(6, 2, 2, 2, 75.00),
(7, 2, 3, 3, 150.00),
(8, 2, 5, 4, 250.00),
(9, 2, 6, 5, 350.00),
(10, 2, 7, 6, 500.00),
(11, 2, 8, 7, 650.00),
(12, 3, 1, 1, 0.00),
(13, 3, 2, 2, 75.00),
(14, 3, 3, 3, 150.00),
(15, 3, 5, 4, 250.00),
(16, 3, 6, 5, 350.00),
(17, 3, 7, 6, 500.00),
(18, 3, 9, 7, 850.00),
(19, 3, 10, 8, 1050.00);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(11) NOT NULL,
  `train_id` int(11) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`schedule_id`, `train_id`, `day_of_week`, `is_active`) VALUES
(1, 1, 'Monday', 1),
(2, 1, 'Wednesday', 1),
(3, 1, 'Friday', 1),
(4, 2, 'Monday', 1),
(5, 2, 'Tuesday', 1),
(6, 2, 'Thursday', 1),
(7, 2, 'Saturday', 1),
(8, 3, 'Tuesday', 1),
(9, 3, 'Friday', 1),
(10, 3, 'Sunday', 1),
(11, 4, 'Tuesday', 1),
(12, 4, 'Thursday', 1),
(13, 4, 'Saturday', 1),
(14, 5, 'Monday', 1),
(15, 5, 'Wednesday', 1),
(16, 5, 'Friday', 1),
(17, 5, 'Sunday', 1),
(18, 6, 'Monday', 1),
(19, 6, 'Thursday', 1),
(20, 6, 'Saturday', 1);

-- --------------------------------------------------------

--
-- Table structure for table `schedule_details`
--

CREATE TABLE `schedule_details` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `arrival_time` time DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `day_offset` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_details`
--

INSERT INTO `schedule_details` (`id`, `schedule_id`, `station_id`, `arrival_time`, `departure_time`, `day_offset`) VALUES
(1, 1, 1, NULL, '07:00:00', 0),
(2, 1, 2, '07:45:00', '07:50:00', 0),
(3, 1, 3, '09:00:00', '09:15:00', 0),
(4, 1, 4, '12:00:00', NULL, 0),
(5, 4, 1, NULL, '08:30:00', 0),
(6, 4, 2, '09:15:00', '09:20:00', 0),
(7, 4, 3, '10:30:00', '10:45:00', 0),
(8, 4, 5, '12:15:00', '12:25:00', 0),
(9, 4, 6, '13:45:00', '14:15:00', 0),
(10, 4, 7, '16:30:00', '16:45:00', 0),
(11, 4, 8, '19:00:00', NULL, 0),
(12, 8, 1, NULL, '06:00:00', 0),
(13, 8, 2, '06:45:00', '06:50:00', 0),
(14, 8, 3, '08:00:00', '08:15:00', 0),
(15, 8, 5, '09:45:00', '09:55:00', 0),
(16, 8, 6, '11:15:00', '11:45:00', 0),
(17, 8, 7, '14:00:00', '14:30:00', 0),
(18, 8, 9, '20:00:00', '20:30:00', 0),
(19, 8, 10, '23:00:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `station_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`station_id`, `name`, `location`, `description`, `is_active`) VALUES
(1, 'Saloum', 'Northwest coast', 'Northwestern coastal station', 1),
(2, 'Gargoub', 'Coastal region', 'Coastal region station', 1),
(3, 'Matruh', 'Western coast', 'Major western coastal city station', 1),
(4, 'Siwa Oasis', 'Western Desert', 'Desert oasis terminal station', 1),
(5, 'El-Alamein', 'Northern coast', 'Historical coastal city station', 1),
(6, 'Alexandria', 'Mediterranean coast', 'Major port city station', 1),
(7, 'October', 'Cairo suburbs', 'Modern suburban station', 1),
(8, 'Ain Sokhna', 'Red Sea coast', 'Red Sea resort station', 1),
(9, 'Luxor', 'Upper Egypt', 'Ancient historical city station', 1),
(10, 'Aswan', 'Southern Egypt', 'Southern terminal station', 1);

-- --------------------------------------------------------

--
-- Table structure for table `trains`
--

CREATE TABLE `trains` (
  `train_id` int(11) NOT NULL,
  `train_number` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `route_id` int(11) NOT NULL,
  `total_first_class_seats` int(11) NOT NULL DEFAULT 0,
  `total_second_class_seats` int(11) NOT NULL DEFAULT 0,
  `has_wifi` tinyint(1) DEFAULT 0,
  `has_food` tinyint(1) DEFAULT 0,
  `has_power_outlets` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trains`
--

INSERT INTO `trains` (`train_id`, `train_number`, `name`, `route_id`, `total_first_class_seats`, `total_second_class_seats`, `has_wifi`, `has_food`, `has_power_outlets`, `is_active`) VALUES
(1, 'TR100', 'Desert Explorer', 1, 50, 150, 1, 1, 1, 1),
(2, 'TR101', 'Coastal Express', 2, 60, 180, 1, 1, 1, 1),
(3, 'TR102', 'Nile Valley', 3, 80, 240, 1, 1, 1, 1),
(4, 'TR103', 'Oasis Direct', 1, 40, 160, 0, 1, 0, 1),
(5, 'TR104', 'Mediterranean Line', 2, 55, 165, 1, 0, 1, 1),
(6, 'TR105', 'Upper Egypt Express', 3, 75, 225, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `train_status`
--

CREATE TABLE `train_status` (
  `status_id` int(11) NOT NULL,
  `train_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `current_station_id` int(11) DEFAULT NULL,
  `next_station_id` int(11) DEFAULT NULL,
  `status` enum('On Time','Delayed','Cancelled','Arrived','Departed') DEFAULT 'On Time',
  `delay_minutes` int(11) DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expected_arrival` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `train_status`
--

INSERT INTO `train_status` (`status_id`, `train_id`, `schedule_id`, `current_station_id`, `next_station_id`, `status`, `delay_minutes`, `last_updated`, `expected_arrival`) VALUES
(1, 1, 1, 2, 3, 'Departed', 0, '2025-05-02 14:53:44', '2025-05-02 18:53:44'),
(2, 2, 4, 3, 5, 'Delayed', 15, '2025-05-02 14:53:44', '2025-05-02 19:08:44'),
(3, 3, 8, 1, 2, 'On Time', 0, '2025-05-02 14:53:44', '2025-05-02 18:38:44');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type` enum('Deposit','Withdrawal','Booking','Refund') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `user_id`, `amount`, `transaction_type`, `reference_id`, `description`, `transaction_date`) VALUES
(1, 3, 5000.00, 'Deposit', NULL, 'Initial account deposit', '2025-05-02 14:53:44'),
(2, 3, -480.00, 'Booking', 1, 'Booking PNR18745621', '2025-05-02 14:53:44'),
(3, 3, -560.00, 'Booking', 2, 'Booking PNR18745622', '2025-05-02 14:53:44'),
(4, 4, 3500.00, 'Deposit', NULL, 'Initial account deposit', '2025-05-02 14:53:44'),
(5, 4, -1120.00, 'Booking', 3, 'Booking PNR18745623', '2025-05-02 14:53:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','station_master') NOT NULL DEFAULT 'user',
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `balance`, `created_at`, `is_deleted`) VALUES
(1, 'Admin User', 'admin@railconnect.com', '$2y$10$5Nppd9WhawxR.nNsjKR1neOPN6AD6X9QVk6hBtQXRR3drWj1QkMJ2', 'admin', 0.00, '2025-05-02 14:53:43', 0),
(2, 'Station Master Cairo', 'stationmaster@railconnect.com', '$2y$10$x9uC.Cf1/PVIuvJ1tOU/L.ZmkBwK/STKRejZ0ORBN.K3rpXaKj.Aq', 'station_master', 0.00, '2025-05-02 14:53:43', 0),
(3, 'John Smith', 'john@example.com', '$2y$10$IE0TdnIsFQR2SL1rQJ3WnuyqGCvbrmwnTZTTA3AJkEjO2YI/kPN0m', 'user', 5000.00, '2025-05-02 14:53:43', 0),
(4, 'Maria Garcia', 'maria@example.com', '$2y$10$ZZJZOmzYct6HfBmcHuPO9ucENH2FS4hyMi2KPgJ3z3EaLUJBtl2Xe', 'user', 3500.00, '2025-05-02 14:53:43', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD UNIQUE KEY `pnr_number` (`pnr_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `train_id` (`train_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `from_station_id` (`from_station_id`),
  ADD KEY `to_station_id` (`to_station_id`);

--
-- Indexes for table `delay_logs`
--
ALTER TABLE `delay_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `train_id` (`train_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `station_id` (`station_id`),
  ADD KEY `reported_by` (`reported_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pricing`
--
ALTER TABLE `pricing`
  ADD PRIMARY KEY (`pricing_id`),
  ADD UNIQUE KEY `route_id` (`route_id`,`from_station_id`,`to_station_id`),
  ADD KEY `from_station_id` (`from_station_id`),
  ADD KEY `to_station_id` (`to_station_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `route_stations`
--
ALTER TABLE `route_stations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `route_id` (`route_id`,`station_id`),
  ADD UNIQUE KEY `route_id_2` (`route_id`,`sequence_number`),
  ADD KEY `station_id` (`station_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `train_id` (`train_id`);

--
-- Indexes for table `schedule_details`
--
ALTER TABLE `schedule_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `station_id` (`station_id`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`station_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `trains`
--
ALTER TABLE `trains`
  ADD PRIMARY KEY (`train_id`),
  ADD UNIQUE KEY `train_number` (`train_number`),
  ADD KEY `route_id` (`route_id`);

--
-- Indexes for table `train_status`
--
ALTER TABLE `train_status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `train_id` (`train_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `current_station_id` (`current_station_id`),
  ADD KEY `next_station_id` (`next_station_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `delay_logs`
--
ALTER TABLE `delay_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pricing`
--
ALTER TABLE `pricing`
  MODIFY `pricing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `route_stations`
--
ALTER TABLE `route_stations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `schedule_details`
--
ALTER TABLE `schedule_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
  MODIFY `station_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `trains`
--
ALTER TABLE `trains`
  MODIFY `train_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `train_status`
--
ALTER TABLE `train_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`train_id`) REFERENCES `trains` (`train_id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`),
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`from_station_id`) REFERENCES `stations` (`station_id`),
  ADD CONSTRAINT `bookings_ibfk_5` FOREIGN KEY (`to_station_id`) REFERENCES `stations` (`station_id`);

--
-- Constraints for table `delay_logs`
--
ALTER TABLE `delay_logs`
  ADD CONSTRAINT `delay_logs_ibfk_1` FOREIGN KEY (`train_id`) REFERENCES `trains` (`train_id`),
  ADD CONSTRAINT `delay_logs_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`),
  ADD CONSTRAINT `delay_logs_ibfk_3` FOREIGN KEY (`station_id`) REFERENCES `stations` (`station_id`),
  ADD CONSTRAINT `delay_logs_ibfk_4` FOREIGN KEY (`reported_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `pricing`
--
ALTER TABLE `pricing`
  ADD CONSTRAINT `pricing_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`),
  ADD CONSTRAINT `pricing_ibfk_2` FOREIGN KEY (`from_station_id`) REFERENCES `stations` (`station_id`),
  ADD CONSTRAINT `pricing_ibfk_3` FOREIGN KEY (`to_station_id`) REFERENCES `stations` (`station_id`);

--
-- Constraints for table `route_stations`
--
ALTER TABLE `route_stations`
  ADD CONSTRAINT `route_stations_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `route_stations_ibfk_2` FOREIGN KEY (`station_id`) REFERENCES `stations` (`station_id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`train_id`) REFERENCES `trains` (`train_id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule_details`
--
ALTER TABLE `schedule_details`
  ADD CONSTRAINT `schedule_details_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_details_ibfk_2` FOREIGN KEY (`station_id`) REFERENCES `stations` (`station_id`);

--
-- Constraints for table `trains`
--
ALTER TABLE `trains`
  ADD CONSTRAINT `trains_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`);

--
-- Constraints for table `train_status`
--
ALTER TABLE `train_status`
  ADD CONSTRAINT `train_status_ibfk_1` FOREIGN KEY (`train_id`) REFERENCES `trains` (`train_id`),
  ADD CONSTRAINT `train_status_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`),
  ADD CONSTRAINT `train_status_ibfk_3` FOREIGN KEY (`current_station_id`) REFERENCES `stations` (`station_id`),
  ADD CONSTRAINT `train_status_ibfk_4` FOREIGN KEY (`next_station_id`) REFERENCES `stations` (`station_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
