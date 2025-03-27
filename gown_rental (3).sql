-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 04:30 PM
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
-- Database: `gown_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `gown_name` int(11) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gown_id` int(11) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `contact_number` int(11) NOT NULL,
  `payment_status` enum('unpaid','partial','paid') DEFAULT 'unpaid',
  `pickup_date` date DEFAULT NULL,
  `pickup_time` time DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `return_time` time DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `gcash_references` varchar(50) DEFAULT NULL,
  `notes` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `full_name`, `email`, `message`, `created_at`) VALUES
(1, 'Sathya Kilat', 'sathya@example.com', 'hghfg', '2025-03-23 13:35:48'),
(6, 'Sathya Kilat', 'sathya@example.com', 'edfs', '2025-03-23 14:29:55'),
(7, 'Sathya Kilat', 'Sathya@gmail.com', 'hello', '2025-03-23 16:04:18'),
(8, '<script>alert(\'hello\')</script>', 'example@gmail.com', '<script>alert(\'hello\')</script>', '2025-03-24 06:12:41'),
(9, 'Sathya Kilat', 'sathya@example.com', 'hello\r\n', '2025-03-24 16:47:22'),
(10, 'Sathya Kilat', 'sathya@example.com', 'sdx', '2025-03-24 17:12:46');

-- --------------------------------------------------------

--
-- Table structure for table `gowns`
--

CREATE TABLE `gowns` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gowns`
--

INSERT INTO `gowns` (`id`, `name`, `price`, `created_at`) VALUES
(1, 'Wedding Gown 1', 1000.00, '2025-03-25 14:09:20'),
(2, 'Wedding Gown 2', 1000.00, '2025-03-25 14:10:06'),
(3, 'Wedding Gown 3', 500.00, '2025-03-25 14:11:11'),
(4, 'Wedding Gown 4', 500.00, '2025-03-25 14:11:50'),
(5, 'Wedding Gown 5', 500.00, '2025-03-25 14:12:07'),
(6, 'Wedding Gown 6', 500.00, '2025-03-25 14:12:25'),
(7, 'Wedding Gown 7', 500.00, '2025-03-25 14:13:03'),
(8, 'Ball Gown 1 ', 1500.00, '2025-03-25 14:13:45'),
(9, 'Ball Gown 2', 1500.00, '2025-03-25 14:14:01'),
(10, 'Ball Gown 3', 1500.00, '2025-03-25 14:14:52'),
(11, 'Ball Gown 4', 2000.00, '2025-03-25 14:15:28'),
(12, 'Ball Gown 5', 2000.00, '2025-03-25 14:15:59'),
(13, 'Ball Gown 6', 1500.00, '2025-03-25 14:17:43'),
(14, 'Ball Gown 7', 1500.00, '2025-03-25 14:18:26'),
(15, 'Ball Gown 8', 800.00, '2025-03-25 14:18:53'),
(16, 'Ball Gown 9', 800.00, '2025-03-25 14:19:17'),
(17, 'Ball Gown 10', 1500.00, '2025-03-25 14:19:51'),
(18, 'Ball Gown 11', 800.00, '2025-03-25 14:20:28'),
(19, 'Ball Gown 12', 1500.00, '2025-03-25 14:22:01'),
(20, 'Ball Gown 13', 1800.00, '2025-03-25 14:22:18'),
(21, 'Ball Gown 14', 1500.00, '2025-03-25 14:22:57'),
(22, 'Ball Gown 15', 1500.00, '2025-03-25 14:24:19'),
(23, 'Ball Gown 16 ', 2000.00, '2025-03-25 14:26:28'),
(24, 'Ball Gown 17', 1000.00, '2025-03-25 14:27:23'),
(25, 'Ball Gown 18', 1500.00, '2025-03-25 14:28:49'),
(26, 'Ball Gown 19', 2000.00, '2025-03-25 14:29:59'),
(27, 'Raffles Gown 1', 1000.00, '2025-03-25 14:32:59'),
(28, 'Raffles Gown 2', 1500.00, '2025-03-25 14:33:15'),
(29, 'Raffles Gown 3', 1500.00, '2025-03-25 14:33:32'),
(30, 'Raffles Gown 4', 1500.00, '2025-03-25 14:33:53'),
(31, 'Raffles Gown 5', 2000.00, '2025-03-25 14:34:21'),
(32, 'Raffles Gown 6', 1500.00, '2025-03-25 14:34:39'),
(33, 'Raffles Gown 7', 500.00, '2025-03-25 14:35:06'),
(34, 'Pageant Gown 1', 500.00, '2025-03-25 14:35:28'),
(35, 'Pageant Gown 2', 800.00, '2025-03-25 14:35:48'),
(36, 'Pageant Gown 3', 500.00, '2025-03-25 14:36:13'),
(37, 'Pageant Gown 4', 500.00, '2025-03-25 14:36:29'),
(38, 'Pageant Gown 5', 1500.00, '2025-03-25 14:36:48'),
(39, 'Pageant Gown 6', 500.00, '2025-03-25 14:37:07'),
(40, 'Pageant Gown 7', 800.00, '2025-03-25 14:37:27');

-- --------------------------------------------------------

--
-- Table structure for table `manage_user`
--

CREATE TABLE `manage_user` (
  `id` int(11) NOT NULL,
  `total_user` int(11) NOT NULL,
  `today_user` int(11) NOT NULL,
  `this_month_user` datetime NOT NULL,
  `this_year_user` int(11) NOT NULL,
  `created_at` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manage_user`
--

INSERT INTO `manage_user` (`id`, `total_user`, `today_user`, `this_month_user`, `this_year_user`, `created_at`) VALUES
(1, 0, 0, '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `rental_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gown_id` int(11) NOT NULL,
  `rental_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `user_id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`user_id`, `role`) VALUES
(1, 'admin'),
(2, 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`, `role_id`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$4EMKXV/ZPTNfcPKbeEKlPOonqXC0YRQOpY9rQz/pJN8uxLAgOtCZW', '2025-03-19 09:50:38', 1),
(2, 'Joemari Obrial', 'Thya@gmail.com', '$2y$10$WKhHVWAG3rvt7V4tft9AXesC7Yi3OdbDjqyPy8XzLjKKAuxQ2clIa', '2025-03-19 09:52:35', 2);

-- --------------------------------------------------------

--
-- Table structure for table `view_report`
--

CREATE TABLE `view_report` (
  `id` int(11) NOT NULL,
  `total_booking` varchar(255) NOT NULL,
  `pending_booking` varchar(255) NOT NULL,
  `completed_booking` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `full_name` (`full_name`),
  ADD KEY `email` (`email`) USING BTREE;

--
-- Indexes for table `gowns`
--
ALTER TABLE `gowns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manage_user`
--
ALTER TABLE `manage_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`rental_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `view_report`
--
ALTER TABLE `view_report`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `gowns`
--
ALTER TABLE `gowns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `manage_user`
--
ALTER TABLE `manage_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `view_report`
--
ALTER TABLE `view_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`gown_id`) REFERENCES `gowns` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
