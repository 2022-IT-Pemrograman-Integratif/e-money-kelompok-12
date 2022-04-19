-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2022 at 06:04 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-money-kelompok-12`
--

-- --------------------------------------------------------

--
-- Table structure for table `history_topup`
--

CREATE TABLE `history_topup` (
  `history_topup_id` bigint(20) NOT NULL,
  `history_topup_number` varchar(255) NOT NULL,
  `history_topup_name` varchar(255) NOT NULL,
  `history_topup_amount` float NOT NULL,
  `history_topup_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `history_transfer`
--

CREATE TABLE `history_transfer` (
  `history_transfer_id` bigint(20) NOT NULL,
  `history_transfer_number` varchar(255) NOT NULL,
  `history_transfer_number_name` varchar(255) NOT NULL,
  `history_transfer_tujuan` varchar(255) NOT NULL,
  `history_transfer_tujuan_name` varchar(255) NOT NULL,
  `history_transfer_amount` float NOT NULL,
  `history_transfer_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_number` varchar(255) NOT NULL,
  `users_name` varchar(255) NOT NULL,
  `users_password` varchar(255) NOT NULL,
  `users_role` varchar(255) NOT NULL,
  `users_balance` float NOT NULL DEFAULT 0,
  `users_dateCreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_number`, `users_name`, `users_password`, `users_role`, `users_balance`, `users_dateCreated`) VALUES
('082140605035', 'admin', '$2y$10$vQTXYQWG3DRQtJR.fzDAAexhAC.7tQqjedao3CEn2RsoLJCDIG9Vq', 'admin', 19830100, '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `history_topup`
--
ALTER TABLE `history_topup`
  ADD PRIMARY KEY (`history_topup_id`);

--
-- Indexes for table `history_transfer`
--
ALTER TABLE `history_transfer`
  ADD PRIMARY KEY (`history_transfer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `history_topup`
--
ALTER TABLE `history_topup`
  MODIFY `history_topup_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_transfer`
--
ALTER TABLE `history_transfer`
  MODIFY `history_transfer_id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
