-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2025 at 07:53 AM
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
-- Database: `clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE `adminlogin` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Admin') NOT NULL DEFAULT 'Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminlogin`
--

INSERT INTO `adminlogin` (`id`, `username`, `email`, `password`, `user_type`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$3tGgOBlhcMGlrgp0PqBVmOzRaOlHqxpvK0svycEFg5UV0vwdVaMNi', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `disease` text NOT NULL,
  `admission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `doctor_email` varchar(100) NOT NULL,
  `bed_number` varchar(100) NOT NULL,
  `treatment` text DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admissions`
--

INSERT INTO `admissions` (`id`, `patient_name`, `age`, `gender`, `contact`, `address`, `disease`, `admission_date`, `doctor_email`, `bed_number`, `treatment`, `patient_id`, `doctor_id`) VALUES
(55, 'avishkar', 12, 'Male', '9405424704', '', 'xyz', '2025-03-11 07:18:57', 'anil@gmail.com', '212', NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `appointment_date` date NOT NULL,
  `doctor` varchar(50) NOT NULL,
  `doctor_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `name`, `email`, `appointment_date`, `doctor`, `doctor_email`) VALUES
(4, 'ABHI', 'abhi@gmai.com', '2025-03-11', 'Dr.Gore', '');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discharges`
--

CREATE TABLE `discharges` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `admission_date` date NOT NULL,
  `discharge_date` date NOT NULL,
  `treatment` text NOT NULL,
  `total_bill` decimal(10,2) NOT NULL,
  `disease` varchar(255) NOT NULL,
  `doctor_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discharges`
--

INSERT INTO `discharges` (`id`, `patient_id`, `doctor_id`, `admission_date`, `discharge_date`, `treatment`, `total_bill`, `disease`, `doctor_email`) VALUES
(38, 1, 4, '2025-03-10', '2025-03-12', 'xax', 123.00, ' .', 'anil@gmail.com'),


-- --------------------------------------------------------

--
-- Table structure for table `doctorlogin`
--

CREATE TABLE `doctorlogin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `user_type` enum('Doctor','Patient') NOT NULL,
  `specialization` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctorlogin`
--

INSERT INTO `doctorlogin` (`id`, `username`, `email`, `password`, `reset_token`, `reset_expiry`, `user_type`, `specialization`) VALUES
(1, 'Dr.sham', 'sham@gmail.com', '$2y$10$BDch73e/XmSd7D58qwBwp.Qg8gM72FN1wcznvyInfvIstxNczvuN.', NULL, NULL, 'Doctor', 'M.B.B.S'),
(2, 'Dr.Wagh', 'wagh@gmail.com', '$2y$10$S/eL9uAFyC/pFng5oSg8g.TZMWPExBgTn7qaujcu/uCRmfpqcn2Ye', NULL, NULL, 'Doctor', 'BHMS');

-- --------------------------------------------------------

--
-- Table structure for table `patientlogin`
--

CREATE TABLE `patientlogin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Doctor','Patient') NOT NULL,
  `age` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patientlogin`
--

INSERT INTO `patientlogin` (`id`, `username`, `email`, `password`, `user_type`, `age`) VALUES
(1, 'aaaa', 'a@gmail.com', '$2y$10$kEFOwyOcbiOWcEecgeze2uK5L.OhKQazcimPFCegLVOpYZpnhyL.u', 'Patient', 0),
(2, 'rajjj', 'raj@gmail.com', '$2y$10$yN/EoWMozzZOrbMBckT66uBgvIqZ/URuDGTMBOICXsVXKlKw3.IdG', 'Patient', 0),
(3, 'mama', 'mama@gmail.com', '$2y$10$qN2V1JaIwJvu0OSpTGOTu.pHlGAch8X/2mnvRw.476Oe2l6b.QQzm', 'Patient', 0),
(4, 'sachin', 'sachin@gmail.com', '$2y$10$C.KLbdjrdsvWktyBE0YY6OBAtIJFwKQjNLtzOnZZLl7cOfmMtwtai', 'Patient', 0),
(5, 'ram123', 'ram@gmail.com', '$2y$10$Kp8NP8IiafnW.MPtNIE.z.19dnDfMPrYkWsjAtX6aj5yro6e7Bhyy', 'Patient', 12),
(6, 'sham', 'sham1@gmail.com', '$2y$10$WGZbfKb3RttMej2UJn8q4OJJCvbpeaHO0GBWLPnFN7yz5G7bjipEG', 'Patient', 0);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `treatments`
--

CREATE TABLE `treatments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `disease` varchar(255) NOT NULL,
  `treatment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `symptoms` varchar(255) NOT NULL,
  `bill` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatments`
--

INSERT INTO `treatments` (`id`, `patient_id`, `doctor_id`, `disease`, `treatment`, `created_at`, `symptoms`, `bill`) VALUES
(19, 1, 4, 'xyz', 'vvcn cbn', '2025-03-10 10:10:41', '', 0.00),
(20, 1, 4, 'xyz', 'xVcadv', '2025-03-10 10:12:46', '', 0.00),
(21, 6, 4, 'cb c', 'bh', '2025-03-12 10:14:16', '', 0.00),
(22, 8, 4, 'cb c', 'fdsf', '2025-03-15 06:05:50', '', 0.00),
(23, 9, 4, 'xyz', 'xyz', '2025-03-15 07:39:37', '', 0.00),
(24, 9, 11, 'bb', 'cc', '2025-03-15 13:32:01', 'aaa', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminlogin`
--
ALTER TABLE `adminlogin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admissions_ibfk_1` (`doctor_email`),
  ADD KEY `fk_doctor` (`doctor_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discharges`
--
ALTER TABLE `discharges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctorlogin`
--
ALTER TABLE `doctorlogin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_2` (`id`);

--
-- Indexes for table `patientlogin`
--
ALTER TABLE `patientlogin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminlogin`
--
ALTER TABLE `adminlogin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discharges`
--
ALTER TABLE `discharges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `doctorlogin`
--
ALTER TABLE `doctorlogin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `patientlogin`
--
ALTER TABLE `patientlogin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admissions`
--
ALTER TABLE `admissions`
  ADD CONSTRAINT `admissions_ibfk_1` FOREIGN KEY (`doctor_email`) REFERENCES `doctorlogin` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `discharges`
--
ALTER TABLE `discharges`
  ADD CONSTRAINT `discharges_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patientlogin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `discharges_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctorlogin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `treatments`
--
ALTER TABLE `treatments`
  ADD CONSTRAINT `treatments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patientlogin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctorlogin` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
