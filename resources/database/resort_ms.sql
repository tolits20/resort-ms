-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 03:59 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(5, 'levipenaverde@example.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'admin', 'activate', '2025-02-14 15:10:14', '2025-02-14 15:10:14'),
(9, 'tolits@example.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'admin', 'activate', '2025-02-15 05:24:00', '2025-02-28 03:53:06'),
(10, 'allan@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'admin', 'activate', '2025-02-16 10:43:34', '2025-02-27 13:42:18'),
(12, 'catuera@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'user', 'activate', '2025-02-23 15:06:58', '2025-02-25 04:30:37'),
(13, 'ego@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'admin', 'deactivate', '2025-02-24 15:25:09', '2025-02-25 01:32:39'),
(27, 'user@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', 'user', 'activate', '2025-02-26 16:17:06', NULL),
(36, 'levi@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', 'user', 'activate', '2025-02-28 04:01:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_notification`
--

CREATE TABLE IF NOT EXISTS `account_notification` (
  `cnotif_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `account_notification` enum('create','update') NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cnotif_id`),
  KEY `account_notification_fk` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_notification`
--

INSERT INTO `account_notification` (`cnotif_id`, `account_id`, `account_notification`, `Date`) VALUES
(1, 13, 'update', '2025-02-25 09:32:39'),
(2, 10, 'update', '2025-02-25 09:41:55'),
(4, 10, 'update', '2025-02-25 09:47:19'),
(5, 12, 'update', '2025-02-25 11:35:03'),
(6, 12, 'update', '2025-02-25 12:30:37'),
(7, 9, 'update', '2025-02-25 16:15:56'),
(8, 10, 'update', '2025-02-26 08:15:13'),
(9, 27, 'create', '2025-02-27 00:17:06'),
(25, 10, 'update', '2025-02-27 21:37:20'),
(26, 10, 'update', '2025-02-27 21:37:24'),
(27, 10, 'update', '2025-02-27 21:37:31'),
(28, 10, 'update', '2025-02-27 21:38:04'),
(29, 10, 'update', '2025-02-27 21:41:47'),
(30, 10, 'update', '2025-02-27 21:42:04'),
(31, 10, 'update', '2025-02-27 21:42:18'),
(34, 9, 'update', '2025-02-28 11:52:14'),
(35, 9, 'update', '2025-02-28 11:53:06'),
(36, 36, 'create', '2025-02-28 12:01:54');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE IF NOT EXISTS `booking` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `room_id` int(11) NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime NOT NULL,
  `book_status` enum('pending','confirmed','cancelled','completed') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`book_id`),
  KEY `book_room_fk` (`room_id`),
  KEY `book_account_fk` (`account_id`),
  KEY `book_guest_fk` (`guest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`book_id`, `account_id`, `guest_id`, `room_id`, `check_in`, `check_out`, `book_status`, `created_at`, `updated_at`) VALUES
(3, 5, NULL, 9, '2025-03-14 18:00:00', '2025-03-18 10:00:00', 'pending', '2025-03-03 08:45:00', '2025-03-01 09:29:21'),
(4, NULL, 1, 9, '2025-03-14 18:00:00', '2025-03-18 10:00:00', 'pending', '2025-03-03 08:45:00', '2025-03-01 09:29:34'),
(5, 12, NULL, 15, '2025-02-25 00:00:00', '2025-02-27 00:00:00', 'confirmed', '2025-02-25 12:32:02', '2025-02-28 06:24:42');

-- --------------------------------------------------------

--
-- Table structure for table `booking_notification`
--

CREATE TABLE IF NOT EXISTS `booking_notification` (
  `booking_notif_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `booking_status` varchar(50) NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`booking_notif_id`),
  KEY `booking_notif_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_notification`
--

INSERT INTO `booking_notification` (`booking_notif_id`, `book_id`, `booking_status`, `Date`) VALUES
(1, 5, 'pending', '2025-02-25 12:32:28');

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
,`check_in` datetime
,`check_out` datetime
,`status` enum('pending','confirmed','cancelled','completed')
);

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE IF NOT EXISTS `discount` (
  `discount_id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_name` varchar(50) NOT NULL,
  `discount_percentage` int(11) NOT NULL,
  `discount_start` date NOT NULL,
  `discount_end` date NOT NULL,
  `discount_status` enum('activate','deactivate') NOT NULL,
  `applicable_room` enum('premium','deluxe','standard') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`discount_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`discount_id`, `discount_name`, `discount_percentage`, `discount_start`, `discount_end`, `discount_status`, `applicable_room`, `created_at`, `updated_at`) VALUES
(1, 'promo 50%', 50, '2025-03-01', '2025-03-02', 'activate', 'standard', '2025-02-28 15:28:13', '2025-02-28 15:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `feedback_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `rating` int(1) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`feedback_id`),
  KEY `feedback_account_fk` (`account_id`),
  KEY `feedback_book_fk` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE IF NOT EXISTS `guest` (
  `guest_id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `gender` enum('Male','Female','','') NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`guest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`guest_id`, `fname`, `lname`, `gender`, `contact`, `email`, `created_at`, `updated_at`) VALUES
(1, 'carl', 'nepu', 'Male', '09501841852', 'carl@email.com', '2025-03-01 09:27:01', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `guest_booking`
-- (See below for the actual view)
--
CREATE TABLE IF NOT EXISTS `guest_booking` (
`id` int(11)
,`fname` varchar(50)
,`lname` varchar(50)
,`room_code` varchar(50)
,`check_in` datetime
,`check_out` datetime
,`status` enum('pending','confirmed','cancelled','completed')
);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('cash','credit card','e-payment') NOT NULL,
  `payment_img` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `payment_status` enum('pending','paid','refunded') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`),
  KEY `payment_book_fk` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_code` varchar(50) NOT NULL,
  `room_type` enum('standard','premium','deluxe') NOT NULL,
  `room_status` enum('available','booked','under maintenance') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_ code` (`room_code`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_code`, `room_type`, `room_status`, `price`, `created_at`, `updated_at`) VALUES
(9, 'room101', 'standard', 'available', 500.00, '2025-02-15 15:37:50', '2025-03-01 13:27:58'),
(12, 'room102', 'standard', 'booked', 1000.00, '2025-02-19 12:40:06', '2025-03-01 13:33:44'),
(14, 'room103', 'premium', 'under maintenance', 5000.00, '2025-02-22 03:31:37', '2025-02-27 15:03:28'),
(15, 'room104', 'premium', 'available', 6000.00, '2025-02-25 01:27:38', '2025-02-27 15:30:17');

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
(9, '167b2d9635b18f.png', '2025-02-17 06:38:27'),
(9, '167b2d9635c32e.png', '2025-02-17 06:38:27'),
(12, '167b5d126d3e1d.png', '2025-02-19 12:40:06'),
(12, '167b5d126d7179.png', '2025-02-19 12:40:06'),
(12, '167b5d126d795e.png', '2025-02-19 12:40:06'),
(14, '167b945193908d.png', '2025-02-22 03:31:37'),
(14, '167b945193958b.png', '2025-02-22 03:31:37'),
(15, '167bd1c8aaebb9.png', '2025-02-25 01:27:38'),
(15, '167bd1c8aaf9c6.png', '2025-02-25 01:27:38'),
(15, '167bd1c8ab0c5a.png', '2025-02-25 01:27:38');

-- --------------------------------------------------------

--
-- Table structure for table `room_notification`
--

CREATE TABLE IF NOT EXISTS `room_notification` (
  `rnotif_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `room_notification` enum('create','update') NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`rnotif_id`),
  KEY `room_notification_fk` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_notification`
--

INSERT INTO `room_notification` (`rnotif_id`, `room_id`, `room_notification`, `Date`) VALUES
(1, 15, 'create', '2025-02-25 09:29:05'),
(2, 15, 'update', '2025-02-25 09:49:20'),
(3, 15, 'update', '2025-02-25 09:49:48'),
(4, 14, 'update', '2025-02-25 11:35:38'),
(5, 14, 'update', '2025-02-25 11:35:59'),
(6, 12, 'update', '2025-02-25 12:39:53'),
(7, 15, 'update', '2025-02-25 12:40:00'),
(8, 9, 'update', '2025-02-26 08:15:26'),
(9, 14, 'update', '2025-02-27 23:03:28'),
(15, 9, 'update', '2025-03-01 21:27:58'),
(16, 12, 'update', '2025-03-01 21:33:44');

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
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `name` (`fname`),
  UNIQUE KEY `contact` (`contact`),
  KEY `user_account_fk` (`account_id`),
  KEY `unique_fullname` (`fname`,`lname`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `account_id`, `fname`, `lname`, `age`, `gender`, `contact`, `profile_img`) VALUES
(5, 5, 'Levi', 'Penaverde', 20, 'male', '987867546', '67af5cd6192634.90705227.jpg'),
(9, 9, 'Angelito', 'Jacalan', 21, 'female', '09876544461', '67c132eeec0432.70170810.png'),
(10, 10, 'Allan', 'Monforte', 98, 'male', '9112245667', '67b1c15630d272.47796055.png'),
(12, 12, 'Melvin', 'Catuera', 20, 'female', '9123456789', '67bc9b041413e9.81245551.png'),
(13, 13, 'Ianzae', 'Ego', 21, 'female', '9876543211', '67bc8f552ae286.40054461.png'),
(14, 27, 'Fname', 'Lname', 98, 'male', '9231231236', '67bf3e820512d9.05980251.png'),
(23, 36, 'Adora', 'Shor', 20, 'male', '9501841852', '67c13532cda582.10582847.png');

-- --------------------------------------------------------

--
-- Structure for view `customer_booking`
--
DROP TABLE IF EXISTS `customer_booking`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `customer_booking`  AS SELECT `b`.`book_id` AS `id`, `u`.`fname` AS `fname`, `u`.`lname` AS `lname`, `r`.`room_code` AS `room_code`, `b`.`check_in` AS `check_in`, `b`.`check_out` AS `check_out`, `b`.`book_status` AS `status` FROM (((`booking` `b` join `account` on(`b`.`account_id` = `account`.`account_id`)) join `user` `u` on(`b`.`account_id` = `u`.`account_id`)) join `room` `r` on(`b`.`room_id` = `r`.`room_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `guest_booking`
--
DROP TABLE IF EXISTS `guest_booking`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `guest_booking`  AS SELECT `b`.`book_id` AS `id`, `g`.`fname` AS `fname`, `g`.`lname` AS `lname`, `r`.`room_code` AS `room_code`, `b`.`check_in` AS `check_in`, `b`.`check_out` AS `check_out`, `b`.`book_status` AS `status` FROM (((`booking` `b` join `guest` on(`b`.`guest_id` = `guest`.`guest_id`)) join `guest` `g` on(`b`.`guest_id` = `g`.`guest_id`)) join `room` `r` on(`b`.`room_id` = `r`.`room_id`)) ;

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
  ADD CONSTRAINT `book_guest_fk` FOREIGN KEY (`guest_id`) REFERENCES `guest` (`guest_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `book_room_fk` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking_notification`
--
ALTER TABLE `booking_notification`
  ADD CONSTRAINT `booking_notif_id` FOREIGN KEY (`book_id`) REFERENCES `booking` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_account_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `feedback_book_fk` FOREIGN KEY (`book_id`) REFERENCES `booking` (`book_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
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
