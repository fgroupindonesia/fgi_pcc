-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2023 at 01:55 AM
-- Server version: 5.5.39
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fgi_pcc`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_commands`
--

CREATE TABLE `data_commands` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_uuid` varchar(75) NOT NULL,
  `remote_uuid` varchar(75) NOT NULL,
  `command` varchar(200) NOT NULL,
  `applied` tinyint(1) NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_commands`
--

INSERT INTO `data_commands` (`id`, `client_uuid`, `remote_uuid`, `command`, `applied`, `date_modified`, `date_created`) VALUES
(1, '665', '45', 'restart,shutdown,kill-app', 1, '2022-07-23 17:36:55', '2023-04-11 13:35:23'),
(2, '775', '45', '', 0, '2022-07-24 10:00:12', '2023-04-04 13:35:27'),
(3, '2222', '123', '', 0, '2023-04-18 00:15:48', '2023-04-18 07:15:48');

-- --------------------------------------------------------

--
-- Table structure for table `data_licenses`
--

CREATE TABLE `data_licenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` varchar(75) NOT NULL,
  `serial_number` varchar(75) NOT NULL,
  `email` varchar(75) NOT NULL,
  `whatsapp` varchar(75) NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_licenses`
--

INSERT INTO `data_licenses` (`id`, `uuid`, `serial_number`, `email`, `whatsapp`, `date_modified`, `date_created`) VALUES
(1, '22', '4r0-bffAFq', 'sesuatu@home.com', '08123', '2022-07-23 16:05:49', '2023-04-04 10:33:25');

-- --------------------------------------------------------

--
-- Table structure for table `data_tokens`
--

CREATE TABLE `data_tokens` (
  `id` int(10) NOT NULL,
  `username` varchar(75) NOT NULL,
  `token` varchar(75) NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_tokens`
--

INSERT INTO `data_tokens` (`id`, `username`, `token`, `date_modified`, `date_created`) VALUES
(3, '123', 'Dfevzj18sz9KdrD1UwH1IHU2Y', '2023-04-17 23:13:14', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `data_trackers`
--

CREATE TABLE `data_trackers` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` varchar(75) NOT NULL,
  `location_lat` decimal(11,8) NOT NULL,
  `location_long` decimal(11,8) NOT NULL,
  `city` varchar(75) NOT NULL,
  `status_device` tinyint(1) NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_trackers`
--

INSERT INTO `data_trackers` (`id`, `uuid`, `location_lat`, `location_long`, `city`, `status_device`, `date_modified`, `date_created`) VALUES
(1, '775', '999.99999999', '-10.00000000', 'bandung', 0, '2022-07-24 10:05:44', '2023-04-10 00:00:00'),
(2, '2222', '19.11100000', '12.33300000', 'jakarta', 1, '2023-04-18 00:15:48', '2023-04-18 07:15:48');

-- --------------------------------------------------------

--
-- Table structure for table `data_users`
--

CREATE TABLE `data_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` varchar(75) NOT NULL,
  `ip_address` varchar(75) NOT NULL,
  `membership` tinyint(1) NOT NULL,
  `code` tinyint(1) NOT NULL,
  `country` varchar(75) NOT NULL,
  `email` varchar(75) NOT NULL,
  `whatsapp` varchar(75) NOT NULL,
  `fullname` varchar(75) NOT NULL,
  `status` varchar(75) NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_users`
--

INSERT INTO `data_users` (`id`, `uuid`, `ip_address`, `membership`, `code`, `country`, `email`, `whatsapp`, `fullname`, `status`, `date_modified`, `date_created`) VALUES
(1, '123la', '192.168.0.1', 1, 1, '', '', '', '', '', '2022-04-25 09:11:29', '2023-04-04 13:24:44'),
(2, '123la', '192.168.0.1', 1, 1, '', '', '', '', '', '2022-04-25 09:11:52', '2023-04-04 13:24:48'),
(3, '45', '192.168.0.1', 1, 1, '', '', '', '', '', '2022-07-23 17:17:55', '2023-04-11 13:24:53'),
(4, '22', '23.123.123.123', 1, 0, '', '', '', '', '', '2022-07-23 16:40:10', '2023-04-11 13:24:50'),
(5, '225', '112.123.123.123', 1, 0, '', '', '', '', '', '2022-07-23 17:09:44', '2023-04-04 13:24:56'),
(6, '665', '112.123.123.123', 1, 0, '', '', '', '', '', '2022-07-23 17:10:25', '2023-04-05 13:24:59'),
(7, '775', '192.123.123.123', 0, 0, '', '', '', '', '', '2022-07-24 10:00:12', '2023-04-05 13:25:02'),
(15, '123', '103.147.9.19', 0, 1, 'id', 'menang@selalu.com', '1111', 'michael jackson', 'active', '2023-04-18 00:03:34', '2023-04-18 06:13:14'),
(16, '2222', '111.111.111.111', 0, 0, 'id', '', '', '', 'active', '2023-04-18 00:16:51', '2023-04-18 07:15:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_commands`
--
ALTER TABLE `data_commands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_licenses`
--
ALTER TABLE `data_licenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_tokens`
--
ALTER TABLE `data_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_trackers`
--
ALTER TABLE `data_trackers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_users`
--
ALTER TABLE `data_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_commands`
--
ALTER TABLE `data_commands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `data_licenses`
--
ALTER TABLE `data_licenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `data_tokens`
--
ALTER TABLE `data_tokens`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `data_trackers`
--
ALTER TABLE `data_trackers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_users`
--
ALTER TABLE `data_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
