-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2018 at 09:43 AM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fellow_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `Id` int(11) NOT NULL,
  `Name` varchar(70) NOT NULL,
  `Email` varchar(70) NOT NULL,
  `Comment` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`Id`, `Name`, `Email`, `Comment`) VALUES
(11, 'Shingirai Sibakwe', 'sibakwes@hotmail.co.uk', 'fugyjlij;'),
(12, 'Shingirai Sibakwe', 'sibakwes@hotmail.co.uk', 'jmkl;,ldkc'),
(13, 'Shingirai Sibakwe', 'sibakwes@hotmail.co.uk', 'hjklfldfkfk');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `Id` int(25) NOT NULL,
  `Icon` varchar(25) NOT NULL,
  `Heading` varchar(25) NOT NULL,
  `paragraph` varchar(300) NOT NULL,
  `Element_name` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`Id`, `Icon`, `Heading`, `paragraph`, `Element_name`) VALUES
(1, 'perm_data_setting', 'system integration', 'We change customer''s work and increase productivity so that we can provide a system that allows our developers to really spend time on. We create Commercial software that realizes improvements in business efficiency.', 'system'),
(2, 'devices', 'Web solution', 'We create websites that will make our customers to think about what we are making a website for, what we create we think with you to make it a feeling webpage where feelings are transmitted, that’s the role of fellow system.', 'web'),
(3, 'router', 'Networking', 'We aim to provide high quality computer networks tailored to meet your specific requirements. With emerging connectivity developments in both hardware and software', 'networking');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD UNIQUE KEY `Id` (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `Id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
