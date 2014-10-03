-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 03, 2014 at 03:13 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `colorring`
--

-- --------------------------------------------------------

--
-- Table structure for table `LightShow`
--

CREATE TABLE IF NOT EXISTS `LightShow` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `lightShowName` varchar(255) NOT NULL,
  `singleCmdIdxStr` varchar(255) CHARACTER SET ascii NOT NULL,
  PRIMARY KEY (`idx`),
  UNIQUE KEY `idx` (`idx`),
  UNIQUE KEY `name` (`lightShowName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `LightShow`
--

INSERT INTO `LightShow` (`idx`, `lightShowName`, `singleCmdIdxStr`) VALUES
(12, 'Beautiful Light Show', '44,45,46,1,1,1,1,1,1,1,53,1,1,1,1,1,1,1,1,1'),
(16, 'White Strobe on100 off100', '48,50,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(19, 'All Blank', '1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(20, 'RedBlue Ying Yang', '56,55,59,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(21, 'RGB Flow Slow fill, then Fast', '62,63,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(22, 'RGB Flow Slow fill, then Fast Grad', '65,64,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(23, 'RGB Flow Slow fill, then Fast Grad(4Sec)', '67,66,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(24, 'Blue Water filling up', '72,1,1,1,1,1,1,1,1,1,73,74,71,1,1,1,1,1,1,1'),
(25, 'Rainbow Grad(2x) Slow Shift', '80,79,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(26, 'Rainbow Grad(1x) Slow Shift', '81,79,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(27, 'RGB Grad(1x) Slow Shift', '84,79,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(28, 'RGB Grad(2x) Slow Shift', '83,79,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(29, 'RGB Grad(4x) Slow Shift', '82,79,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(30, 'OUT - Rainbow Grad(1x) Slow Shift CW / IN - RGB Grad(1x) Slow Shift CCW', '81,79,1,1,1,1,1,1,1,1,84,85,1,1,1,1,1,1,1,1'),
(31, 'OUT - Rainbow Grad(1x) Slower Shift CW / IN - RGB Grad(1x) Slower Shift CCW', '81,87,1,1,1,1,1,1,1,1,84,86,1,1,1,1,1,1,1,1'),
(33, 'OUT&IN - White Strobe on10 off10', '89,88,1,1,1,1,1,1,1,1,89,88,1,1,1,1,1,1,1,1'),
(34, 'Spinning RG Radiation', '91,90,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1'),
(35, 'Brazil Flow', '92,1,1,1,1,1,1,1,1,1,96,1,1,1,1,1,1,1,1,1'),
(36, 'USA Flow', '94,1,1,1,1,1,1,1,1,1,95,1,1,1,1,1,1,1,1,1'),
(37, 'White Strobe on10 off10', '89,88,1,1,1,1,1,1,1,1,89,88,1,1,1,1,1,1,1,1'),
(38, 'OUT - Whole Strip Rainbow / IN - Whole Strip Rainbow slower', '97,1,1,1,1,1,1,1,1,1,98,1,1,1,1,1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
