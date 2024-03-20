-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2024 at 03:41 PM
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
-- Database: `arundel`
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
-- Table structure for table `direction`
--

CREATE TABLE `direction` (
  `direction_id` tinyint(4) NOT NULL,
  `direction` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `direction`
--

INSERT INTO `direction` (`direction_id`, `direction`) VALUES
(6, 'Down Stairs'),
(2, 'East'),
(1, 'North'),
(3, 'South'),
(5, 'Up Stairs'),
(4, 'West');

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
(1, 0, 1, 1, 'edge_1.jpg', 1, 'Bridge can be seen ahead and pub on left corner.', NULL),
(2, 1, 0, 1, 'edge_2.jpg', 3, '', NULL),
(3, 1, 2, 1, 'edge_3.jpg', 4, 'Follow path with car parking spaces.', NULL),
(4, 2, 1, 1, 'edge_4.jpg', 2, NULL, NULL),
(5, 2, 3, 1, 'edge_5.jpg', 1, 'Arundel entrance can now be seen.', 'Accessible door button located on the left.'),
(6, 2, 23, 1, 'edge_6.jpg', 4, '', NULL),
(7, 3, 2, 1, 'edge_7.jpg', 3, NULL, 'The double doors are motion tracked. A button is also located on the right.'),
(8, 3, 4, 1, 'edge_8.jpg', 2, 'Through the main entrance, the next point is the stairs on your right.', NULL),
(9, 3, 5, 1, 'edge_9.jpg', 1, 'Continuing through the main entrance, the next point is the double doors ahead.', 'Accessible door button located below the window on the left.'),
(10, 4, 3, 1, 'edge_10.jpg', 4, NULL, 'The double doors are motion tracked. A button is also located on the right.'),
(11, 4, 5, 1, 'edge_11.jpg', 4, NULL, 'Accessible door button located below the window on the left.'),
(12, 4, 15, 1, 'edge_12.jpg', 4, 'Using the stairs or the lift, the next point is floor 1.', 'Elevator access is on the left.'),
(13, 5, 3, 1, 'edge_13.jpg', 3, NULL, NULL),
(14, 5, 4, 1, 'edge_14.jpg', 2, NULL, 'Elevator access is on the left.\r\n'),
(15, 5, 6, 1, 'edge_15.jpg', 1, 'Double doors ahead - Arundel 10001.', NULL),
(16, 5, 8, 1, 'edge_16.jpg', 2, 'Corridor with to the right a single door and double door.', 'Acccessible door button is located on the right.'),
(17, 6, 5, 1, 'edge_17.jpg', 3, NULL, 'Acccessible door button is located on the left.'),
(18, 6, 7, 1, 'edge_18.jpg', 1, NULL, NULL),
(19, 6, 8, 1, 'edge_19.jpg', 2, NULL, 'Acccessible door button is located on the right.'),
(20, 7, 6, 1, 'edge_20.jpg', 3, NULL, NULL),
(21, 8, 5, 1, 'edge_21.jpg', 4, NULL, 'Acccessible door button is located on the left.'),
(22, 8, 6, 1, 'edge_22.jpg', 4, NULL, 'Acccessible door button is located on the right.'),
(23, 8, 9, 1, 'edge_23.jpg', 1, 'Open area with toilets located on the left.', 'Disabled toilets are located on the left, after the mens and womens toilets.'),
(24, 8, 11, 1, 'edge_24.jpg', 3, NULL, NULL),
(25, 8, 12, 1, 'edge_25.jpg', 2, NULL, 'Acccessible door button is located on the left.'),
(26, 9, 8, 1, 'edge_26.jpg', 3, NULL, NULL),
(27, 9, 10, 1, 'edge_27.jpg', 1, NULL, 'Disabled toilets are located on the left, after the mens and womens toilets.'),
(28, 9, 11, 1, 'edge_28.jpg', 3, NULL, NULL),
(29, 9, 12, 1, 'edge_29.jpg', 2, NULL, NULL),
(30, 10, 9, 1, 'edge_30.jpg', 3, NULL, NULL),
(31, 11, 8, 1, 'edge_31.jpg', 1, NULL, 'Acccessible door button is located on the right.'),
(32, 11, 9, 1, 'edge_32.jpg', 1, NULL, 'Disabled toilets are located on the right.'),
(33, 11, 12, 1, 'edge_33.jpg', 1, NULL, 'Acccessible door button is located on the left.'),
(34, 12, 8, 1, 'edge_34.jpg', 4, NULL, 'Acccessible door button is located on the right.'),
(35, 12, 9, 1, 'edge_35.jpg', 1, NULL, 'Disabled toilets are located on the right.'),
(36, 12, 11, 1, 'edge_36.jpg', 3, NULL, NULL),
(37, 12, 13, 1, 'edge_37.jpg', 2, NULL, NULL),
(38, 13, 12, 1, 'edge_38.jpg', 4, NULL, 'Acccessible door button is located on the left.'),
(39, 13, 14, 1, 'edge_39.jpg', 1, NULL, NULL),
(40, 14, 13, 1, 'edge_40.jpg', 3, NULL, NULL),
(41, 15, 4, 1, 'edge_41.jpg', 4, NULL, 'Elevator access is on the left.'),
(42, 15, 16, 1, 'edge_42.jpg', 4, 'Using the stairs or the lift, the next point is floor 2.', 'Elevator access is on the left.'),
(43, 16, 15, 1, 'edge_43.jpg', 4, NULL, 'Elevator access is on the left.'),
(44, 16, 17, 1, 'edge_44.jpg', 4, NULL, 'Elevator access is on the left.'),
(45, 17, 18, 1, 'edge_45.jpg', 1, 'Level 3, double doors leading to Charles Street.', 'Acccessible door button is located on the right.'),
(46, 17, 16, 1, 'edge_46.jpg', 4, NULL, 'Elevator access is on the left.'),
(47, 18, 17, 1, 'edge_47.jpg', 3, NULL, 'Acccessible door button is located on the left.'),
(48, 18, 19, 1, 'edge_48.jpg', 2, NULL, NULL),
(49, 19, 18, 1, 'edge_49.jpg', 4, NULL, NULL),
(50, 19, 20, 1, 'edge_50.jpg', 1, NULL, NULL),
(51, 19, 21, 1, 'edge_51.jpg', 2, 'After reaching floor 2, continue to the double door labeled \'Leading to Charles Street\'.', NULL),
(52, 20, 19, 1, 'edge_52.jpg', 3, NULL, NULL),
(53, 21, 19, 1, 'edge_53.jpg', 4, NULL, NULL),
(54, 22, 23, 1, 'edge_54.jpg', 3, NULL, NULL),
(55, 23, 22, 1, 'edge_55.jpg', 1, NULL, NULL),
(56, 23, 2, 1, 'edge_56.jpg', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `node`
--

CREATE TABLE `node` (
  `node_id` smallint(6) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `category` int(11) NOT NULL,
  `is_outside` tinyint(1) NOT NULL,
  `floor` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `node`
--

INSERT INTO `node` (`node_id`, `name`, `category`, `is_outside`, `floor`) VALUES
(0, 'Eyre Street Car Park', 5, 1, 0),
(1, NULL, 3, 0, 0),
(2, NULL, 3, 0, 0),
(3, 'Arundel Main Entrance', 2, 1, 0),
(4, 'Ground Floor Stairs', 4, 0, 0),
(5, NULL, 1, 0, 0),
(6, NULL, 1, 0, 0),
(7, 'Arundel 10001', 5, 0, 0),
(8, NULL, 1, 0, 0),
(9, NULL, 1, 0, 0),
(10, 'Toilets', 5, 0, 0),
(11, 'Open IT Area', 5, 0, 0),
(12, NULL, 1, 0, 0),
(13, NULL, 6, 0, 0),
(14, 'Brown Lane Fire Escape', 5, 0, 0),
(15, 'First Floor Stairs', 4, 0, 1),
(16, 'Second Floor Stairs', 4, 0, 2),
(17, 'Third Floor Stairs', 4, 0, 3),
(18, NULL, 1, 0, 3),
(19, NULL, 3, 0, 3),
(20, 'Floor 3 Open IT Area', 5, 0, 3),
(21, 'Charles Street Connection', 5, 0, 3),
(22, 'Arundel Street Car Park', 5, 1, 0),
(23, NULL, 3, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `path`
--

CREATE TABLE `path` (
  `path_id` smallint(6) NOT NULL,
  `start_node_id` smallint(6) NOT NULL,
  `end_node_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `steps`
--

CREATE TABLE `steps` (
  `step_id` int(11) NOT NULL,
  `edge_id` smallint(6) NOT NULL,
  `position_in_path` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `direction`
--
ALTER TABLE `direction`
  ADD PRIMARY KEY (`direction_id`),
  ADD UNIQUE KEY `direction` (`direction`),
  ADD KEY `direction_id` (`direction_id`);

--
-- Indexes for table `edges`
--
ALTER TABLE `edges`
  ADD PRIMARY KEY (`edge_id`),
  ADD KEY `edge_id` (`edge_id`),
  ADD KEY `direction` (`direction`),
  ADD KEY `edges_ibfk_2` (`end_node_id`),
  ADD KEY `edges_ibfk_1` (`start_node_id`);

--
-- Indexes for table `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`node_id`),
  ADD KEY `node_id` (`node_id`);

--
-- Indexes for table `path`
--
ALTER TABLE `path`
  ADD PRIMARY KEY (`path_id`),
  ADD KEY `start_node_id` (`start_node_id`),
  ADD KEY `end_node_id` (`end_node_id`);

--
-- Indexes for table `steps`
--
ALTER TABLE `steps`
  ADD PRIMARY KEY (`step_id`,`edge_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `direction`
--
ALTER TABLE `direction`
  MODIFY `direction_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `node`
--
ALTER TABLE `node`
  MODIFY `node_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `path`
--
ALTER TABLE `path`
  MODIFY `path_id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `path`
--
ALTER TABLE `path`
  ADD CONSTRAINT `path_ibfk_1` FOREIGN KEY (`start_node_id`) REFERENCES `node` (`node_id`),
  ADD CONSTRAINT `path_ibfk_2` FOREIGN KEY (`end_node_id`) REFERENCES `node` (`node_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
