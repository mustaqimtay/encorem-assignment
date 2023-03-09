-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: mydb:3306
-- Generation Time: Feb 28, 2023 at 03:09 AM
-- Server version: 8.0.28
-- PHP Version: 8.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `encore_med`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int UNSIGNED NOT NULL,
  `code` varchar(32) NOT NULL,
  `appt_datetime` datetime NOT NULL,
  `patient` text NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `arrived_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `code`, `appt_datetime`, `patient`, `status`, `arrived_at`, `created_at`) VALUES
(1, 'DyRowcjF', '2023-02-28 11:10:01', '{\"name\":\"Ahmad Mustaqim Tay\",\"ic\":\"89041045331\",\"mrn\":null,\"mobileNo\":\"0182362496\"}', 'rescheduled', '2023-02-28 09:10:01', '2023-02-28 10:23:49'),
(2, 'KJPHKBCX', '2023-02-28 09:00:00', '{\"name\":\"Ahmad Mustaqim Tay\",\"ic\":\"89041045331\",\"mrn\":null,\"mobileNo\":\"0182362496\"}', 'pending', NULL, '2023-02-28 10:46:00'),
(3, 'LK9PXQDF', '2023-02-28 09:00:00', '{\"name\":\"Ahmad Mustaqim Tay\",\"ic\":\"89041045331\",\"mrn\":null,\"mobileNo\":\"0182362496\"}', 'pending', NULL, '2023-02-28 10:46:15'),
(4, 'CLPHZJQY', '2023-02-28 09:00:00', '{\"name\":\"Ahmad Mustaqim Tay\",\"ic\":\"89041045331\",\"mrn\":null,\"mobileNo\":\"0182362496\"}', 'pending', NULL, '2023-02-28 10:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'superadmin', '$2y$10$Io8NDNGPs79pbr.Px7T94Oif9le2Vzm3optqvsOjwrRoi6o6lDG1W');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
