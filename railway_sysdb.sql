-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 05:09 PM
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
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`ID`, `Name`) VALUES
(8, 'Ain Sokhna'),
(6, 'Alexandria'),
(10, 'Aswan'),
(5, 'El-Alamein'),
(2, 'Gargoub'),
(11, 'Hurghada'),
(9, 'Luxor'),
(4, 'Matruh'),
(7, 'October'),
(3, 'Sallum'),
(1, 'Siwa Oasis');

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `province`
--

INSERT INTO `province` (`ID`, `Name`) VALUES
(2, 'Alexandria'),
(6, 'Aswan'),
(3, 'Cairo'),
(5, 'Luxor'),
(1, 'Marsa Matruh'),
(4, 'Suez');

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route_station`
--

CREATE TABLE `route_station` (
  `ID` int(11) NOT NULL,
  `Route_ID` int(11) NOT NULL,
  `Station_ID` int(11) NOT NULL,
  `Arrival_Time` datetime DEFAULT NULL,
  `Departure_Time` datetime DEFAULT NULL,
  `Order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `station`
--

CREATE TABLE `station` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `City_ID` int(11) DEFAULT NULL,
  `Province_ID` int(11) DEFAULT NULL,
  `Latitude` float DEFAULT NULL,
  `Longitude` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `station`
--

INSERT INTO `station` (`ID`, `Name`, `City_ID`, `Province_ID`, `Latitude`, `Longitude`) VALUES
(1, 'Sallum', 3, 1, NULL, NULL),
(2, 'Gargoub', 2, 1, NULL, NULL),
(3, 'Siwa Oasis', 1, 1, NULL, NULL),
(4, 'Matruh', 4, 1, NULL, NULL),
(5, 'El-Alamein', 5, 2, NULL, NULL),
(6, 'Alexandria', 6, 2, NULL, NULL),
(7, 'October', 7, 3, NULL, NULL),
(8, 'Ain Sokhna', 8, 2, NULL, NULL),
(9, 'Luxor', 9, 5, NULL, NULL),
(10, 'Aswan', 10, 6, NULL, NULL),
(11, 'Hurghada', 11, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `ID` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `From_Station_ID` int(11) DEFAULT NULL,
  `To_Station_ID` int(11) DEFAULT NULL,
  `Train_ID` int(11) DEFAULT NULL,
  `Travel_Date` datetime NOT NULL,
  `Seat_Number` int(11) DEFAULT NULL,
  `Status` enum('Booked','Cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `train`
--

CREATE TABLE `train` (
  `ID` int(11) NOT NULL,
  `Train_Number` varchar(50) NOT NULL,
  `Type` enum('High','Medium','Low','Sleeper') NOT NULL,
  `Capacity` int(11) DEFAULT NULL,
  `Route_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainlocation`
--

CREATE TABLE `trainlocation` (
  `ID` int(11) NOT NULL,
  `Train_ID` int(11) NOT NULL,
  `Current_Lat` float NOT NULL,
  `Current_Lng` float NOT NULL,
  `Speed` float DEFAULT NULL,
  `Timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('user','admin','station_master') NOT NULL DEFAULT 'user',
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `balance`) VALUES
(4, 'ahmd', 'ahmd1@gmail.com', '$2y$10$w7urc8DwP0BZeBHqgv48aOCzBhKP9OeBPOGhsQk2xPNDCOeYX2m.i', 'user', 0.00),
(5, 'sex', 'sex@gmail.com', '$2y$10$LBhmNmTCUWL8arqP8.dPMuMN1MdMZ.pdNnKGXf7oEuWIydMly6.0i', 'user', 0.00),
(6, 'mrmr', 'mrmr@gmail.com', '$2y$10$giKFl7fisvjBDWqjULzK9e8GkFQ9NYGwRxcyqukHvoUe9zjDSfjlS', 'admin', 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `route_station`
--
ALTER TABLE `route_station`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Route_ID` (`Route_ID`),
  ADD KEY `Station_ID` (`Station_ID`);

--
-- Indexes for table `station`
--
ALTER TABLE `station`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `City_ID` (`City_ID`),
  ADD KEY `Province_ID` (`Province_ID`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `From_Station_ID` (`From_Station_ID`),
  ADD KEY `To_Station_ID` (`To_Station_ID`),
  ADD KEY `Train_ID` (`Train_ID`);

--
-- Indexes for table `train`
--
ALTER TABLE `train`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Train_Number` (`Train_Number`),
  ADD KEY `Route_ID` (`Route_ID`);

--
-- Indexes for table `trainlocation`
--
ALTER TABLE `trainlocation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Train_ID` (`Train_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `province`
--
ALTER TABLE `province`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `route_station`
--
ALTER TABLE `route_station`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `station`
--
ALTER TABLE `station`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `train`
--
ALTER TABLE `train`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainlocation`
--
ALTER TABLE `trainlocation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `route_station`
--
ALTER TABLE `route_station`
  ADD CONSTRAINT `route_station_ibfk_1` FOREIGN KEY (`Route_ID`) REFERENCES `route` (`ID`),
  ADD CONSTRAINT `route_station_ibfk_2` FOREIGN KEY (`Station_ID`) REFERENCES `station` (`ID`);

--
-- Constraints for table `station`
--
ALTER TABLE `station`
  ADD CONSTRAINT `station_ibfk_1` FOREIGN KEY (`City_ID`) REFERENCES `city` (`ID`),
  ADD CONSTRAINT `station_ibfk_2` FOREIGN KEY (`Province_ID`) REFERENCES `province` (`ID`);

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`From_Station_ID`) REFERENCES `station` (`ID`),
  ADD CONSTRAINT `ticket_ibfk_3` FOREIGN KEY (`To_Station_ID`) REFERENCES `station` (`ID`),
  ADD CONSTRAINT `ticket_ibfk_4` FOREIGN KEY (`Train_ID`) REFERENCES `train` (`ID`);

--
-- Constraints for table `train`
--
ALTER TABLE `train`
  ADD CONSTRAINT `train_ibfk_1` FOREIGN KEY (`Route_ID`) REFERENCES `route` (`ID`);

--
-- Constraints for table `trainlocation`
--
ALTER TABLE `trainlocation`
  ADD CONSTRAINT `trainlocation_ibfk_1` FOREIGN KEY (`Train_ID`) REFERENCES `train` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
