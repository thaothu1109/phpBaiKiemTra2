-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2025 at 11:39 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `secret_2fa` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `fullname` varchar(100) NOT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `secret_2fa`, `created_at`, `fullname`, `otp`, `otp_created_at`) VALUES
(1, 'thinhmailinh', 'thaothumai04@gmail.com', '$2y$10$ClN.TQmUZ.sz3mypffy3V.7TXVCbMwzLInLsM/upKa36GWY.r30bW', '7C4SM4XLJ7OEL2VJ', '2025-04-02 17:47:20', 'Thịnh Mai Linh', NULL, NULL),
(2, 'mailinh555', '25a4042230@hvnh.edu.vn', '$2y$10$U6MLQWP0VSfbTy7o7gNqE.s21M1Zy2IARsgBaF5OzXaA7fw7ndCPG', NULL, '2025-04-08 09:34:23', 'Thịnh Mai Linh', '$2y$10$5QJ0Y9c0nc2thxbE3FegJe.v8ivkZsMSzmQ2PXnOxRjnVxtblBGyu', '2025-04-10 15:47:35'),
(3, 'namthinh161', 'thuthao1209.bav@gmail.com', '$2y$10$GPJayX2jRVJpCXKSlkHWSeriQcnA15Hqnfkxo3XRCQIzy6zaaG54O', 'LA6XJCCXMSS3PRKF', '2025-04-10 08:48:52', 'Thịnh Ngọc Nam', '$2y$10$1o8G3rzYqe9cQKpToyaCh.TpWsDpNSnmGZMvT4C/zFmiB8wQ2mfHG', '2025-04-10 16:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `registration_date` datetime DEFAULT NULL,
  `last_password_change` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_log`
--
ALTER TABLE `user_log`
  ADD CONSTRAINT `user_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
