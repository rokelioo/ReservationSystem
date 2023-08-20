-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2023 at 06:19 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reservation`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `pk_id` int(11) NOT NULL,
  `fk_specialist` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `surname` varchar(25) NOT NULL,
  `reservation_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`pk_id`, `fk_specialist`, `name`, `surname`, `reservation_code`) VALUES
(1, 1, 'Antanas', 'Alegardas', 8763),
(2, 1, 'Gediminas', 'Vaisvila', 3518),
(3, 1, 'asdasd', 'asdas', 303542),
(4, 1, 'kestas', 'jankus', 330742),
(5, 1, 'sksa', 'asdwq', 478701),
(6, 1, 'aaaaaaaaaaaa', 'ssssssssssss', 499641),
(7, 1, 'test1', 'test', 589561),
(8, 1, 'test2', 'test', 924530),
(9, 1, 'test3', 'dasda', 756203),
(10, 1, 'dassa', 'adsas', 483361),
(11, 1, 'dassa', 'adsas', 878273),
(12, 1, 'dassa', 'adsas', 839980),
(13, 1, 'dassa', 'adsas', 398830),
(14, 1, 'test3', 'dasda', 492027),
(15, 1, 'dassa', 'adsas', 929692),
(16, 1, 'asdsa', 'adssa', 102426),
(17, 1, 'Gedas', 'Pranskus', 910431),
(18, 1, 'asdas', 'asdas', 799255),
(19, 1, 'asdas', 'asdas', 141768),
(20, 1, 'asdas', 'asdas', 775242),
(21, 1, 'Gedas', 'Pranskus', 166855),
(22, 1, 'fewfwe', 'sdfdsf', 643615),
(23, 1, 'fewfwe', 'sdfdsf', 855757),
(24, 1, 'Gedas', 'Dauginskis', 928494),
(25, 1, 'Mangirdas', 'Usoknis', 73447),
(26, 1, 'Juozas', 'Juozapaitis', 977092),
(27, 1, 'Poulius', 'Poulikonis', 905941),
(28, 1, 'Vaidas', 'Vaideliauskas', 204109),
(29, 1, 'Test1', 'Test1', 248056),
(30, 1, 'Test2', 'Test2', 527852),
(31, 1, 'Test3', 'Test3', 746929),
(32, 1, 'Test4', 'Test4', 246486),
(33, 1, 'Test11', 'Test11', 486492),
(34, 1, 'Test12', 'Test12', 690338),
(35, 1, 'Test13', 'Test13', 603973),
(36, 1, 'Test14', 'Test14', 289597),
(37, 1, 'Test21', 'Test21', 315238),
(38, 1, 'Test21', 'Test21', 167088),
(39, 1, 'Test21', 'Test21', 36013),
(40, 1, 'Test21', 'Test21', 403191),
(41, 1, 'aa', 'aa', 858593),
(42, 1, 'aa', 'aa', 833262),
(43, 1, 'aa', 'aa', 356484),
(44, 1, 'asdsa', 'asdas', 908492),
(45, 1, 'asdas', 'asdas', 913432),
(46, 1, 'ff', 'ff', 846288),
(47, 1, 'dd', 'dd', 275991),
(48, 1, 'dqwwdq', 'dwqwdq', 506494);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `pk_id` int(11) NOT NULL,
  `fk_specialist` int(11) NOT NULL,
  `fk_customer` int(11) NOT NULL,
  `startTime` datetime NOT NULL,
  `endTime` datetime DEFAULT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`pk_id`, `fk_specialist`, `fk_customer`, `startTime`, `endTime`, `status`) VALUES
(4, 1, 1, '2023-08-15 22:11:25', '2023-08-30 22:11:25', 'pending'),
(5, 1, 5, '2023-08-16 17:44:34', '2023-08-16 17:59:34', 'pending'),
(6, 1, 6, '2023-08-16 17:44:47', '2023-08-16 17:59:47', 'pending'),
(7, 1, 7, '2023-08-16 18:04:50', '2023-08-16 18:19:50', 'pending'),
(8, 1, 8, '2023-08-16 18:05:21', '2023-08-16 18:20:21', 'pending'),
(9, 1, 9, '2023-08-16 18:06:08', '2023-08-16 18:21:08', 'pending'),
(10, 1, 10, '2023-08-16 18:08:15', '2023-08-16 18:23:15', 'pending'),
(11, 1, 11, '2023-08-16 18:13:03', '2023-08-16 18:28:03', 'pending'),
(12, 1, 12, '2023-08-16 18:13:14', '2023-08-16 18:28:14', 'pending'),
(13, 1, 13, '2023-08-16 18:14:54', '2023-08-16 18:29:54', 'pending'),
(22, 1, 22, '2023-08-18 16:50:49', '2023-08-18 17:05:49', 'Cancel'),
(23, 1, 23, '2023-08-18 17:05:49', '2023-08-18 17:20:49', 'End'),
(24, 1, 24, '2023-08-18 20:10:49', '2023-08-18 20:25:49', 'End'),
(25, 1, 25, '2023-08-18 21:44:59', '2023-08-18 21:59:59', 'pending'),
(26, 1, 26, '2023-08-18 21:59:59', '2023-08-18 22:14:59', 'pending'),
(27, 1, 27, '2023-08-18 22:14:59', '2023-08-18 22:29:59', 'pending'),
(28, 1, 28, '2023-08-18 22:29:59', '2023-08-18 22:44:59', 'End'),
(29, 1, 29, '2023-08-18 22:44:59', '2023-08-18 22:59:59', 'pending'),
(30, 1, 30, '2023-08-18 22:59:59', '2023-08-18 23:14:59', 'pending'),
(31, 1, 31, '2023-08-18 23:14:59', '2023-08-18 23:29:59', 'Pending'),
(32, 1, 32, '2023-08-18 23:29:59', '2023-08-18 23:44:59', 'Priority'),
(33, 1, 33, '2023-08-19 11:55:34', '2023-08-19 12:10:34', 'Cancel'),
(34, 1, 34, '2023-08-19 12:10:34', '2023-08-19 12:25:34', 'Pending'),
(35, 1, 35, '2023-08-19 12:25:34', '2023-08-19 12:40:34', 'Priority'),
(36, 1, 36, '2023-08-19 12:40:34', '2023-08-19 12:55:34', 'Pending'),
(37, 1, 43, '2023-08-19 22:37:56', '2023-08-19 22:52:56', 'pending'),
(38, 1, 44, '2023-08-19 22:54:53', '2023-08-19 23:09:53', 'canceled'),
(39, 1, 45, '2023-08-19 23:09:53', '2023-08-19 23:24:53', 'canceled'),
(40, 1, 46, '2023-08-20 14:28:56', '2023-08-20 14:43:56', 'Pending'),
(41, 1, 47, '2023-08-20 14:43:56', '2023-08-20 14:58:56', 'Begin'),
(42, 1, 48, '2023-08-20 14:58:56', '2023-08-20 15:13:56', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `specialist`
--

CREATE TABLE `specialist` (
  `pk_id` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `password` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `specialist`
--

INSERT INTO `specialist` (`pk_id`, `name`, `surname`, `password`) VALUES
(1, 'Rokas', 'Lideikis', '123'),
(2, 'Lukas', 'Kisevicius', '321');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`pk_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`pk_id`);

--
-- Indexes for table `specialist`
--
ALTER TABLE `specialist`
  ADD PRIMARY KEY (`pk_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `pk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `pk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `specialist`
--
ALTER TABLE `specialist`
  MODIFY `pk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
