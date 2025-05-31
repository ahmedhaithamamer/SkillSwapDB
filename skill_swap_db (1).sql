-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2025 at 01:01 PM
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
-- Database: `skill_swap_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `main_request`
--

CREATE TABLE `main_request` (
  `main_request_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `main_request`
--

INSERT INTO `main_request` (`main_request_id`, `request_id`) VALUES
(3, 10);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `offer_money` char(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `user_id`, `description`, `offer_money`, `created_at`) VALUES
(3, 1, 'ergr rg erg ergerg ', '', '2025-05-20 15:29:07'),
(4, 1, 'fdfdfdfd df df d df dfdf d df', '', '2025-05-20 17:42:29'),
(5, 1, 'ewqfqwf wef qwf qw fwe fq few fwq fw f', '', '2025-05-20 18:18:52'),
(6, 1, 'wewfeefe. we fefwe fwe', '250', '2025-05-23 06:14:20'),
(7, 1, 'wfefwfwe fewf we fwef ew', NULL, '2025-05-29 12:43:26'),
(8, 1, 'test', '55', '2025-05-29 12:44:33'),
(10, 1, 'Exchange of skills', NULL, '2025-05-29 17:34:24');

-- --------------------------------------------------------

--
-- Table structure for table `requests_needed_skills`
--

CREATE TABLE `requests_needed_skills` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests_needed_skills`
--

INSERT INTO `requests_needed_skills` (`id`, `request_id`, `skill_id`) VALUES
(1, 3, 10),
(2, 4, 6),
(3, 4, 5),
(4, 6, 10),
(5, 6, 2),
(6, 7, 8),
(7, 8, 1),
(11, 10, 4),
(12, 10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `requests_offer_skills`
--

CREATE TABLE `requests_offer_skills` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests_offer_skills`
--

INSERT INTO `requests_offer_skills` (`id`, `request_id`, `skill_id`) VALUES
(3, 3, 4),
(4, 3, 2),
(5, 4, 9),
(6, 5, 9),
(7, 5, 3),
(8, 7, 10),
(10, 10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `responds`
--

CREATE TABLE `responds` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `response_type` varchar(10) NOT NULL,
  `note` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `responds`
--

INSERT INTO `responds` (`id`, `request_id`, `user_id`, `response_type`, `note`, `status`, `created_at`) VALUES
(1, 3, 1, 'skill', 'nice', 1, '2025-05-29 13:03:30'),
(2, 6, 1, 'money', 'wanna help', NULL, '2025-05-31 10:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `description`) VALUES
(1, 'C++ Programming', '-'),
(2, 'Python Development', '-'),
(3, 'Flutter Development', '-'),
(4, 'Web Development', '-'),
(5, 'Data Analysis', '-'),
(6, 'Machine Learning', '-'),
(7, 'Database Management', '-'),
(8, 'Cybersecurity', '-'),
(9, 'Mobile App Development', '-'),
(10, 'UI/UX Design', '-');

-- --------------------------------------------------------

--
-- Table structure for table `track_admins`
--

CREATE TABLE `track_admins` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `page_url` text DEFAULT NULL,
  `action` char(50) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  `photo` text DEFAULT NULL,
  `major` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `photo`, `major`, `year`, `bio`) VALUES
(1, 'Abdulrahman Elsisi', 'abdusisi1979@gmail.com', '$2y$10$kv.ytE8cPMp23P3pPVtDa.g2xTSgXbVR759iUoMGrWE5THmgP/xzm', 1022269757, NULL, 'Electronics and Computer Engineering', 2, 'I\'m The best of the best of the besties');

-- --------------------------------------------------------

--
-- Table structure for table `users_skills`
--

CREATE TABLE `users_skills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_skills`
--

INSERT INTO `users_skills` (`id`, `user_id`, `skill_id`) VALUES
(1, 1, 1),
(2, 1, 7),
(3, 1, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `main_request`
--
ALTER TABLE `main_request`
  ADD KEY `main_request_id` (`main_request_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `requests_needed_skills`
--
ALTER TABLE `requests_needed_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `requests_offer_skills`
--
ALTER TABLE `requests_offer_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `responds`
--
ALTER TABLE `responds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `track_admins`
--
ALTER TABLE `track_admins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_skills`
--
ALTER TABLE `users_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `requests_needed_skills`
--
ALTER TABLE `requests_needed_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `requests_offer_skills`
--
ALTER TABLE `requests_offer_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `responds`
--
ALTER TABLE `responds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `track_admins`
--
ALTER TABLE `track_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_skills`
--
ALTER TABLE `users_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `main_request`
--
ALTER TABLE `main_request`
  ADD CONSTRAINT `main_request_id` FOREIGN KEY (`main_request_id`) REFERENCES `requests` (`id`),
  ADD CONSTRAINT `request_id` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `requests_needed_skills`
--
ALTER TABLE `requests_needed_skills`
  ADD CONSTRAINT `requests_needed_skills_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`),
  ADD CONSTRAINT `requests_needed_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`);

--
-- Constraints for table `requests_offer_skills`
--
ALTER TABLE `requests_offer_skills`
  ADD CONSTRAINT `requests_offer_skills_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`),
  ADD CONSTRAINT `requests_offer_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`);

--
-- Constraints for table `responds`
--
ALTER TABLE `responds`
  ADD CONSTRAINT `responds_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`),
  ADD CONSTRAINT `responds_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `track_admins`
--
ALTER TABLE `track_admins`
  ADD CONSTRAINT `track_admins_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);

--
-- Constraints for table `users_skills`
--
ALTER TABLE `users_skills`
  ADD CONSTRAINT `users_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
