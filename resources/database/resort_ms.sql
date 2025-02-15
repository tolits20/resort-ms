-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2025 at 05:57 PM
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(5, 'levipenaverde@example.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'admin', 'activate', '2025-02-14 15:10:14', '2025-02-14 15:10:14'),
(9, 'tolits@example.com', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', 'user', 'activate', '2025-02-15 05:24:00', '2025-02-15 08:13:20');

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
  `status` enum('pending','confirmed','check in','check out','cancelled') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`book_id`),
  KEY `book_room_fk` (`room_id`),
  KEY `book_account_fk` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `book_id` int(11) NOT NULL,
  `payment_method` enum('cash','credit card','e-payment') NOT NULL,
  `payment_status` enum('pending','paid','refunded') NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `payment_book_fk` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_code` varchar(50) NOT NULL,
  `type` enum('standard','premium') NOT NULL,
  `status` enum('available','booked','under maintenance') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_ code` (`room_code`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_code`, `type`, `status`, `created_at`, `updated_at`) VALUES
(9, 'room101', 'premium', 'available', '2025-02-15 15:37:50', '2025-02-15 16:22:20');

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
(9, '167b0b4ce07b71.png', '2025-02-15 15:37:50'),
(9, '167b0bf3c6b0be.png', '2025-02-15 16:22:20'),
(9, '167b0bf3c6b747.png', '2025-02-15 16:22:20');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `account_id`, `fname`, `lname`, `age`, `gender`, `contact`, `profile_img`, `created_at`, `updated_at`) VALUES
(5, 5, 'Levi Asher', 'Penaverde', 20, 'male', '987867546', '67af5cd6192634.90705227.jpg', '2025-02-14 15:10:14', '2025-02-14 15:10:14'),
(9, 9, 'Angelito', 'Jacalan', 20, 'male', '9876544461', '67b04ca01eed56.63896355.jpg', '2025-02-15 05:24:00', '2025-02-15 08:13:20');

--
-- Constraints for dumped tables
--

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
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_account_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
