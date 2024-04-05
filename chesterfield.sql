-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2024 at 04:27 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chesterfield`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` tinyint(4) NOT NULL,
  `category_notes` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_notes`) VALUES
(1, 'Door'),
(2, 'External Door'),
(3, 'Junction'),
(4, 'Stairs'),
(5, 'Endpoint'),
(6, 'Corridor');

-- --------------------------------------------------------

--
-- Table structure for table `edges`
--

CREATE TABLE `edges` (
  `edge_id` smallint(6) NOT NULL,
  `start_node_id` smallint(6) NOT NULL,
  `end_node_id` smallint(6) NOT NULL,
  `distance` smallint(6) NOT NULL,
  `image` varchar(256) NOT NULL,
  `direction` tinyint(4) NOT NULL,
  `notes` varchar(256) DEFAULT NULL,
  `accessibility_notes` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `edges`
--

INSERT INTO `edges` (`edge_id`, `start_node_id`, `end_node_id`, `distance`, `image`, `direction`, `notes`, `accessibility_notes`) VALUES
(41, 50, 51, 1, 'edge_55.jpg', 1, NULL, NULL),
(42, 51, 50, 1, 'edge_54.jpg', 3, NULL, NULL),
(43, 49, 50, 1, 'edge_6.jpg', 4, NULL, NULL),
(44, 50, 49, 1, 'edge_56.jpg', 2, NULL, NULL),
(45, 39, 49, 1, 'edge_7.jpg', 3, NULL, NULL),
(46, 49, 39, 1, 'edge_5.jpg', 1, 'Arundel entrance can now be seen.', NULL),
(47, 40, 39, 1, 'edge_13.jpg', 3, NULL, NULL),
(48, 39, 40, 1, 'edge_9.jpg', 1, 'Continuing through the main entrance, the next point is the double doors ahead.', NULL),
(49, 41, 40, 1, 'edge_17.jpg', 3, NULL, NULL),
(50, 40, 41, 1, 'edge_15.jpg', 1, 'Double doors ahead - Arundel 10001.', NULL),
(51, 41, 43, 1, 'edge_18.jpg', 1, NULL, NULL),
(52, 43, 41, 1, 'edge_20.jpg', 3, NULL, NULL),
(53, 42, 40, 1, 'edge_21.jpg', 4, NULL, NULL),
(54, 40, 42, 1, 'edge_16.jpg', 2, NULL, NULL),
(55, 42, 41, 1, 'edge_22.jpg', 4, NULL, NULL),
(56, 41, 42, 1, 'edge_19.jpg', 2, NULL, NULL),
(57, 45, 42, 1, 'edge_26.jpg', 4, NULL, NULL),
(58, 42, 45, 1, 'edge_23.jpg', 1, NULL, NULL),
(59, 44, 42, 1, 'edge_31.jpg', 1, NULL, NULL),
(60, 42, 44, 1, 'edge_28.jpg', 3, NULL, NULL),
(61, 46, 42, 1, 'edge_34.jpg', 4, NULL, NULL),
(62, 42, 46, 1, 'edge_25.jpg', 2, NULL, NULL),
(63, 46, 45, 1, 'edge_29.jpg', 4, NULL, NULL),
(64, 45, 46, 1, 'edge_34.jpg', 2, NULL, NULL),
(65, 46, 44, 1, 'edge_36.jpg', 4, NULL, NULL),
(66, 44, 46, 1, 'edge_33.jpg', 4, NULL, NULL),
(67, 48, 46, 1, 'edge_38.jpg', 1, NULL, NULL),
(68, 46, 48, 1, 'edge_37.jpg', 2, NULL, NULL),
(69, 52, 45, 1, 'edge_27.jpg', 3, NULL, NULL),
(70, 45, 52, 1, 'edge_27.jpg', 1, NULL, NULL),
(73, 53, 54, 1, 'edge_2.jpg', 3, NULL, NULL),
(74, 54, 53, 1, 'edge_1.jpg', 1, 'Bridge can be seen ahead and pub on left corner.', NULL),
(77, 53, 49, 1, 'edge_3.jpg', 4, 'Follow path with car parking spaces.', NULL),
(78, 49, 53, 1, 'edge_4.jpg', 2, NULL, NULL),
(79, 44, 45, 1, 'edge_32.jpg', 1, NULL, NULL),
(80, 45, 44, 1, 'edge_24.jpg', 3, NULL, NULL),
(93, 57, 48, 1, 'edge_40.jpg', 3, NULL, NULL),
(94, 48, 57, 1, 'edge_39.jpg', 1, NULL, NULL),
(95, 59, 57, 1, '', 4, NULL, NULL),
(96, 57, 59, 1, '', 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `node`
--

CREATE TABLE `node` (
  `node_id` smallint(6) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `category` int(11) NOT NULL,
  `is_outside` tinyint(1) NOT NULL,
  `floor` tinyint(4) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `node`
--

INSERT INTO `node` (`node_id`, `name`, `category`, `is_outside`, `floor`, `x`, `y`) VALUES
(39, 'Arundel Main Entrance', 2, 0, 0, 274, 694),
(40, '', 1, 0, 0, 268, 458),
(41, '', 1, 0, 0, 274, 343),
(42, '', 1, 0, 0, 701, 403),
(43, 'Arundel 10001', 5, 0, 0, 176, 162),
(44, 'Open IT Area', 5, 0, 0, 875, 565),
(45, '', 1, 0, 0, 789, 347),
(46, '', 1, 0, 0, 1052, 417),
(48, '', 6, 0, 0, 1314, 408),
(49, '', 3, 0, 0, 271, 816),
(50, '', 3, 0, 0, -102, 828),
(51, 'Arundel Street Car Park', 5, 0, 0, -108, 381),
(52, 'Toilets', 5, 0, 0, 786, 257),
(53, '', 3, 0, 0, 1058, 798),
(54, 'Eyre Street Car Park', 5, 0, 0, 1052, 1055),
(57, 'Fire Exit', 2, 0, 0, 1318, 65),
(59, 'Ward', 5, 0, 0, 1300, -142);

-- --------------------------------------------------------

--
-- Table structure for table `path`
--

CREATE TABLE `path` (
  `path_id` int(6) NOT NULL,
  `start_node_id` smallint(6) NOT NULL,
  `end_node_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `path`
--

INSERT INTO `path` (`path_id`, `start_node_id`, `end_node_id`) VALUES
(7, 54, 44),
(8, 39, 44),
(9, 39, 52),
(10, 39, 51),
(11, 39, 57),
(12, 57, 39),
(13, 39, 54),
(14, 57, 51),
(15, 52, 43),
(16, 54, 39),
(17, 54, 43),
(18, 57, 43),
(19, 39, 43);

-- --------------------------------------------------------

--
-- Table structure for table `steps`
--

CREATE TABLE `steps` (
  `step_id` int(11) NOT NULL,
  `path_id` int(6) NOT NULL,
  `edge_id` smallint(6) NOT NULL,
  `position_in_path` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `steps`
--

INSERT INTO `steps` (`step_id`, `path_id`, `edge_id`, `position_in_path`) VALUES
(29, 7, 74, 0),
(30, 7, 77, 1),
(31, 7, 46, 2),
(32, 7, 48, 3),
(33, 7, 54, 4),
(34, 7, 60, 5),
(35, 8, 48, 0),
(36, 8, 54, 1),
(37, 8, 60, 2),
(38, 9, 48, 0),
(39, 9, 54, 1),
(40, 9, 58, 2),
(41, 9, 70, 3),
(42, 10, 45, 0),
(43, 10, 43, 1),
(44, 10, 41, 2),
(45, 11, 48, 0),
(46, 11, 54, 1),
(47, 11, 62, 2),
(48, 11, 68, 3),
(49, 11, 94, 4),
(50, 12, 93, 0),
(51, 12, 67, 1),
(52, 12, 61, 2),
(53, 12, 53, 3),
(54, 12, 47, 4),
(55, 13, 45, 0),
(56, 13, 78, 1),
(57, 13, 73, 2),
(58, 14, 93, 0),
(59, 14, 67, 1),
(60, 14, 61, 2),
(61, 14, 53, 3),
(62, 14, 47, 4),
(63, 14, 45, 5),
(64, 14, 43, 6),
(65, 14, 41, 7),
(66, 15, 69, 0),
(67, 15, 57, 1),
(68, 15, 55, 2),
(69, 15, 51, 3),
(70, 16, 74, 0),
(71, 16, 77, 1),
(72, 16, 46, 2),
(73, 17, 74, 0),
(74, 17, 77, 1),
(75, 17, 46, 2),
(76, 17, 48, 3),
(77, 17, 50, 4),
(78, 17, 51, 5),
(79, 18, 93, 0),
(80, 18, 67, 1),
(81, 18, 61, 2),
(82, 18, 55, 3),
(83, 18, 51, 4),
(84, 19, 48, 0),
(85, 19, 50, 1),
(86, 19, 51, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `category_id_2` (`category_id`);

--
-- Indexes for table `edges`
--
ALTER TABLE `edges`
  ADD PRIMARY KEY (`edge_id`);

--
-- Indexes for table `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`node_id`);

--
-- Indexes for table `path`
--
ALTER TABLE `path`
  ADD PRIMARY KEY (`path_id`),
  ADD UNIQUE KEY `path_id` (`path_id`);

--
-- Indexes for table `steps`
--
ALTER TABLE `steps`
  ADD PRIMARY KEY (`step_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `edges`
--
ALTER TABLE `edges`
  MODIFY `edge_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `node`
--
ALTER TABLE `node`
  MODIFY `node_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `path`
--
ALTER TABLE `path`
  MODIFY `path_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `steps`
--
ALTER TABLE `steps`
  MODIFY `step_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
