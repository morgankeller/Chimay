-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 03, 2014 at 11:22 AM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


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
  `clientAddress1` varchar(255) NOT NULL,
  `clientAddress2` varchar(255) NOT NULL,
  `clientCity` varchar(255) NOT NULL,
  `clientState` varchar(2) NOT NULL,
  `clientZip` int(5) DEFAULT NULL,
  `contactID` int(11) NOT NULL,
  `clientCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`clientID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` VALUES(1, 'Grasswalkers, LLC', '2223 Avenida de la Playa', 'Suite 110', 'La Jolla', 'CA', 92037, 0, '2013-08-20 19:52:07');
INSERT INTO `clients` VALUES(4, 'Sony Computer Entertainment', '1630 Stewart St', '', 'Santa Monica', 'CA', 90404, 0, '2014-03-27 00:00:00');
INSERT INTO `clients` VALUES(5, 'Annenberg School for Communication', 'Watt Way', '', 'Los Angeles', 'CA', 90007, 0, '2014-04-24 00:00:00');
INSERT INTO `clients` VALUES(6, 'Client Name Test', '123 Test St.', '', 'Los Angeles', 'CA', 90250, 0, '2014-06-25 12:58:10');

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

--
-- Dumping data for table `contacts`
--


-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `messageID` int(11) NOT NULL AUTO_INCREMENT,
  `messageCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `messageTitle` mediumtext NOT NULL,
  `messageBody` text NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`messageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` VALUES(1, '2014-06-25 11:53:35', 'Test message title', 'This is the content of the message', 1);

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

INSERT INTO `users` VALUES(1, 'morgan', 'Morgan', 'Keller', 'morgan@indiebrewco.com', 'morgan', 1);
