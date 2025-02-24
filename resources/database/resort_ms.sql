-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 05:21 PM
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
-- Database: `resort_ms`
--
CREATE DATABASE IF NOT EXISTS `resort_ms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `resort_ms`;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `status` enum('activate','deactivate') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(5, 'levipenaverde@example.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'admin', 'activate', '2025-02-14 15:10:14', '2025-02-14 15:10:14'),
(9, 'tolits@example.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'admin', 'activate', '2025-02-15 05:24:00', '2025-02-23 14:46:27'),
(10, 'allan@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'user', 'activate', '2025-02-16 10:43:34', '2025-02-24 15:43:24'),
(12, 'catuera@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'user', 'activate', '2025-02-23 15:06:58', '2025-02-24 16:15:00'),
(13, 'ego@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'admin', 'activate', '2025-02-24 15:25:09', '2025-02-24 15:29:38');

-- --------------------------------------------------------

--
-- Table structure for table `account_notification`
--

CREATE TABLE IF NOT EXISTS `account_notification` (
  `account_id` int(11) NOT NULL,
  `account_notification` enum('create','update') NOT NULL,
  KEY `account_notification_fk` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_notification`
--

INSERT INTO `account_notification` (`account_id`, `account_notification`) VALUES
(13, 'create'),
(13, 'update'),
(10, 'update'),
(12, 'update');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE IF NOT EXISTS `booking` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `book_status` enum('pending','confirmed','cancelled') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`book_id`),
  KEY `book_room_fk` (`room_id`),
  KEY `book_account_fk` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`book_id`, `account_id`, `room_id`, `check_in`, `check_out`, `book_status`, `price`, `created_at`) VALUES
(2, 9, 14, '2025-02-23', '2025-02-28', 'pending', 5000.00, '2025-02-23 23:36:52');

-- --------------------------------------------------------

--
-- Stand-in structure for view `book_payment`
-- (See below for the actual view)
--
CREATE TABLE IF NOT EXISTS `book_payment` (
`ID` int(11)
,`name` varchar(101)
,`amount` decimal(10,2)
,`payment_method` enum('cash','credit card','e-payment')
,`payment_status` enum('pending','completed','refunded')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `customer_booking`
-- (See below for the actual view)
--
CREATE TABLE IF NOT EXISTS `customer_booking` (
`id` int(11)
,`fname` varchar(50)
,`lname` varchar(50)
,`room_code` varchar(50)
,`check_in` date
,`check_out` date
,`status` enum('pending','confirmed','cancelled')
,`price` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `book_id` int(11) NOT NULL,
  `payment_method` enum('cash','credit card','e-payment') NOT NULL,
  `payment_status` enum('pending','completed','refunded') NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `payment_book_fk` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`book_id`, `payment_method`, `payment_status`, `payment_date`) VALUES
(2, 'cash', 'completed', '2025-02-22 04:29:38');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_code` varchar(50) NOT NULL,
  `room_type` enum('standard','premium') NOT NULL,
  `room_status` enum('available','booked','under maintenance') NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_ code` (`room_code`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_code`, `room_type`, `room_status`, `price`, `created_at`, `updated_at`) VALUES
(9, 'room101', 'premium', 'available', 500, '2025-02-15 15:37:50', '2025-02-22 03:20:03'),
(12, 'room102', 'premium', 'available', 1000, '2025-02-19 12:40:06', '2025-02-23 15:29:13'),
(14, 'room103', 'premium', 'under maintenance', 5000, '2025-02-22 03:31:37', '2025-02-22 03:31:37');

-- --------------------------------------------------------

--
-- Table structure for table `room_gallery`
--

CREATE TABLE IF NOT EXISTS `room_gallery` (
  `room_id` int(11) NOT NULL,
  `room_img` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `galley_room_fk` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_gallery`
--

INSERT INTO `room_gallery` (`room_id`, `room_img`, `created_at`) VALUES
(9, '167b0bf3c6b747.png', '2025-02-15 16:22:20'),
(9, '167b2d9635b18f.png', '2025-02-17 06:38:27'),
(9, '167b2d9635c32e.png', '2025-02-17 06:38:27'),
(12, '167b5d126d3e1d.png', '2025-02-19 12:40:06'),
(12, '167b5d126d7179.png', '2025-02-19 12:40:06'),
(12, '167b5d126d795e.png', '2025-02-19 12:40:06'),
(14, '167b945193908d.png', '2025-02-22 03:31:37'),
(14, '167b945193958b.png', '2025-02-22 03:31:37');

-- --------------------------------------------------------

--
-- Table structure for table `room_notification`
--

CREATE TABLE IF NOT EXISTS `room_notification` (
  `room_id` int(11) NOT NULL,
  `room_notification` enum('create','update') NOT NULL,
  KEY `room_notification_fk` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `contact` varchar(15) NOT NULL,
  `profile_img` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `name` (`fname`),
  UNIQUE KEY `contact` (`contact`),
  KEY `user_account_fk` (`account_id`),
  KEY `unique_fullname` (`fname`,`lname`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `account_id`, `fname`, `lname`, `age`, `gender`, `contact`, `profile_img`, `created_at`, `updated_at`) VALUES
(5, 5, 'Levi', 'Penaverde', 20, 'male', '987867546', '67af5cd6192634.90705227.jpg', '2025-02-14 15:10:14', '2025-02-21 15:03:19'),
(9, 9, 'Angelito', 'Jacalan', 21, 'male', '09876544461', '67b195f85239c1.78592261.jpg', '2025-02-15 05:24:00', '2025-02-23 14:46:27'),
(10, 10, 'Allan', 'Monforte', 20, 'male', '9112245667', '67b1c15630d272.47796055.png', '2025-02-16 10:43:34', '2025-02-24 15:43:24'),
(12, 12, 'Melvin', 'Catuera', 20, 'male', '9123456789', '67bc9b041413e9.81245551.png', '2025-02-23 15:06:58', '2025-02-24 16:15:00'),
(13, 13, 'Ianzae', 'Ego', 25, 'female', '9876543211', '67bc8f552ae286.40054461.png', '2025-02-24 15:25:09', '2025-02-24 15:29:38');

-- --------------------------------------------------------

--
-- Structure for view `book_payment`
--
DROP TABLE IF EXISTS `book_payment`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `book_payment`  AS SELECT `booking`.`book_id` AS `ID`, concat(`user`.`fname`,' ',`user`.`lname`) AS `name`, `booking`.`price` AS `amount`, `payments`.`payment_method` AS `payment_method`, `payments`.`payment_status` AS `payment_status` FROM ((((`account` join `user` on(`account`.`account_id` = `user`.`account_id`)) join `booking` on(`account`.`account_id` = `booking`.`account_id`)) join `room` on(`booking`.`room_id` = `room`.`room_id`)) join `payments` on(`booking`.`book_id` = `payments`.`book_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `customer_booking`
--
DROP TABLE IF EXISTS `customer_booking`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `customer_booking`  AS SELECT `b`.`book_id` AS `id`, `u`.`fname` AS `fname`, `u`.`lname` AS `lname`, `r`.`room_code` AS `room_code`, `b`.`check_in` AS `check_in`, `b`.`check_out` AS `check_out`, `b`.`book_status` AS `status`, `b`.`price` AS `price` FROM (((`booking` `b` join `account` on(`b`.`account_id` = `account`.`account_id`)) join `user` `u` on(`b`.`account_id` = `u`.`account_id`)) join `room` `r` on(`b`.`room_id` = `r`.`room_id`)) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_notification`
--
ALTER TABLE `account_notification`
  ADD CONSTRAINT `account_notification_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `book_account_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `book_room_fk` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payment_book_fk` FOREIGN KEY (`book_id`) REFERENCES `booking` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_gallery`
--
ALTER TABLE `room_gallery`
  ADD CONSTRAINT `galley_room_fk` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_notification`
--
ALTER TABLE `room_notification`
  ADD CONSTRAINT `room_notification_fk` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_account_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
