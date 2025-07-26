-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 23, 2025 at 02:16 PM
-- Server version: 8.0.31
-- PHP Version: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostel_allocation`
--

-- --------------------------------------------------------

--
-- Table structure for table `allocations`
--

DROP TABLE IF EXISTS `allocations`;
CREATE TABLE IF NOT EXISTS `allocations` (
  `allocation_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `room_id` int NOT NULL,
  `allocate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` date DEFAULT NULL,
  `status` enum('active','completed') DEFAULT 'active',
  PRIMARY KEY (`allocation_id`),
  KEY `student_id` (`student_id`),
  KEY `room_id` (`room_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `allocations`
--

INSERT INTO `allocations` (`allocation_id`, `student_id`, `room_id`, `allocate_date`, `end_date`, `status`) VALUES
(1, 2, 1, '2025-07-22 13:58:11', NULL, 'active'),
(2, 3, 2, '2025-07-23 11:14:58', NULL, 'active'),
(3, 4, 6, '2025-07-23 12:30:12', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
CREATE TABLE IF NOT EXISTS `applications` (
  `application_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `apply_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  PRIMARY KEY (`application_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`application_id`, `student_id`, `apply_date`, `status`) VALUES
(1, 1, '2025-07-22 08:43:30', 'rejected'),
(2, 2, '2025-07-22 11:19:23', 'approved'),
(3, 3, '2025-07-23 07:57:12', 'approved'),
(4, 4, '2025-07-23 12:28:23', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

DROP TABLE IF EXISTS `buildings`;
CREATE TABLE IF NOT EXISTS `buildings` (
  `building_id` int NOT NULL AUTO_INCREMENT,
  `building_name` varchar(100) NOT NULL,
  PRIMARY KEY (`building_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`building_id`, `building_name`) VALUES
(1, 'Tipu Block'),
(2, 'Qasim Block'),
(3, 'Jinnah Block'),
(4, 'Iqbal Block');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
CREATE TABLE IF NOT EXISTS `complaints` (
  `complaint_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `room_id` int NOT NULL,
  `complaint_text` text NOT NULL,
  `complaint_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','in_progress','resolved') DEFAULT 'pending',
  `resolution_text` text,
  `resolved_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`complaint_id`),
  KEY `student_id` (`student_id`),
  KEY `room_id` (`room_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `student_id`, `room_id`, `complaint_text`, `complaint_date`, `status`, `resolution_text`, `resolved_date`) VALUES
(1, 3, 2, 'Cleaness', '2025-07-23 11:17:09', 'resolved', 'dssdfsdfsdfsdfsdfsdfsdfsd', '2025-07-23 11:17:38'),
(2, 4, 6, 'Water', '2025-07-23 12:34:45', 'resolved', 'WAPDA Bowser deliverd', '2025-07-23 12:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

DROP TABLE IF EXISTS `floors`;
CREATE TABLE IF NOT EXISTS `floors` (
  `floor_id` int NOT NULL AUTO_INCREMENT,
  `building_id` int NOT NULL,
  `floor_number` int NOT NULL,
  PRIMARY KEY (`floor_id`),
  KEY `building_id` (`building_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `floors`
--

INSERT INTO `floors` (`floor_id`, `building_id`, `floor_number`) VALUES
(1, 1, 3),
(2, 3, 1),
(3, 3, 2),
(4, 3, 3),
(5, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id` int NOT NULL AUTO_INCREMENT,
  `floor_id` int NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `capacity` int DEFAULT '1',
  `status` enum('available','occupied') DEFAULT 'available',
  `occupied` int DEFAULT '0',
  PRIMARY KEY (`room_id`),
  KEY `floor_id` (`floor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `floor_id`, `room_number`, `capacity`, `status`, `occupied`) VALUES
(1, 1, '301', 1, 'occupied', 1),
(2, 2, '101', 2, 'available', 1),
(3, 3, '201', 2, 'available', 0),
(4, 4, '301', 1, 'available', 0),
(5, 4, '302', 3, 'available', 0),
(6, 5, '201', 2, 'available', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','student') NOT NULL DEFAULT 'student',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$T10DGQuNiHwZJhwWLfTp6Ohft6JLwUj27Qcn069LTTLnK6mZIXYOW', 'admin@admin.com', 'Admin', 'admin', '2025-07-22 08:43:06'),
(2, 'student', '$2y$10$Le45mcS/kv5jzsemwbA0luGbc4pKW/I5i4Vrh3dpK4aDzpw/e4MQu', 'student@hms.com', 'student', 'student', '2025-07-22 11:19:09'),
(3, 'student2', '$2y$10$pNcsHZr0rj95aHckbLCd5eYAQpYOE0Vt7/bMXEYBgAALwl.nfYnaO', 'student2@hms.com', 'student2', 'student', '2025-07-23 07:55:54'),
(4, 'Student3', '$2y$10$eQdhNtxzYHMGoaHsvudLx.EVRe6WoCQyBXXNe01sigPrD0M/Ix56G', 'student3@hms.com', 'Student3', 'student', '2025-07-23 12:22:49');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
