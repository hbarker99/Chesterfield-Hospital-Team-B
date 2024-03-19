-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2024 at 10:30 PM
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
(1, 1, 2, 1, 'edge_1.jpg', 1, 'Bridge can be seen ahead and pub on left corner.', ''),
(2, 2, 1, 1, 'edge_2.jpg', 3, '', ''),
(3, 2, 3, 1, 'edge_3.jpg', 4, 'Follow path with car parking spaces.', ''),
(4, 3, 2, 1, 'edge_4.jpg', 2, '', ''),
(5, 3, 4, 1, 'edge_5.jpg', 1, 'Arundel entrance can now be seen.', 'Accessible door button located on the left.'),
(6, 3, 24, 1, 'edge_6.jpg', 4, '', ''),
(7, 4, 3, 1, 'edge_7.jpg', 3, '', 'The double doors are motion tracked. A button is also located on the right.'),
(8, 4, 5, 1, 'edge_8.jpg', 2, '\"Through the main entrance, the next point is the stairs on your right.\"', ''),
(9, 4, 6, 1, 'edge_9.jpg', 1, '\"Continuing through the main entrance, the next point is the double doors ahead.\"', 'Accessible door button located below the window on the left.'),
(10, 5, 4, 1, 'edge_10.jpg', 4, '', 'The double doors are motion tracked. A button is also located on the right.'),
(11, 5, 6, 1, 'edge_11.jpg', 4, '', 'Accessible door button located below the window on the left.'),
(12, 5, 16, 1, 'edge_12.jpg', 4, '\"Using the stairs or the lift, the next point is floor 1.\"', 'Elevator access is on the left.'),
(13, 6, 4, 1, 'edge_13.jpg', 3, '', ''),
(14, 6, 5, 1, 'edge_14.jpg', 2, '', '\"Elevator access is on the left.'),
(15, 6, 7, 1, 'edge_15.jpg', 1, 'Double doors ahead - Arundel 10001.', ''),
(16, 6, 9, 1, 'edge_16.jpg', 2, 'Corridor with to the right a single door and double door.', 'Acccessible door button is located on the right.'),
(17, 7, 6, 1, 'edge_17.jpg', 3, '', 'Acccessible door button is located on the left.'),
(18, 7, 8, 1, 'edge_18.jpg', 1, '', ''),
(19, 7, 9, 1, 'edge_19.jpg', 2, '', 'Acccessible door button is located on the right.'),
(20, 8, 7, 1, 'edge_20.jpg', 3, '', ''),
(21, 9, 6, 1, 'edge_21.jpg', 4, '', 'Acccessible door button is located on the left.'),
(22, 9, 7, 1, 'edge_22.jpg', 4, '', 'Acccessible door button is located on the right.'),
(23, 9, 10, 1, 'edge_23.jpg', 1, 'Open area with toilets located on the left.', '\"Disabled toilets are located on the left, after the mens and womens toilets.\"'),
(24, 9, 12, 1, 'edge_24.jpg', 3, '', ''),
(25, 9, 13, 1, 'edge_25.jpg', 2, '', 'Acccessible door button is located on the left.'),
(26, 10, 9, 1, 'edge_26.jpg', 3, '', ''),
(27, 10, 11, 1, 'edge_27.jpg', 1, '', '\"Disabled toilets are located on the left, after the mens and womens toilets.\"'),
(28, 10, 12, 1, 'edge_28.jpg', 3, '', ''),
(29, 10, 13, 1, 'edge_29.jpg', 2, '', ''),
(30, 11, 10, 1, 'edge_30.jpg', 3, '', ''),
(31, 12, 9, 1, 'edge_31.jpg', 1, '', 'Acccessible door button is located on the right.'),
(32, 12, 10, 1, 'edge_32.jpg', 1, '', 'Disabled toilets are located on the right.'),
(33, 12, 13, 1, 'edge_33.jpg', 1, '', 'Acccessible door button is located on the left.'),
(34, 13, 9, 1, 'edge_34.jpg', 4, '', 'Acccessible door button is located on the right.'),
(35, 13, 10, 1, 'edge_35.jpg', 1, '', 'Disabled toilets are located on the right.'),
(36, 13, 12, 1, 'edge_36.jpg', 3, '', ''),
(37, 13, 14, 1, 'edge_37.jpg', 2, '', ''),
(38, 14, 13, 1, 'edge_38.jpg', 4, '', 'Acccessible door button is located on the left.'),
(39, 14, 15, 1, 'edge_39.jpg', 1, '', ''),
(40, 15, 14, 1, 'edge_40.jpg', 3, '', ''),
(41, 16, 5, 1, 'edge_41.jpg', 4, '', 'Elevator access is on the left.'),
(42, 16, 17, 1, 'edge_42.jpg', 4, '\"Using the stairs or the lift, the next point is floor 2.\"', 'Elevator access is on the left.'),
(43, 17, 16, 1, 'edge_43.jpg', 4, '', 'Elevator access is on the left.'),
(44, 17, 18, 1, 'edge_44.jpg', 4, '', 'Elevator access is on the left.'),
(45, 18, 19, 1, 'edge_45.jpg', 1, '\"Level 3, double doors leading to Charles Street.\"', 'Acccessible door button is located on the right.'),
(46, 18, 17, 1, 'edge_46.jpg', 4, '', 'Elevator access is on the left.'),
(47, 19, 18, 1, 'edge_47.jpg', 3, '', 'Acccessible door button is located on the left.'),
(48, 19, 20, 1, 'edge_48.jpg', 2, '', ''),
(49, 20, 19, 1, 'edge_49.jpg', 4, '', ''),
(50, 20, 21, 1, 'edge_50.jpg', 1, '', '');

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
(1, 'Eyre Street Car Park', 5, 1, 0),
(2, NULL, 3, 0, 0),
(3, NULL, 3, 0, 0),
(4, 'Arundel Main Entrance', 2, 1, 0),
(5, 'Ground Floor Stairs', 4, 0, 0),
(6, NULL, 1, 0, 0),
(7, NULL, 1, 0, 0),
(8, 'Arundel 10001', 5, 0, 0),
(9, NULL, 1, 0, 0),
(10, NULL, 1, 0, 0),
(11, 'Toilets', 5, 0, 0),
(12, 'Open IT Area', 5, 0, 0),
(13, NULL, 1, 0, 0),
(14, NULL, 6, 0, 0),
(15, 'Brown Lane Fire Escape', 5, 0, 0),
(16, 'First Floor Stairs', 4, 0, 1),
(17, 'Second Floor Stairs', 4, 0, 2),
(18, 'Third Floor Stairs', 4, 0, 3),
(19, NULL, 1, 0, 3),
(20, NULL, 3, 0, 3),
(21, 'Floor 3 Open IT Area', 5, 0, 3),
(22, 'Charles Street Connection', 5, 0, 3),
(23, 'Arundel Street Car Park', 5, 1, 0),
(24, NULL, 3, 0, 0);

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
  MODIFY `node_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
