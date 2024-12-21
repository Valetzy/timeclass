-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2024 at 07:57 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timeclass`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `username`, `password`, `user_type`) VALUES
(1, 'admin', '$2y$10$DnO47LTaKEVqE3R/DVyTqeXd0EZKEjuS9dqweLd1W2GoKpwnTKTSi', 2);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `t_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`t_id`, `firstname`, `lastname`, `username`, `password`, `user_type`) VALUES
(1, 'reybi', 'tubil', 'abucayjustin9@gmail.com', '$2y$10$yy7weG4tf3t9dUL9WISsbu3umNrpzJCtZeXE4bZ4DQL.LAwXEPZk.', '1'),
(2, 'juluis', 'doroteo', 'maot@gmail.com', '$2y$10$ZjKkH6/L/oBwA8hZ1uGAluhh2rU0PkpWvBnBIaidhWwA3pgLtUyaC', '1'),
(3, 'ricky', 'saga', 'RickySaga', '$2y$10$B0MRYMys4ZRBctKJeEJKH.NITqiMrEBBaWcRytbpcMEbZjiFsOnYC', '1'),
(4, 'justin', 'abucs', 'teacher', '$2y$10$DnO47LTaKEVqE3R/DVyTqeXd0EZKEjuS9dqweLd1W2GoKpwnTKTSi', '1'),
(5, 'ace', 'motero', 'acemontero', '$2y$10$uu3nsW/snVwQobTZPqyvFO8p7k9u3LfjAsbd.Y2NQMktLm1cW50VK', '1'),
(6, 'reybi1', 'tubil', 'reybi@gmail.com', '$2y$10$g0uwBKw7CZbw8Gp7emh4eOmUhzskNWnpdi6T3BW0yeF7/nGD9YTyu', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`t_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
