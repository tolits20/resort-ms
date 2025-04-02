-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2025 at 04:32 PM
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
  `role` enum('user','admin','staff') NOT NULL,
  `last_active` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password`, `role`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 'levipenaverde@example.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'admin', '2025-04-02 21:47:03', '2025-02-14 15:10:14', '2025-02-14 15:10:14', NULL),
(9, 'tolits@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'admin', '2025-03-09 13:13:34', '2025-02-15 05:24:00', '2025-03-04 14:20:11', NULL),
(10, 'allan@example.com', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', 'user', '2025-04-01 10:23:34', '2025-02-16 10:43:34', '2025-03-26 00:27:01', NULL),
(12, 'catuera@example.com', '9d2dd1c52280572bf5d0aedd5aeda096fc1f8f54', 'staff', '2025-03-26 15:57:24', '2025-02-23 15:06:58', '2025-03-10 08:09:10', NULL),
(27, 'lems.leviasherpenaverde@gmail.com', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', 'user', '2025-04-02 22:24:42', '2025-02-26 16:17:06', '2025-03-03 13:58:06', NULL),
(38, 'asherxd102345@gmail.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'user', NULL, '2025-03-04 13:33:51', '2025-03-06 13:43:42', '2025-03-11 23:13:41'),
(40, 'user@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'user', '2025-03-26 08:25:40', '2025-03-06 01:39:18', '2025-03-25 20:53:26', NULL),
(45, 'jyren@gmail.com', '803e4a8e0a6f26ec9fa75321c0d551a83b9a075d', 'staff', '2025-03-16 16:22:45', '2025-03-09 03:04:02', '2025-03-16 08:22:18', NULL),
(47, 'alexa@gmail.com', '803e4a8e0a6f26ec9fa75321c0d551a83b9a075d', 'user', '2025-03-26 08:12:28', '2025-03-12 14:26:19', '2025-03-26 00:10:16', NULL),
(54, 'billonedlloyd@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'user', NULL, '2025-03-15 04:25:50', NULL, '2025-03-26 15:49:39'),
(55, 'admin@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', 'admin', '2025-03-26 03:43:25', '2025-03-25 19:42:41', NULL, NULL),
(57, 'davimher@gmail.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', 'user', '2025-03-26 15:30:00', '2025-03-26 07:29:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_notification`
--

CREATE TABLE IF NOT EXISTS `account_notification` (
  `cnotif_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `is_read` tinytext NOT NULL DEFAULT '0',
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cnotif_id`),
  KEY `account_notification_fk` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_notification`
--

INSERT INTO `account_notification` (`cnotif_id`, `account_id`, `message`, `is_read`, `Date`) VALUES
(1, 27, 'this is a test notification', '1', '2025-03-14 14:18:03'),
(2, 54, 'hello good morning kumain ka na', '1', '2025-03-15 12:25:50'),
(3, 45, 'Jyren Santestiban Account is Updated Succesfully', '0', '2025-03-16 16:22:18'),
(4, 55, 'New User Admin Admin Registered!', '0', '2025-03-26 03:42:41'),
(6, 47, 'Alexa Santestiban updated successfully!', '0', '2025-03-26 08:10:16'),
(7, 10, 'Allan Monforte Account is Updated Succesfully', '0', '2025-03-26 08:27:01'),
(8, 57, 'New User Davimher Landas Registered!', '0', '2025-03-26 15:29:43');

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
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`book_id`, `account_id`, `guest_id`, `room_id`, `check_in`, `check_out`, `book_status`, `reminder_sent`, `completion_sent`, `created_at`, `updated_at`) VALUES
(43, 40, NULL, 31, '2025-03-24 07:00:00', '2025-03-25 05:00:00', 'completed', NULL, '2025-03-26 08:19:34', '2025-03-24 04:19:27', '2025-03-25 20:23:40'),
(44, 40, NULL, 27, '2025-03-19 07:00:00', '2025-03-20 05:00:00', 'completed', '2025-03-26 08:15:42', '2025-03-26 08:21:17', '2025-03-26 04:40:11', '2025-03-18 20:40:11'),
(45, 47, NULL, 28, '2025-03-25 07:00:00', '2025-03-26 17:00:00', 'completed', NULL, '2025-04-01 09:59:40', '2025-03-25 08:13:04', '2025-03-26 00:13:04'),
(46, 40, NULL, 30, '2025-03-26 19:00:00', '2025-03-27 05:00:00', 'cancelled', NULL, NULL, '2025-03-26 08:25:59', '2025-03-26 00:25:59'),
(47, 10, NULL, 29, '2025-03-26 19:00:00', '2025-03-27 17:00:00', 'completed', '2025-03-26 08:30:33', '2025-04-01 10:00:06', '2025-03-26 08:28:13', '2025-03-26 00:28:13'),
(48, 10, NULL, 29, '2025-03-28 19:00:00', '2025-03-29 05:00:00', 'cancelled', NULL, NULL, '2025-03-26 08:45:18', '2025-03-26 00:45:18'),
(49, 27, NULL, 27, '2025-03-26 19:00:00', '2025-03-27 05:00:00', 'completed', '2025-03-26 08:55:34', '2025-04-01 09:59:49', '2025-03-26 08:54:46', '2025-03-26 07:43:39'),
(50, 57, NULL, 31, '2025-03-27 07:00:00', '2025-03-27 17:00:00', 'cancelled', NULL, NULL, '2025-03-26 15:30:53', '2025-03-26 07:38:05'),
(51, 27, NULL, 27, '2025-03-27 07:00:00', '2025-03-27 17:00:00', 'completed', '2025-03-26 15:34:23', '2025-04-01 09:59:57', '2025-03-26 15:32:54', '2025-03-26 07:40:43'),
(52, 5, NULL, 27, '2025-03-28 07:00:00', '2025-03-28 17:00:00', 'cancelled', NULL, NULL, '2025-03-26 15:45:27', '2025-03-26 07:45:27'),
(53, 27, NULL, 27, '2025-04-03 07:00:00', '2025-04-03 17:00:00', 'cancelled', NULL, NULL, '2025-04-01 10:22:22', '2025-04-01 02:22:22'),
(54, 10, NULL, 27, '2025-04-03 07:00:00', '2025-04-03 17:00:00', 'confirmed', '2025-04-02 21:47:03', NULL, '2025-04-01 10:24:03', '2025-04-01 02:25:05');

--
-- Triggers `booking`
--
DELIMITER $$
CREATE TRIGGER `prevent_double_booking` BEFORE INSERT ON `booking` FOR EACH ROW BEGIN
        DECLARE conflict_count INT;
        SELECT COUNT(*) INTO conflict_count
        FROM booking
        WHERE room_id = NEW.room_id
        AND book_status IN ('confirmed', 'booked')
        AND NEW.check_in < check_out
        AND NEW.check_out > check_in;

        IF conflict_count > 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Double booking detected. Please choose a different room or time.';
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `booking_notification`
--

CREATE TABLE IF NOT EXISTS `booking_notification` (
  `booking_notif_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`booking_notif_id`),
  KEY `booking_notif_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_notification`
--

INSERT INTO `booking_notification` (`booking_notif_id`, `book_id`, `message`, `is_read`, `Date`) VALUES
(19, 43, 'New Booking', 0, '2025-03-26 04:19:27'),
(20, 43, 'Updated Booking', 0, '2025-03-26 04:21:11'),
(21, 44, 'New Booking', 0, '2025-03-26 04:40:11'),
(22, 45, 'New Booking', 0, '2025-03-26 08:13:04'),
(23, 46, 'New Booking', 0, '2025-03-26 08:25:59'),
(24, 47, 'New Booking', 0, '2025-03-26 08:28:13'),
(25, 48, 'New Booking', 0, '2025-03-26 08:45:18'),
(26, 49, 'New Booking', 0, '2025-03-26 08:54:46'),
(27, 50, 'New Booking', 0, '2025-03-26 15:30:53'),
(28, 51, 'New Booking', 0, '2025-03-26 15:32:54'),
(29, 50, 'Updated Booking', 0, '2025-03-26 15:37:37'),
(30, 50, 'Booking cancelled', 0, '2025-03-26 15:38:05'),
(31, 52, 'New Booking', 0, '2025-03-26 15:45:27'),
(32, 53, 'New Booking', 0, '2025-04-01 10:22:22'),
(33, 54, 'New Booking', 0, '2025-04-01 10:24:03');

-- --------------------------------------------------------

--
-- Stand-in structure for view `customer_booking`
-- (See below for the actual view)
--
CREATE TABLE IF NOT EXISTS `customer_booking` (
`id` int(11)
,`fname` varchar(50)
,`lname` varchar(50)
,`email` varchar(50)
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
  `applicable_room` enum('standard','single_room','deluxe','family_room','studio_room','suite') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`discount_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`discount_id`, `discount_name`, `discount_percentage`, `discount_start`, `discount_end`, `discount_status`, `applicable_room`, `created_at`, `updated_at`) VALUES
(2, 'summer sale ', 20, '2025-03-02 11:14:00', '2025-03-28 12:00:00', 'activate', 'family_room', '2025-03-02 11:15:12', '2025-03-02 11:15:12'),
(3, 'just discount', 32, '2025-03-07 21:55:00', '2025-03-09 21:55:00', 'deactivate', 'deluxe', '2025-03-07 21:55:46', '2025-03-07 21:55:46'),
(7, 'sample', 50, '2025-03-23 10:33:00', '2025-03-27 10:33:00', 'activate', 'suite', '2025-03-23 10:34:06', '2025-03-23 10:34:06'),
(8, 'sample1', 30, '2025-03-26 15:51:00', '2025-03-29 15:51:00', 'activate', 'standard', '2025-03-26 15:52:07', '2025-03-26 15:52:07');

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
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`feedback_id`),
  KEY `feedback_book_fk` (`book_id`),
  KEY `feedback_account_fk` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `account_id`, `book_id`, `rating`, `overall_experience`, `room_cleanliness`, `staff_service`, `facilities`, `comment`, `created_at`, `updated_at`) VALUES
(5, NULL, 43, 5, 'Great', 'Great', 'Great', 'Great', 'Great', '2025-03-25 20:24:04', '2025-03-25 20:24:04');

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE IF NOT EXISTS `guest` (
  `guest_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `gender` enum('Male','Female','','') NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`guest_id`),
  KEY `guest_assist_fk` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`guest_id`, `account_id`, `fname`, `lname`, `gender`, `contact`, `email`, `created_at`, `updated_at`) VALUES
(1, 12, 'carl', 'nepu', 'Male', '09501841852', 'carl@email.com', '2025-03-01 09:27:01', NULL),
(2, 12, 'elena', 'hilado', 'Female', '0987867587', 'asherxd10245@gmail.com', '2025-03-13 15:55:40', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `guest_booking`
-- (See below for the actual view)
--
CREATE TABLE IF NOT EXISTS `guest_booking` (
`id` int(11)
,`fname` varchar(50)
,`lname` varchar(50)
,`email` varchar(50)
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `pay_amount` decimal(10,2) NOT NULL,
  `payment_type` enum('cash','credit_card','e-wallet') DEFAULT NULL,
  `payment_img` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','paid','refunded') NOT NULL,
  `email_receipt` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`),
  KEY `payment_book_fk` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `book_id`, `amount`, `pay_amount`, `payment_type`, `payment_img`, `transaction_id`, `payment_status`, `email_receipt`, `created_at`, `updated_at`) VALUES
(13, 43, 2000.00, 2000.00, 'e-wallet', '67e310111aec7.png', '14141', 'paid', '2025-03-26 04:23:29', '2025-03-26 04:19:45', '2025-03-26 04:20:33'),
(14, 44, 4000.00, 4000.00, 'credit_card', 'pay_67e314bdd01946.62057107.png', '234234', 'paid', '2025-03-26 08:15:51', '2025-03-26 04:40:29', '2025-03-26 04:40:29'),
(15, 45, 3000.00, 3000.00, 'e-wallet', 'pay_67e3469fba6bd4.52068405.png', '765432', 'pending', NULL, '2025-03-26 08:13:19', '2025-03-26 08:13:19'),
(16, 46, 1000.00, 1000.00, 'e-wallet', 'pay_67e349a4bc7f32.27217285.png', '76545', 'pending', NULL, '2025-03-26 08:26:12', '2025-03-26 08:26:12'),
(17, 47, 3000.00, 3000.00, 'e-wallet', 'pay_67e34a290a68f8.32682819.png', '98765', 'paid', '2025-03-26 08:29:30', '2025-03-26 08:28:25', '2025-03-26 08:28:25'),
(18, 48, 1500.00, 1500.00, 'e-wallet', 'pay_67e34e2ca020c4.41782331.png', '87654', 'pending', NULL, '2025-03-26 08:45:32', '2025-03-26 08:45:32'),
(19, 49, 1000.00, 1000.00, 'e-wallet', 'pay_67e35066e2ef46.98556440.png', '8765', 'paid', '2025-03-26 08:55:44', '2025-03-26 08:55:02', '2025-03-26 08:55:02'),
(20, 50, 1000.00, 1000.00, 'e-wallet', 'pay_67e3ad5d21ea56.48894103.jpg', '2322323', 'pending', NULL, '2025-03-26 15:31:41', '2025-03-26 15:31:56'),
(21, 51, 1000.00, 1000.00, 'credit_card', 'pay_67e3adb25c5f51.92574815.jpg', '22222', 'paid', '2025-03-26 15:34:31', '2025-03-26 15:33:06', '2025-03-26 15:33:06'),
(22, 53, 2000.00, 2000.00, 'e-wallet', 'pay_67eb4dec0af439.79759713.jpg', '8765', 'pending', NULL, '2025-04-01 10:22:36', '2025-04-01 10:22:36'),
(23, 54, 2000.00, 1000.00, 'e-wallet', 'pay_67eb4e5023c9e4.44106226.jpg', '7653', 'pending', NULL, '2025-04-01 10:24:16', '2025-04-01 10:24:16');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_code` varchar(50) NOT NULL,
  `room_type` enum('standard','single_room','deluxe','family_room','studio_room','suite','banquet_hall','garden_venue') NOT NULL,
  `room_status` enum('available','booked','under maintenance') NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_ code` (`room_code`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_code`, `room_type`, `room_status`, `description`, `price`, `created_at`, `updated_at`) VALUES
(27, 'Ocean View Villa', 'suite', 'available', '', 2000.00, '2025-03-25 20:07:26', '2025-03-25 20:08:48'),
(28, 'Family Suite', 'family_room', 'available', '', 3000.00, '2025-03-25 20:10:43', '2025-03-25 20:11:02'),
(29, 'Deluxe Room', 'deluxe', 'available', '', 1500.00, '2025-03-25 20:13:42', '2025-03-25 20:13:42'),
(30, 'Standard Room', 'standard', 'available', '', 1000.00, '2025-03-25 20:15:12', '2025-03-25 20:15:12'),
(31, 'Solo Retreat', 'single_room', 'available', '', 1000.00, '2025-03-25 20:17:58', '2025-03-26 07:38:05'),
(32, 'room110', 'standard', 'available', '', 1500.00, '2025-03-26 07:51:06', '2025-03-26 07:51:06'),
(33, 'Crystal Banquet Hall', 'banquet_hall', 'available', '', 10000.00, '2025-04-02 14:19:32', '2025-04-02 14:19:32'),
(34, 'Sunset Garden Terrace', 'garden_venue', 'available', '', 5000.00, '2025-04-02 14:20:29', '2025-04-02 14:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `room_gallery`
--

CREATE TABLE IF NOT EXISTS `room_gallery` (
  `room_id` int(11) NOT NULL,
  `room_img` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `room_img` (`room_img`),
  KEY `galley_room_fk` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_gallery`
--

INSERT INTO `room_gallery` (`room_id`, `room_img`, `created_at`) VALUES
(27, '167e30cfe07778.jpg', '2025-03-25 20:07:26'),
(28, '167e30dc36f19a.jpg', '2025-03-25 20:10:43'),
(28, '167e30dd635f04.jpg', '2025-03-25 20:11:02'),
(29, '167e30e76d8ba5.jpg', '2025-03-25 20:13:42'),
(30, '167e30ed08c823.jpg', '2025-03-25 20:15:12'),
(31, '167e30f76e49d9.jpg', '2025-03-25 20:17:58'),
(32, '167e3b1ead4687.jpg', '2025-03-26 07:51:06'),
(32, '167e3b1ead5b2e.jpg', '2025-03-26 07:51:06'),
(32, '167e3b1ead6174.jpg', '2025-03-26 07:51:06'),
(33, '167ed4774272ee.png', '2025-04-02 14:19:32'),
(33, '167ed4774288ab.png', '2025-04-02 14:19:32'),
(33, '167ed4774290b6.png', '2025-04-02 14:19:32'),
(34, '167ed47ad8edb6.png', '2025-04-02 14:20:29'),
(34, '167ed47ad92255.png', '2025-04-02 14:20:29'),
(34, '167ed47ad92823.png', '2025-04-02 14:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `room_notification`
--

CREATE TABLE IF NOT EXISTS `room_notification` (
  `rnotif_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `is_read` int(1) NOT NULL DEFAULT 0,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`rnotif_id`),
  KEY `room_notification_fk` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_notification`
--

INSERT INTO `room_notification` (`rnotif_id`, `room_id`, `message`, `is_read`, `Date`) VALUES
(16, 27, 'Ocean View Villa added to the system successfully', 0, '2025-03-26 04:07:26'),
(17, 27, 'Details for Room #[Ocean View Villa] have been modified', 0, '2025-03-26 04:07:47'),
(18, 27, 'Details for Room #[Ocean View Villa] have been modified', 0, '2025-03-26 04:08:16'),
(19, 27, 'Details for Room #[Ocean View Villa] have been modified', 0, '2025-03-26 04:08:48'),
(20, 28, 'Family Suite added to the system successfully', 0, '2025-03-26 04:10:43'),
(21, 28, 'Details for Room #[Family Suite] have been modified', 0, '2025-03-26 04:11:02'),
(22, 29, 'Deluxe Room added to the system successfully', 0, '2025-03-26 04:13:42'),
(23, 30, 'Standard Room added to the system successfully', 0, '2025-03-26 04:15:12'),
(24, 31, 'Solo Retreat added to the system successfully', 0, '2025-03-26 04:17:58'),
(25, 27, 'Room #[Ocean View Villa] is now availble', 0, '2025-03-26 08:21:07'),
(26, 32, 'room110 added to the system successfully', 0, '2025-03-26 15:51:06'),
(27, 29, 'Room #[Deluxe Room] is now availble', 0, '2025-04-01 09:59:57'),
(28, 33, 'Crystal Banquet Hall added to the system successfully', 0, '2025-04-02 22:19:32'),
(29, 34, 'Sunset Garden Terrace added to the system successfully', 0, '2025-04-02 22:20:29');

-- --------------------------------------------------------

--
-- Stand-in structure for view `summary_payment`
-- (See below for the actual view)
--
CREATE TABLE IF NOT EXISTS `summary_payment` (
`booking_id` int(11)
,`booking_type` varchar(5)
,`NAME` varchar(101)
,`email` varchar(50)
,`room_code` varchar(50)
,`room_type` varchar(12)
,`price` decimal(10,2)
,`check_in` datetime
,`check_out` datetime
,`book_status` varchar(9)
,`amount` decimal(10,2)
,`amount_paid` decimal(10,2)
,`payment_status` varchar(8)
,`payment_type` varchar(11)
,`attached_receipt` datetime
,`payment_created_at` datetime
,`receipt` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` enum('Low','Medium','High') NOT NULL,
  `status` enum('Pending','Overdue','Completed') NOT NULL DEFAULT 'Pending',
  `due_date` date NOT NULL,
  `recurrence` enum('None','Daily','Weekly','Monthly') DEFAULT 'None',
  `template_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tasks_ibfk_1` (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=326 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `priority`, `status`, `due_date`, `recurrence`, `template_id`, `created_at`, `updated_at`) VALUES
(319, 'Clean Pool', 'you guys need to clean the pool every time the resort is under maintenance got it??', 'High', 'Overdue', '2025-03-14', 'Daily', 2, '2025-03-13 16:07:14', '2025-03-15 02:26:00'),
(320, 'Clean Pool', 'you guys need to clean the pool every time the resort is under maintenance got it??', 'High', 'Completed', '2025-03-15', 'Daily', 2, '2025-03-15 02:09:07', '2025-03-15 03:02:58'),
(321, 'clean AC', 'clean the AC do maintenance after customer bookings', 'Medium', 'Completed', '2025-03-16', 'Weekly', 11, '2025-03-15 03:10:48', '2025-03-15 03:48:23'),
(322, 'Clean Pool', 'you guys need to clean the pool every time the resort is under maintenance got it??', 'High', 'Completed', '2025-03-16', 'Daily', 2, '2025-03-16 06:52:13', '2025-03-16 07:02:31'),
(323, 'clean AC', 'clean the AC do maintenance after customer bookings', 'Medium', 'Completed', '2025-03-24', 'Daily', 11, '2025-03-22 19:18:44', '2025-03-22 19:35:55'),
(324, 'sample', 'vsdfsdfdsfsd', 'High', 'Completed', '2025-03-27', 'Daily', 12, '2025-03-26 00:39:49', '2025-03-26 08:09:42'),
(325, 'sample', 'vsdfsdfdsfsd', 'High', 'Overdue', '2025-03-27', 'Daily', 12, '2025-03-26 07:53:14', '2025-04-01 01:59:57');

-- --------------------------------------------------------

--
-- Table structure for table `task_assignees`
--

CREATE TABLE IF NOT EXISTS `task_assignees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `assignee_task` enum('Pending','Complete','Overdue') NOT NULL DEFAULT 'Pending',
  `completion_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `task_assigness_fk_1` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_assignees`
--

INSERT INTO `task_assignees` (`id`, `task_id`, `staff_id`, `assignee_task`, `completion_time`) VALUES
(48, 319, 12, 'Overdue', NULL),
(49, 319, 45, 'Overdue', NULL),
(50, 320, 12, 'Complete', '2025-03-15 11:02:58'),
(51, 320, 45, 'Complete', '2025-03-15 11:02:58'),
(52, 321, 12, 'Complete', '2025-03-15 11:48:23'),
(53, 321, 45, 'Complete', '2025-03-15 11:48:23'),
(54, 322, 12, 'Complete', '2025-03-16 15:02:31'),
(55, 322, 45, 'Complete', '2025-03-16 15:02:31'),
(56, 323, 12, 'Complete', '2025-03-23 03:35:55'),
(57, 324, 12, 'Complete', '2025-03-26 16:09:42'),
(58, 325, 45, 'Overdue', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `task_notifications`
--

CREATE TABLE IF NOT EXISTS `task_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `task_notifications_ibfk_2` (`staff_id`),
  KEY `task_notification_fk_1` (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_notifications`
--

INSERT INTO `task_notifications` (`id`, `task_id`, `staff_id`, `message`, `is_read`, `created_at`) VALUES
(61, 319, 12, 'You have a new task: Clean Pool', 1, '2025-03-13 16:07:14'),
(62, 319, 45, 'You have a new task: Clean Pool', 1, '2025-03-13 16:07:14'),
(63, 320, 12, 'You have a new task: Clean Pool', 1, '2025-03-15 02:09:07'),
(64, 320, 45, 'You have a new task: Clean Pool', 1, '2025-03-15 02:09:07'),
(65, 321, 12, 'New task assigned: clean AC', 1, '2025-03-15 03:10:48'),
(66, 321, 45, 'New task assigned: clean AC', 1, '2025-03-15 03:10:48'),
(67, 322, 12, 'You have a new task: Clean Pool', 1, '2025-03-16 06:52:13'),
(68, 322, 45, 'You have a new task: Clean Pool', 1, '2025-03-16 06:52:13'),
(69, 323, 12, 'New task assigned: clean AC', 1, '2025-03-22 19:18:44'),
(70, 324, 12, 'New task assigned: sample', 1, '2025-03-26 00:39:49'),
(71, 325, 45, 'New task assigned: sample', 0, '2025-03-26 07:53:14');

-- --------------------------------------------------------

--
-- Table structure for table `task_templates`
--

CREATE TABLE IF NOT EXISTS `task_templates` (
  `task_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`task_template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_templates`
--

INSERT INTO `task_templates` (`task_template_id`, `title`, `description`, `priority`, `created_at`) VALUES
(2, 'Clean Pool', 'Cleaning a pool involves skimming debris, brushing the surfaces, vacuuming the floor, checking and cleaning the filter, and balancing the water\'s chemical levels.', 'High', '2025-03-09 09:33:57'),
(11, 'clean AC', 'Cleaning an AC involves turning off the power, cleaning or replacing the air filter, wiping the coils and fins, and checking the drainage system for any clogs.', 'Medium', '2025-03-09 09:38:08'),
(12, 'sample', 'vsdfsdfdsfsd', 'High', '2025-03-26 00:39:17');

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
  `profile_img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `name` (`fname`),
  UNIQUE KEY `contact` (`contact`),
  KEY `user_account_fk` (`account_id`),
  KEY `unique_fullname` (`fname`,`lname`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `account_id`, `fname`, `lname`, `age`, `gender`, `contact`, `profile_img`) VALUES
(5, 5, 'Levi', 'Penaverde', 20, 'male', '987867546', '67af5cd6192634.90705227.jpg'),
(9, 9, 'Angelito', 'Jacalan', 22, 'female', '09876544461', '67c70c1b41d3d7.70685210.png'),
(10, 10, 'Allan', 'Monforte', 98, 'male', '9112245667', '67b1c15630d272.47796055.png'),
(12, 12, 'Melvin', 'Catuera', 20, 'female', '9123456789', '67c3d43e7168e3.93428308.png'),
(14, 27, 'Asher', 'Hilado', 21, 'male', '9231231236', '67bf3e820512d9.05980251.png'),
(25, 38, 'Scarlet', 'Penaverde', 18, 'female', '912345678', '67c7013f97ae19.64225426.png'),
(27, 40, 'Joe', 'Ey', 57, 'male', '914154147', '67c8fcc661d110.05298683.jpg'),
(32, 45, 'Jyren', 'Santestiban', 19, 'female', '987867521', '67cd05220ac740.71267362.png'),
(33, 47, 'Alexa', 'Santestiban', 19, 'female', '987654476', '67e345e8dc2934.96809605.png'),
(40, 54, 'Lloyd', 'Billoned', 56, 'female', '9876545461', NULL),
(41, 55, 'Admin', 'Admin', 22, 'male', '9232562547', NULL),
(43, 57, 'Davimher', 'Landas', 20, 'female', '9585895874', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_pending_payments`
-- (See below for the actual view)
--
CREATE TABLE IF NOT EXISTS `vw_pending_payments` (
`payment_id` int(11)
,`book_id` int(11)
,`total_amount` decimal(10,2)
,`downpayment_amount` decimal(10,2)
,`remaining_balance` decimal(11,2)
,`payment_type` enum('cash','credit_card','e-wallet')
,`payment_status` enum('pending','paid','refunded')
,`created_at` datetime
);

-- --------------------------------------------------------

--
-- Structure for view `customer_booking`
--
DROP TABLE IF EXISTS `customer_booking`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `customer_booking`  AS SELECT `b`.`book_id` AS `id`, `u`.`fname` AS `fname`, `u`.`lname` AS `lname`, `account`.`username` AS `email`, `r`.`room_code` AS `room_code`, `b`.`check_in` AS `check_in`, `b`.`check_out` AS `check_out`, `b`.`book_status` AS `status` FROM (((`booking` `b` join `account` on(`b`.`account_id` = `account`.`account_id`)) join `user` `u` on(`b`.`account_id` = `u`.`account_id`)) join `room` `r` on(`b`.`room_id` = `r`.`room_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `guest_booking`
--
DROP TABLE IF EXISTS `guest_booking`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `guest_booking`  AS SELECT `b`.`book_id` AS `id`, `g`.`fname` AS `fname`, `g`.`lname` AS `lname`, `g`.`email` AS `email`, `r`.`room_code` AS `room_code`, `b`.`check_in` AS `check_in`, `b`.`check_out` AS `check_out`, `b`.`book_status` AS `status` FROM (((`booking` `b` join `guest` on(`b`.`guest_id` = `guest`.`guest_id`)) join `guest` `g` on(`b`.`guest_id` = `g`.`guest_id`)) join `room` `r` on(`b`.`room_id` = `r`.`room_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `summary_payment`
--
DROP TABLE IF EXISTS `summary_payment`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `summary_payment`  AS SELECT `b`.`book_id` AS `booking_id`, 'User' AS `booking_type`, concat(`cb`.`fname`,' ',`cb`.`lname`) AS `NAME`, `cb`.`email` AS `email`, `cb`.`room_code` AS `room_code`, `r`.`room_type` AS `room_type`, `r`.`price` AS `price`, `cb`.`check_in` AS `check_in`, `cb`.`check_out` AS `check_out`, `cb`.`status` AS `book_status`, coalesce(`p`.`amount`,0) AS `amount`, coalesce(`p`.`pay_amount`,0) AS `amount_paid`, coalesce(`p`.`payment_status`,'Pending') AS `payment_status`, coalesce(`p`.`payment_type`,'N/A') AS `payment_type`, `p`.`email_receipt` AS `attached_receipt`, `p`.`created_at` AS `payment_created_at`, `p`.`payment_img` AS `receipt` FROM (((`customer_booking` `cb` left join `payment` `p` on(`cb`.`id` = `p`.`book_id`)) join `booking` `b` on(`p`.`book_id` = `b`.`book_id`)) join `room` `r` on(`cb`.`room_code` = `r`.`room_code`))union all select `b`.`book_id` AS `booking_id`,'Guest' AS `booking_type`,concat(`gb`.`fname`,' ',`gb`.`lname`) AS `NAME`,`gb`.`email` AS `email`,`gb`.`room_code` AS `room_code`,`r`.`room_type` AS `room_type`,`r`.`price` AS `price`,`gb`.`check_in` AS `check_in`,`gb`.`check_out` AS `check_out`,`gb`.`status` AS `book_status`,coalesce(`p`.`amount`,0) AS `amount`,coalesce(`p`.`pay_amount`,0) AS `amount_paid`,coalesce(`p`.`payment_status`,'Pending') AS `payment_status`,coalesce(`p`.`payment_type`,'N/A') AS `payment_type`,`p`.`email_receipt` AS `attached_receipt`,`p`.`created_at` AS `payment_created_at`,`p`.`payment_img` AS `receipt` from (((`guest_booking` `gb` left join `payment` `p` on(`gb`.`id` = `p`.`book_id`)) join `booking` `b` on(`p`.`book_id` = `b`.`book_id`)) join `room` `r` on(`gb`.`room_code` = `r`.`room_code`))  ;

-- --------------------------------------------------------

--
-- Structure for view `vw_pending_payments`
--
DROP TABLE IF EXISTS `vw_pending_payments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_pending_payments`  AS SELECT `payment`.`payment_id` AS `payment_id`, `payment`.`book_id` AS `book_id`, `payment`.`amount` AS `total_amount`, `payment`.`pay_amount` AS `downpayment_amount`, `payment`.`amount`- `payment`.`pay_amount` AS `remaining_balance`, `payment`.`payment_type` AS `payment_type`, `payment`.`payment_status` AS `payment_status`, `payment`.`created_at` AS `created_at` FROM `payment` WHERE `payment`.`amount` - `payment`.`pay_amount` > 0 ;

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
-- Constraints for table `guest`
--
ALTER TABLE `guest`
  ADD CONSTRAINT `guest_assist_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `task_templates` (`task_template_id`) ON DELETE CASCADE;

--
-- Constraints for table `task_assignees`
--
ALTER TABLE `task_assignees`
  ADD CONSTRAINT `task_assignees_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_assigness_fk_1` FOREIGN KEY (`staff_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_notifications`
--
ALTER TABLE `task_notifications`
  ADD CONSTRAINT `task_notification_fk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_notifications_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_account_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
