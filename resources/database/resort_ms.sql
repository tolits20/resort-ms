-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2025 at 06:19 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `status` enum('activate','deactivate') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(5, 'levipenaverde@example.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'admin', 'activate', '2025-02-14 15:10:14', '2025-02-14 15:10:14'),
(9, 'tolits@example.com', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'admin', 'activate', '2025-02-15 05:24:00', '2025-02-25 08:15:56'),
(10, 'allan@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'admin', 'deactivate', '2025-02-16 10:43:34', '2025-02-26 00:15:13'),
(12, 'catuera@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'user', 'activate', '2025-02-23 15:06:58', '2025-02-25 04:30:37'),
(13, 'ego@example.com', '4dfd0d9665c9f63e437e054f57d4407867dacce5', 'admin', 'deactivate', '2025-02-24 15:25:09', '2025-02-25 01:32:39'),
(27, 'user@example.com', '88ea39439e74fa27c09a4fc0bc8ebe6d00978392', 'user', 'activate', '2025-02-26 16:17:06', NULL),
(28, 'admin@example.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'admin', 'activate', '2025-02-26 16:24:26', NULL),
(30, 'user2@example.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'user', 'activate', '2025-02-26 16:51:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_notification`
--

CREATE TABLE `account_notification` (
  `cnotif_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `account_notification` enum('create','update') NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(10, 28, 'create', '2025-02-27 00:24:26'),
(11, 30, 'create', '2025-02-27 00:51:32');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `book_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `book_status` enum('pending','confirmed','cancelled') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`book_id`, `account_id`, `room_id`, `check_in`, `check_out`, `book_status`, `price`, `created_at`, `updated_at`) VALUES
(5, 12, 15, '2025-02-25', '2025-02-27', 'pending', 5000.00, '2025-02-25 12:32:02', '2025-02-26 17:19:16'),
(6, 10, 12, '2025-02-26', '2025-02-27', 'confirmed', 4500.00, '2025-02-25 12:37:19', '2025-02-26 17:19:16'),
(7, 27, 9, '2025-02-28', '2025-03-01', 'confirmed', 500.00, '2025-02-27 00:23:31', '2025-02-26 17:19:16'),
(8, 30, 14, '2025-02-28', '2025-03-01', 'confirmed', 5000.00, '2025-02-27 00:52:03', '2025-02-26 17:19:16');

-- --------------------------------------------------------

--
-- Table structure for table `booking_notification`
--

CREATE TABLE `booking_notification` (
  `booking_notif_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `booking_status` varchar(50) NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_notification`
--

INSERT INTO `booking_notification` (`booking_notif_id`, `book_id`, `booking_status`, `Date`) VALUES
(1, 5, 'pending', '2025-02-25 12:32:28'),
(2, 6, 'confirmed', '2025-02-25 12:37:34'),
(3, 7, 'pending', '2025-02-27 00:23:31'),
(4, 8, 'pending', '2025-02-27 00:52:03');

-- --------------------------------------------------------

--
-- Stand-in structure for view `book_payment`
-- (See below for the actual view)
--
CREATE TABLE `book_payment` (
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
CREATE TABLE `customer_booking` (
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
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `account_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(2, 27, 3, 'k lang', '2025-02-26 16:36:43', '2025-02-26 17:01:16');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `book_id` int(11) NOT NULL,
  `payment_method` enum('cash','credit card','e-payment') NOT NULL,
  `payment_status` enum('pending','completed','refunded') NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `room_code` varchar(50) NOT NULL,
  `room_type` enum('standard','premium') NOT NULL,
  `room_status` enum('available','booked','under maintenance') NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_code`, `room_type`, `room_status`, `price`, `created_at`, `updated_at`) VALUES
(9, 'room101', 'premium', 'available', 500, '2025-02-15 15:37:50', '2025-02-26 16:23:31'),
(12, 'room102', 'premium', 'booked', 1000, '2025-02-19 12:40:06', '2025-02-25 04:39:53'),
(14, 'room103', 'premium', 'booked', 5000, '2025-02-22 03:31:37', '2025-02-26 16:52:03'),
(15, 'room104', 'premium', 'booked', 6000, '2025-02-25 01:27:38', '2025-02-25 04:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `room_gallery`
--

CREATE TABLE `room_gallery` (
  `room_id` int(11) NOT NULL,
  `room_img` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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

CREATE TABLE `room_notification` (
  `rnotif_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `room_notification` enum('create','update') NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(8, 9, 'update', '2025-02-26 08:15:26');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `contact` varchar(15) NOT NULL,
  `profile_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `account_id`, `fname`, `lname`, `age`, `gender`, `contact`, `profile_img`) VALUES
(5, 5, 'Levi', 'Penaverde', 20, 'male', '987867546', '67af5cd6192634.90705227.jpg'),
(9, 9, 'Angelito', 'Jacalan', 21, 'female', '09876544461', '67b195f85239c1.78592261.jpg'),
(10, 10, 'Allan', 'Monforte', 98, 'male', '9112245667', '67b1c15630d272.47796055.png'),
(12, 12, 'Melvin', 'Catuera', 20, 'female', '9123456789', '67bc9b041413e9.81245551.png'),
(13, 13, 'Ianzae', 'Ego', 21, 'female', '9876543211', '67bc8f552ae286.40054461.png'),
(14, 27, 'Fname', 'Lname', 98, 'male', '9231231236', '67bf3e820512d9.05980251.png'),
(15, 28, 'Admin', 'Admin', 20, 'male', '9141541478', '67bf403ac326f9.36027168.png'),
(17, 30, 'Asd', 'Lname', 98, 'male', '9121251254', '67bf469401d450.78560708.png');

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
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `account_notification`
--
ALTER TABLE `account_notification`
  ADD PRIMARY KEY (`cnotif_id`),
  ADD KEY `account_notification_fk` (`account_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `book_room_fk` (`room_id`),
  ADD KEY `book_account_fk` (`account_id`);

--
-- Indexes for table `booking_notification`
--
ALTER TABLE `booking_notification`
  ADD PRIMARY KEY (`booking_notif_id`),
  ADD KEY `booking_notif_id` (`book_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `feedback_account_fk` (`account_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD KEY `payment_book_fk` (`book_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_ code` (`room_code`);

--
-- Indexes for table `room_gallery`
--
ALTER TABLE `room_gallery`
  ADD KEY `galley_room_fk` (`room_id`);

--
-- Indexes for table `room_notification`
--
ALTER TABLE `room_notification`
  ADD PRIMARY KEY (`rnotif_id`),
  ADD KEY `room_notification_fk` (`room_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `name` (`fname`),
  ADD UNIQUE KEY `contact` (`contact`),
  ADD KEY `user_account_fk` (`account_id`),
  ADD KEY `unique_fullname` (`fname`,`lname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `account_notification`
--
ALTER TABLE `account_notification`
  MODIFY `cnotif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `booking_notification`
--
ALTER TABLE `booking_notification`
  MODIFY `booking_notif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `room_notification`
--
ALTER TABLE `room_notification`
  MODIFY `rnotif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
-- Constraints for table `booking_notification`
--
ALTER TABLE `booking_notification`
  ADD CONSTRAINT `booking_notif_id` FOREIGN KEY (`book_id`) REFERENCES `booking` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_account_fk` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
