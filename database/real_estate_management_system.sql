-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2024 at 10:00 PM
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
-- Database: `real_estate_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `clienttable`
--

CREATE TABLE `tenantstable` (
  `TenantID` int(11) NOT NULL,
  `PropertyID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `StreetAddress` varchar(255) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `StateProvince` varchar(50) DEFAULT NULL,
  `ZIPPostalCode` varchar(20) DEFAULT NULL,
  `LeaseStart` date NOT NULL,
  `LeaseEnd` date NOT NULL,
  `RentAmount` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenancerequesttable`
--

CREATE TABLE `maintenancerequesttable` (
  `RequestID` int(11) NOT NULL,
  `TenantID` int(11) NOT NULL,
  `PropertyID` int(11) NOT NULL,
  `RequestDate` date NOT NULL,
  `Description` text NOT NULL,
  `Status` enum('Pending','In Progress','Completed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messagestable`
--

CREATE TABLE `messagestable` (
  `MessageID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `MessageDate` date NOT NULL,
  `MessageContent` text NOT NULL,
  `MessageType` enum('Maintenance Alert','Tenant Message','Payment Reminder') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paymentstable`
--

CREATE TABLE `paymentstable` (
  `PaymentID` int(11) NOT NULL,
  `TenantID` int(11) NOT NULL,
  `PropertyID` int(11) NOT NULL,
  `PaymentDate` date NOT NULL,
  `AmountPaid` decimal(15,2) NOT NULL,
  `PaymentStatus` enum('Paid','Overdue','Pending') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `propertytable`
--

CREATE TABLE `propertytable` (
  `PropertyID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PropertyName` varchar(255) NOT NULL,
  `PropertyType` enum('Residential','Commercial') NOT NULL,
  `Location` varchar(255) NOT NULL,
  `NumberOfUnits` int(11) NOT NULL,
  `MonthlyRent` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenantstable`
--



--
-- Indexes for dumped tables
--

--
-- Indexes for table `clienttable`
--
ALTER TABLE `clienttable`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `maintenancerequesttable`
--
ALTER TABLE `maintenancerequesttable`
  ADD PRIMARY KEY (`RequestID`),
  ADD KEY `TenantID` (`TenantID`),
  ADD KEY `PropertyID` (`PropertyID`);

--
-- Indexes for table `messagestable`
--
ALTER TABLE `messagestable`
  ADD PRIMARY KEY (`MessageID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `paymentstable`
--
ALTER TABLE `paymentstable`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `TenantID` (`TenantID`),
  ADD KEY `PropertyID` (`PropertyID`);

--
-- Indexes for table `propertytable`
--
ALTER TABLE `propertytable`
  ADD PRIMARY KEY (`PropertyID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `tenantstable`
--
ALTER TABLE `tenantstable`
  ADD PRIMARY KEY (`TenantID`),
  ADD KEY `PropertyID` (`PropertyID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clienttable`
--
ALTER TABLE `clienttable`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenancerequesttable`
--
ALTER TABLE `maintenancerequesttable`
  MODIFY `RequestID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messagestable`
--
ALTER TABLE `messagestable`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paymentstable`
--
ALTER TABLE `paymentstable`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `propertytable`
--
ALTER TABLE `propertytable`
  MODIFY `PropertyID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenantstable`
--
ALTER TABLE `tenantstable`
  MODIFY `TenantID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `maintenancerequesttable`
--
ALTER TABLE `maintenancerequesttable`
  ADD CONSTRAINT `maintenancerequesttable_ibfk_1` FOREIGN KEY (`TenantID`) REFERENCES `tenantstable` (`TenantID`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenancerequesttable_ibfk_2` FOREIGN KEY (`PropertyID`) REFERENCES `propertytable` (`PropertyID`) ON DELETE CASCADE;

--
-- Constraints for table `messagestable`
--
ALTER TABLE `messagestable`
  ADD CONSTRAINT `messagestable_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `clienttable` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `paymentstable`
--
ALTER TABLE `paymentstable`
  ADD CONSTRAINT `paymentstable_ibfk_1` FOREIGN KEY (`TenantID`) REFERENCES `tenantstable` (`TenantID`) ON DELETE CASCADE,
  ADD CONSTRAINT `paymentstable_ibfk_2` FOREIGN KEY (`PropertyID`) REFERENCES `propertytable` (`PropertyID`) ON DELETE CASCADE;

--
-- Constraints for table `propertytable`
--
ALTER TABLE `propertytable`
  ADD CONSTRAINT `propertytable_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `clienttable` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `tenantstable`
--
ALTER TABLE `tenantstable`
  ADD CONSTRAINT `tenantstable_ibfk_1` FOREIGN KEY (`PropertyID`) REFERENCES `propertytable` (`PropertyID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
