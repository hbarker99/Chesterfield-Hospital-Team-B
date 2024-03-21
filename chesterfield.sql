-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2024 at 01:06 PM
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
(16, 3, 11),
(18, 22, 0),
(19, 0, 11),
(20, 0, 3),
(21, 0, 22),
(22, 0, 21),
(23, 11, 21);

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
(25, 19, 1, 0),
(26, 19, 3, 1),
(27, 19, 5, 2),
(28, 19, 9, 3),
(29, 19, 16, 4),
(30, 19, 24, 5),
(31, 20, 1, 0),
(32, 20, 3, 1),
(33, 20, 5, 2),
(34, 21, 1, 0),
(35, 21, 3, 1),
(36, 21, 6, 2),
(37, 21, 55, 3),
(38, 22, 1, 0),
(39, 22, 3, 1),
(40, 22, 5, 2),
(41, 22, 8, 3),
(42, 22, 12, 4),
(43, 22, 42, 5),
(44, 22, 44, 6),
(45, 22, 45, 7),
(46, 22, 48, 8),
(47, 22, 51, 9),
(48, 23, 31, 0),
(49, 23, 21, 1),
(50, 23, 14, 2),
(51, 23, 12, 3),
(52, 23, 42, 4),
(53, 23, 44, 5),
(54, 23, 45, 6),
(55, 23, 48, 7),
(56, 23, 51, 8);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `edges`
--
ALTER TABLE `edges`
  MODIFY `edge_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `node`
--
ALTER TABLE `node`
  MODIFY `node_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
