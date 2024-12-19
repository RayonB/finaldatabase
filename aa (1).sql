-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2024 at 06:25 PM
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
-- Database: `aa`
--

-- --------------------------------------------------------

--
-- Table structure for table `request_types`
--

CREATE TABLE `request_types` (
  `id` int(11) NOT NULL,
  `request_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_types`
--

INSERT INTO `request_types` (`id`, `request_name`, `price`) VALUES
(1, 'Student ID Card', 20.00),
(2, 'Transcript of Records', 25.00),
(3, 'Certificate of Enrollment', 15.00),
(4, 'Official Receipt', 10.00),
(5, 'Diploma', 30.00),
(6, 'Clearance', 5.00),
(7, 'Certificate of Good Standing', 10.00),
(8, 'Letter of Recommendation', 20.00),
(9, 'Student Handbook', 12.00),
(10, 'Enrollment Confirmation', 10.00),
(11, 'Graduation Confirmation', 15.00),
(12, 'ID Replacement', 20.00),
(13, 'Transcript Authentication', 40.00),
(14, 'Certificate of Transfer', 35.00),
(15, 'Application Form', 5.00),
(16, 'Student Record Check', 10.00),
(17, 'Graduation Ceremonies Invitation', 8.00),
(18, 'Certificate of Accomplishment', 12.00),
(19, 'Internship Certificate', 25.00),
(20, 'Research Assistance Certificate', 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('student','instructor','staff') NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `course` varchar(100) NOT NULL,
  `cellphone_number` varchar(20) NOT NULL,
  `gender` enum('male','female','other') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `status`, `full_name`, `course`, `cellphone_number`, `gender`) VALUES
(1, 'joshua@gmail.com', '$2y$10$5LFrnU80NtmtBb59BCvlcO685cya8YLtxTvOhpjM1FVmCgLQeTOjG', 'student', 'joshua', 'BSIT', '09065804521', 'male'),
(2, 'mama@gmail.com', '$2y$10$SDiLqHwWZQFwySK/1kkO9eyEZajWj6iSZ1soyTd7OKlJrzJIpYmqu', 'instructor', 'ewrewrs', 'BSIT', '09065804521', 'female');

-- --------------------------------------------------------

--
-- Table structure for table `user_requests`
--

CREATE TABLE `user_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_price` decimal(10,2) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_requests`
--

INSERT INTO `user_requests` (`id`, `user_id`, `request_id`, `status`, `date_requested`, `total_price`, `description`) VALUES
(1, 2, 17, 'pending', '2024-12-19 16:06:38', 33.00, 'sadsadsa'),
(2, 2, 19, 'pending', '2024-12-19 16:06:38', 33.00, 'sadsadsa'),
(3, 2, 1, '', '2024-12-19 16:06:58', 65.00, 'asdsaf'),
(4, 2, 3, 'pending', '2024-12-19 16:06:58', 65.00, 'asdsaf'),
(5, 2, 5, 'pending', '2024-12-19 16:06:58', 65.00, 'asdsaf'),
(6, 2, 3, NULL, '2024-12-19 16:46:31', 15.00, 'safsaf'),
(7, 2, 2, '', '2024-12-19 16:49:09', 25.00, 'sdsad'),
(8, 2, 3, '', '2024-12-19 16:49:50', 45.00, 'dasdsa'),
(9, 2, 5, 'pending', '2024-12-19 16:49:50', 45.00, 'dasdsa'),
(10, 2, 3, '', '2024-12-19 16:53:30', 55.00, 'sadsad');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_types`
--
ALTER TABLE `request_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_requests`
--
ALTER TABLE `user_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `request_id` (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_types`
--
ALTER TABLE `request_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_requests`
--
ALTER TABLE `user_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_requests`
--
ALTER TABLE `user_requests`
  ADD CONSTRAINT `user_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_requests_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `request_types` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
