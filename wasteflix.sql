-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2021 at 05:38 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wasteflix`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cid`, `name`, `price`) VALUES
(1, 'Household', 10),
(2, 'Industrial', 15),
(3, 'Plastics', 8),
(4, 'Glass', 10),
(5, 'Paper', 15),
(6, 'Wood', 20);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `description` varchar(20) NOT NULL,
  `category` int(11) NOT NULL,
  `city` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `qnty` int(11) NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`rid`, `uid`, `description`, `category`, `city`, `date`, `qnty`, `status`) VALUES
(1, 1, 'Thumpsup bottle', 4, 'Surat', '2020-07-20', 10, 'Completed.'),
(2, 1, 'News Paper', 5, 'Surat', '2020-07-20', 10, 'Rejected.'),
(3, 1, 'Coke', 4, 'Surat', '2020-07-20', 10, 'Rejected.'),
(4, 1, 'Kinly', 3, 'SK', '2020-07-20', 10, 'Completed.'),
(5, 1, 'Coca Cola', 4, 'Surat', '2020-07-20', 10, 'Completed.'),
(6, 1, 'Kinly club soda', 3, 'Surat', '2020-07-20', 15, 'Completed.'),
(7, 1, 'Book', 5, 'Surat', '2021-05-07', 10, 'Completed.'),
(8, 1, 'Coca cola', 3, 'Surat', '2021-05-07', 2, 'Rejected.');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `reid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `partner` varchar(20) NOT NULL,
  `vouc_code` varchar(20) NOT NULL,
  `offer` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`reid`, `rid`, `uid`, `name`, `partner`, `vouc_code`, `offer`) VALUES
(1, 1, 1, 'Cashback Offer', 'Amazon', 'GET100', 'Recharge Cashback'),
(2, 4, 1, 'Cashback Offer', 'Amazon', 'GET80', 'Recharge Cashback'),
(3, 5, 1, 'Cashback Offer', 'Amazon', 'GET100', 'Recharge Cashback'),
(4, 6, 1, 'Cashback Offer', 'Amazon', 'GET120', 'Recharge Cashback'),
(5, 7, 1, 'Cashback Offer', 'Amazon', 'GET150', 'Recharge Cashback');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `urole` int(11) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `name`, `contact`, `email`, `urole`, `password`) VALUES
(1, 'Dhruv', '9876543211', 'dhruv@gmail.com', 0, '123456'),
(2, 'Dip', '8974569852', 'dip@gmail.com', 1, '123456');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`rid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `request_ibfk_2` (`category`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`reid`),
  ADD KEY `rid_intigrity` (`rid`),
  ADD KEY `uid_intigrity` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `reid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`category`) REFERENCES `category` (`cid`);

--
-- Constraints for table `rewards`
--
ALTER TABLE `rewards`
  ADD CONSTRAINT `rid_intigrity` FOREIGN KEY (`rid`) REFERENCES `request` (`rid`),
  ADD CONSTRAINT `uid_intigrity` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
