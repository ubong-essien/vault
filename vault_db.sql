-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2024 at 10:45 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vault_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_tb`
--

CREATE TABLE `access_tb` (
  `id` int(11) NOT NULL,
  `access_key` text NOT NULL,
  `regno` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `access_count` int(11) NOT NULL DEFAULT 0,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `access_tb`
--

INSERT INTO `access_tb` (`id`, `access_key`, `regno`, `password`, `access_count`, `active`) VALUES
(1, 'testcaassscode', 'AK22/ENG/MEC/001', '$2y$10$RCmdhasf9bC9PC1eczj.Y.fZ9CQ7I6BSEwtvCny65SHKbSL9aEcLC', 0, 1),
(2, 'testcaassscode1', 'AK22/ENG/MEC/002', '$2y$10$RCmdhasf9bC9PC1eczj.Y.fZ9CQ7I6BSEwtvCny65SHKbSL9aEcLC', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `voters_tb`
--

CREATE TABLE `voters_tb` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `regno` varchar(255) NOT NULL,
  `token` varchar(100) NOT NULL,
  `e_voting_token` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voters_tb`
--

INSERT INTO `voters_tb` (`id`, `name`, `regno`, `token`, `e_voting_token`) VALUES
(1, 'user voter 1', 'AK22/ENG/MEC/001', '', 'Y6BSN5E4'),
(2, 'user voter 2', 'AK22/ENG/MEC/002', '', '8HR571S6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_tb`
--
ALTER TABLE `access_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voters_tb`
--
ALTER TABLE `voters_tb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_tb`
--
ALTER TABLE `access_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `voters_tb`
--
ALTER TABLE `voters_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
