-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 18, 2014 at 05:52 PM
-- Server version: 5.5.32-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.8

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
-- Table structure for table `clientContacts`
--

CREATE TABLE IF NOT EXISTS `clientContacts` (
  `clientContactID` int(11) NOT NULL AUTO_INCREMENT,
  `clientID` int(11) NOT NULL,
  `contactID` int(11) NOT NULL,
  PRIMARY KEY (`clientContactID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `clientID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `clientName` varchar(255) NOT NULL,
  `clientAddress1` varchar(255) NOT NULL,
  `clientAddress2` varchar(255) NOT NULL,
  `clientCity` varchar(255) NOT NULL,
  `clientState` varchar(2) NOT NULL,
  `clientZip` int(5) DEFAULT NULL,
  `clientWebsite` varchar(255) NOT NULL,
  `clientCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`clientID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`clientID`, `userID`, `clientName`, `clientAddress1`, `clientAddress2`, `clientCity`, `clientState`, `clientZip`, `clientWebsite`, `clientCreated`) VALUES
(8, 1, 'Naja''s Place', '154 International Boardwalk', '', 'Redondo Beach', 'CA', 90277, 'http://www.najasplace.com/', '2014-07-10 20:32:13'),
(9, 1, 'Sophie''s Place', '1708 S Catalina Ave', '', 'Redondo Beach', 'CA', 90277, 'https://www.facebook.com/sophiesplacerb', '2014-07-18 18:03:02'),
(12, 1, 'Blue Dog Beer Tavern ', '4524 Saugus Ave', '', 'Sherman Oaks', 'CA', 91403, 'www.bluedogbeertavern.com', '2014-07-18 18:53:18'),
(13, 1, 'Hot''s Kitchen', '844 Hermosa Ave', '', 'Hermosa Beach', 'CA', 90254, 'http://www.hotskitchen.com/', '2014-07-18 21:22:36'),
(14, 1, 'MB Post', '1142 Manhattan Ave', '', 'Manhattan Beach', 'CA', 90266, 'http://eatmbpost.com/', '2014-07-18 21:23:36'),
(15, 1, 'The Strand House', '117 Manhattan Beach Blvd', '', 'Manhattan Beach', 'CA', 90266, 'http://www.thestrandhousemb.com/', '2014-07-18 21:26:53'),
(16, 1, 'Simmzy''s (Manhattan Beach)', '229 Manhattan Beach Blvd', '', 'Manhattan Beach', 'CA', 90266, 'http://www.simmzys.com/', '2014-07-18 21:28:07'),
(17, 1, 'Manhattan Beach Brew Co.', '124 Manhattan Beach Blvd', '', 'Manhattan Beach', 'CA', 90266, 'http://www.brewcomb.com/', '2014-07-18 21:29:13'),
(18, 1, 'Rock N'' Brews (El Segundo)', '143 Main St', '', 'El Segundo', 'CA', 90245, 'http://www.rockandbrews.com/elsegundo/', '2014-07-18 21:30:02'),
(19, 1, 'Richmond Bar and Grill', '145 Richmond St', '', 'El Segundo', 'CA', 90245, 'http://www.richmondbarandgrill.com/', '2014-07-18 21:30:47'),
(20, 1, 'Hudson House', '514 N Pacific Coast Hwy', '', 'Redondo Beach', 'CA', 90277, 'http://www.hudsonhousebar.com/', '2014-07-18 21:31:50'),
(21, 1, 'Father''s Office (Helms/Culver City)', '3229 Helms Ave', '', 'Los Angeles', 'CA', 90034, 'http://fathersoffice.com/', '2014-07-18 21:32:59'),
(22, 1, 'City Tavern (Culver City)', '9739 Culver Blvd', '', 'Culver City', 'CA', 90232, 'http://citytavernculvercity.com/', '2014-07-18 21:34:11'),
(23, 1, 'City Tavern (Downtown)', '735 S Figueroa St', '#133', 'Los Angeles', 'CA', 90017, 'http://www.citytaverndtla.com/', '2014-07-18 21:35:06'),
(24, 1, 'Rock N'' Brews (Redondo Beach)', '6300 Pacific Coast Highway', '', 'Redondo Beach', 'CA', 90277, 'http://www.rockandbrews.com/redondobeach/', '2014-07-18 21:36:42');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `contactID` int(11) NOT NULL AUTO_INCREMENT,
  `contactFirstName` varchar(100) NOT NULL,
  `contactLastName` varchar(100) NOT NULL,
  `contactEmail` varchar(100) NOT NULL,
  `contactPhone` varchar(100) NOT NULL,
  `clientID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`contactID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`contactID`, `contactFirstName`, `contactLastName`, `contactEmail`, `contactPhone`, `clientID`, `userID`) VALUES
(1, 'Morgan', 'Keller', 'morgan@indiebrewco.com', '3104805193', 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
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

INSERT INTO `messages` (`messageID`, `messageCreated`, `messageTitle`, `messageBody`, `userID`) VALUES
(1, '2014-06-25 18:53:35', 'Test message title', 'This is the content of the message', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) NOT NULL,
  `userFirstName` varchar(255) NOT NULL,
  `userLastName` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userApproved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userName`, `userFirstName`, `userLastName`, `userEmail`, `userPassword`, `userApproved`) VALUES
(1, 'morgan', 'Morgan', 'Keller', 'morgan@indiebrewco.com', 'm0rgan', 1),
(2, 'kevin', 'Kevin', 'O''Malley', 'komalley@indiebrewco.com', 'k3v1n', 1),
(3, 'connor', 'Connor', 'Forbes', 'connor@indiebrewco.com', 'c0nn0r', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
