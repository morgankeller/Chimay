-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 25, 2014 at 02:44 PM
-- Server version: 5.5.33
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chimay`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `clientID` int(11) NOT NULL AUTO_INCREMENT,
  `clientName` varchar(255) NOT NULL,
  `clientShortname` varchar(255) NOT NULL,
  `clientAddress1` varchar(255) NOT NULL,
  `clientAddress2` varchar(255) NOT NULL,
  `clientCity` varchar(255) NOT NULL,
  `clientState` varchar(2) NOT NULL,
  `clientZip` int(5) DEFAULT NULL,
  `clientContact` varchar(255) NOT NULL,
  `clientEmail` varchar(255) NOT NULL,
  `clientCreated` datetime NOT NULL,
  PRIMARY KEY (`clientID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`clientID`, `clientName`, `clientShortname`, `clientAddress1`, `clientAddress2`, `clientCity`, `clientState`, `clientZip`, `clientContact`, `clientEmail`, `clientCreated`) VALUES
(1, 'Grasswalkers, LLC', 'Grasswalkers', '2223 Avenida de la Playa', 'Suite 110', 'La Jolla', 'CA', 92037, 'Shari Farah', 'sfarah@shwx2.com', '2013-08-20 19:52:07'),
(4, 'Sony Computer Entertainment', 'SCE', '1630 Stewart St', '', 'Santa Monica', 'CA', 90404, 'Aaron Kaufman', 'aaron_kaufman@playstation.sony.com', '2014-03-27 00:00:00'),
(5, 'Annenberg School for Communication', 'Annenberg', 'Watt Way', '', 'Los Angeles', 'CA', 90007, 'Rachel Hartz', 'rachellhartz@gmail.com', '2014-04-24 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contactID` int(11) NOT NULL AUTO_INCREMENT,
  `contactFirstName` varchar(100) NOT NULL,
  `contactLastName` varchar(100) NOT NULL,
  `contactEmail` varchar(100) NOT NULL,
  PRIMARY KEY (`contactID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) NOT NULL,
  `userFirstName` varchar(255) NOT NULL,
  `userLastName` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userApproved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userName`, `userFirstName`, `userLastName`, `userEmail`, `userPassword`, `userApproved`) VALUES
(1, 'morgan', 'Morgan', 'Keller', 'morgan@indiebrewco.com', 'morgan', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
