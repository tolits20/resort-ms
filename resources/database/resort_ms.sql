-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 03:12 PM
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
  `last_active` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password`, `role`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 'levipenaverde@example.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'admin', '2025-03-06 22:08:12', '2025-02-14 15:10:14', '2025-02-14 15:10:14', NULL),
(9, 'tolits@example.com', '21a2f903885172b4503e6f5eaf6b78880f4712cc', 'admin', NULL, '2025-02-15 05:24:00', '2025-03-04 14:20:11', NULL),
(10, 'allan@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'admin', NULL, '2025-02-16 10:43:34', '2025-02-27 13:42:18', NULL),
(12, 'catuera@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'user', NULL, '2025-02-23 15:06:58', '2025-03-02 03:45:02', NULL),
(13, 'ego@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'admin', NULL, '2025-02-24 15:25:09', '2025-02-25 01:32:39', '2025-03-05 22:59:09'),
(27, 'lems.leviasherpenaverde@gmail.com', '9d2dd1c52280572bf5d0aedd5aeda096fc1f8f54', 'user', '2025-03-06 22:09:54', '2025-02-26 16:17:06', '2025-03-03 13:58:06', NULL),
(38, 'asherxd102345@gmail.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'user', NULL, '2025-03-04 13:33:51', '2025-03-06 13:43:42', NULL),
(40, 'user@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', 'user', '2025-03-06 09:39:23', '2025-03-06 01:39:18', '2025-03-06 10:44:32', '2025-03-06 18:49:28');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_notification`
--

INSERT INTO `account_notification` (`cnotif_id`, `account_id`, `account_notification`, `Date`) VALUES
(1, 12, 'update', '2025-03-02 11:45:02'),
(2, 27, 'update', '2025-03-03 21:58:06'),
(4, 38, 'create', '2025-03-04 21:33:51'),
(5, 9, 'update', '2025-03-04 22:18:24'),
(6, 9, 'update', '2025-03-04 22:18:32'),
(7, 9, 'update', '2025-03-04 22:20:11'),
(9, 40, 'create', '2025-03-06 09:39:18'),
(11, 40, 'update', '2025-03-06 18:44:32'),
(13, 38, 'update', '2025-03-06 21:43:42');

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
  `reminder_sent` datetime DEFAULT NULL,
  `completion_sent` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`book_id`),
  KEY `book_room_fk` (`room_id`),
  KEY `book_account_fk` (`account_id`),
  KEY `book_guest_fk` (`guest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`book_id`, `account_id`, `guest_id`, `room_id`, `check_in`, `check_out`, `book_status`, `reminder_sent`, `completion_sent`, `created_at`, `updated_at`) VALUES
(4, NULL, 1, 9, '2025-03-14 18:00:00', '2025-03-18 10:00:00', 'completed', NULL, '2025-03-06 18:41:16', '2025-03-03 08:45:00', '2025-03-05 14:45:18'),
(12, 27, NULL, 15, '2025-03-05 07:00:00', '2025-03-05 17:00:00', 'completed', NULL, '2025-03-06 11:38:39', '2025-03-04 23:05:00', '2025-03-05 14:30:55'),
(14, NULL, 1, 12, '2025-03-07 07:00:00', '2025-03-07 19:00:00', 'confirmed', '2025-03-06 18:40:13', NULL, '2025-03-05 22:49:45', '2025-03-05 14:49:45'),
(16, 40, NULL, 9, '2025-03-31 07:00:00', '2025-04-01 05:00:00', 'confirmed', NULL, NULL, '2025-03-06 09:45:27', '2025-03-06 10:24:09'),
(23, 27, NULL, 15, '2025-03-05 07:00:00', '2025-03-06 17:00:00', 'cancelled', NULL, NULL, '2025-03-06 19:04:45', '2025-03-06 11:13:51'),
(24, 12, NULL, 14, '2025-03-05 07:00:00', '2025-03-06 17:00:00', 'cancelled', NULL, NULL, '2025-03-06 19:37:46', '2025-03-06 11:37:46');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_notification`
--

INSERT INTO `booking_notification` (`booking_notif_id`, `book_id`, `booking_status`, `Date`) VALUES
(5, 16, 'updated', '2025-03-06 10:50:35'),
(7, 16, 'cancelled', '2025-03-06 11:02:18'),
(8, 23, 'pending', '2025-03-06 19:04:45');

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
  `discount_start` datetime NOT NULL,
  `discount_end` datetime NOT NULL,
  `discount_status` enum('activate','deactivate') NOT NULL,
  `applicable_room` enum('premium','deluxe','standard') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`discount_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`discount_id`, `discount_name`, `discount_percentage`, `discount_start`, `discount_end`, `discount_status`, `applicable_room`, `created_at`, `updated_at`) VALUES
(2, 'summer sale ', 20, '2025-03-02 11:14:00', '2025-03-05 12:00:00', 'activate', 'standard', '2025-03-02 11:15:12', '2025-03-02 11:15:12');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `feedback_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `rating` int(1) NOT NULL,
  `overall_experience` text NOT NULL,
  `room_cleanliness` text NOT NULL,
  `staff_service` text NOT NULL,
  `facilities` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`feedback_id`),
  KEY `feedback_book_fk` (`book_id`),
  KEY `feedback_account_fk` (`account_id`)
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
-- Table structure for table `password_recovery`
--

CREATE TABLE IF NOT EXISTS `password_recovery` (
  `otp_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `otp_code` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `expire_at` datetime NOT NULL,
  PRIMARY KEY (`otp_id`),
  UNIQUE KEY `otp_code` (`otp_code`),
  KEY `account_recovery_fk` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('cash','credit card','e-payment') DEFAULT NULL,
  `payment_img` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `payment_status` enum('pending','paid','refunded') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`),
  KEY `payment_book_fk` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `book_id`, `amount`, `payment_type`, `payment_img`, `transaction_id`, `payment_status`, `created_at`, `updated_at`) VALUES
(3, 4, 500.00, 'credit card', '', '', 'pending', '2025-03-02 00:19:30', '2025-03-02 00:19:30');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_code` varchar(50) NOT NULL,
  `room_type` enum('standard','premium','deluxe') NOT NULL,
  `room_status` enum('available','booked','under maintenance') NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_ code` (`room_code`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_code`, `room_type`, `room_status`, `description`, `price`, `created_at`, `updated_at`) VALUES
(9, 'room101', 'standard', 'available', '', 500.00, '2025-02-15 15:37:50', '2025-03-06 03:02:18'),
(12, 'room102', 'standard', 'booked', '', 1000.00, '2025-02-19 12:40:06', '2025-03-01 13:33:44'),
(14, 'room103', 'premium', 'available', '', 5000.00, '2025-02-22 03:31:37', '2025-03-06 02:13:51'),
(15, 'room104', 'premium', 'available', '', 6000.00, '2025-02-25 01:27:38', '2025-02-27 15:30:17');

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
(12, '167b5d126d3e1d.png', '2025-02-19 12:40:06'),
(12, '167b5d126d7179.png', '2025-02-19 12:40:06'),
(12, '167b5d126d795e.png', '2025-02-19 12:40:06'),
(14, '167b945193908d.png', '2025-02-22 03:31:37'),
(14, '167b945193958b.png', '2025-02-22 03:31:37'),
(15, '167bd1c8aaebb9.png', '2025-02-25 01:27:38'),
(15, '167bd1c8aaf9c6.png', '2025-02-25 01:27:38'),
(9, '167c6ff0e9b25d.png', '2025-03-04 13:24:30'),
(9, '167c6ff0e9beea.png', '2025-03-04 13:24:30');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_notification`
--

INSERT INTO `room_notification` (`rnotif_id`, `room_id`, `room_notification`, `Date`) VALUES
(1, 9, 'update', '2025-03-03 11:27:27'),
(2, 9, 'update', '2025-03-03 11:27:40'),
(3, 9, 'update', '2025-03-04 21:24:30');

-- --------------------------------------------------------

--
-- Stand-in structure for view `summary_payment`
-- (See below for the actual view)
--
CREATE TABLE IF NOT EXISTS `summary_payment` (
`booking_id` int(11)
,`booking_type` varchar(5)
,`NAME` varchar(101)
,`room_code` varchar(50)
,`room_type` varchar(8)
,`price` decimal(10,2)
,`check_in` datetime
,`check_out` datetime
,`book_status` varchar(9)
,`amount_paid` decimal(10,2)
,`payment_status` varchar(8)
,`payment_type` varchar(11)
,`payment_created_at` datetime
);

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `account_id`, `fname`, `lname`, `age`, `gender`, `contact`, `profile_img`) VALUES
(5, 5, 'Levi', 'Penaverde', 20, 'male', '987867546', '67af5cd6192634.90705227.jpg'),
(9, 9, 'Angelito', 'Jacalan', 22, 'female', '09876544461', '67c70c1b41d3d7.70685210.png'),
(10, 10, 'Allan', 'Monforte', 98, 'male', '9112245667', '67b1c15630d272.47796055.png'),
(12, 12, 'Melvin', 'Catuera', 20, 'female', '9123456789', '67c3d43e7168e3.93428308.png'),
(13, 13, 'Ianzae', 'Ego', 21, 'female', '9876543211', '67bc8f552ae286.40054461.png'),
(14, 27, 'Asher', 'Hilado', 21, 'male', '9231231236', '67bf3e820512d9.05980251.png'),
(25, 38, 'Scarlet', 'Penaverde', 18, 'female', '912345678', '67c7013f97ae19.64225426.png'),
(27, 40, 'user', 'User', 98, 'male', '914154147', '67c8fcc661d110.05298683.jpg');

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

-- --------------------------------------------------------

--
-- Structure for view `summary_payment`
--
DROP TABLE IF EXISTS `summary_payment`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summary_payment`  AS SELECT `cb`.`id` AS `booking_id`, 'User' AS `booking_type`, concat(`cb`.`fname`,' ',`cb`.`lname`) AS `NAME`, `cb`.`room_code` AS `room_code`, `r`.`room_type` AS `room_type`, `r`.`price` AS `price`, `cb`.`check_in` AS `check_in`, `cb`.`check_out` AS `check_out`, `cb`.`status` AS `book_status`, coalesce(`p`.`amount`,0) AS `amount_paid`, coalesce(`p`.`payment_status`,'Pending') AS `payment_status`, coalesce(`p`.`payment_type`,'N/A') AS `payment_type`, `p`.`created_at` AS `payment_created_at` FROM ((`customer_booking` `cb` left join `payment` `p` on(`cb`.`id` = `p`.`book_id`)) join `room` `r` on(`cb`.`room_code` = `r`.`room_code`))union all select `gb`.`id` AS `booking_id`,'Guest' AS `booking_type`,concat(`gb`.`fname`,' ',`gb`.`lname`) AS `NAME`,`gb`.`room_code` AS `room_code`,`r`.`room_type` AS `room_type`,`r`.`price` AS `price`,`gb`.`check_in` AS `check_in`,`gb`.`check_out` AS `check_out`,`gb`.`status` AS `book_status`,coalesce(`p`.`amount`,0) AS `amount_paid`,coalesce(`p`.`payment_status`,'Pending') AS `payment_status`,coalesce(`p`.`payment_type`,'N/A') AS `payment_type`,`p`.`created_at` AS `payment_created_at` from ((`guest_booking` `gb` left join `payment` `p` on(`gb`.`id` = `p`.`book_id`)) join `room` `r` on(`gb`.`room_code` = `r`.`room_code`))  ;

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
  ADD CONSTRAINT `feedback_account_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `feedback_book_fk` FOREIGN KEY (`book_id`) REFERENCES `booking` (`book_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `password_recovery`
--
ALTER TABLE `password_recovery`
  ADD CONSTRAINT `account_recovery_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
