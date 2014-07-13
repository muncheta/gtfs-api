-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Jul 13, 2014 at 02:33 PM
-- Server version: 5.5.34
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `buzzstop_gtfs`
--

-- --------------------------------------------------------

--
-- Table structure for table `AGENCY`
--

CREATE TABLE `AGENCY` (
  `agency_id` varchar(5) NOT NULL DEFAULT '',
  `agency_name` varchar(14) DEFAULT NULL,
  `agency_url` varchar(28) DEFAULT NULL,
  `agency_timezone` varchar(18) DEFAULT NULL,
  `agency_lang` varchar(20) DEFAULT NULL,
  `agency_phone` varchar(12) DEFAULT NULL,
  `agency_fare_url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`agency_id`),
  KEY `agency_id` (`agency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `boardings`
--

CREATE TABLE `boardings` (
  `boarding_id` int(10) NOT NULL AUTO_INCREMENT,
  `trip_id` int(10) DEFAULT NULL,
  `route_id` varchar(50) DEFAULT NULL,
  `stop_id` int(10) DEFAULT NULL,
  `stop_name` varchar(255) DEFAULT NULL,
  `week_beginning` date DEFAULT NULL,
  `boardings` int(5) DEFAULT NULL,
  PRIMARY KEY (`boarding_id`),
  KEY `trip_id` (`trip_id`,`stop_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=558563 ;

-- --------------------------------------------------------

--
-- Table structure for table `CALENDAR`
--

CREATE TABLE `CALENDAR` (
  `service_id` int(2) NOT NULL DEFAULT '0',
  `monday` int(1) DEFAULT NULL,
  `tuesday` int(1) DEFAULT NULL,
  `wednesday` int(1) DEFAULT NULL,
  `thursday` int(1) DEFAULT NULL,
  `friday` int(1) DEFAULT NULL,
  `saturday` int(1) DEFAULT NULL,
  `sunday` int(1) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `legend_id` int(5) NOT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `CALENDAR_DATES`
--

CREATE TABLE `CALENDAR_DATES` (
  `service_id` int(3) DEFAULT NULL,
  `date` int(8) DEFAULT NULL,
  `exception_type` int(1) DEFAULT NULL,
  KEY `service_id` (`service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `PUBLIC_HOLIDAYS`
--

CREATE TABLE `PUBLIC_HOLIDAYS` (
  `ph_id` int(3) NOT NULL,
  `ph_date` int(8) NOT NULL,
  `ph_desc` varchar(50) NOT NULL,
  PRIMARY KEY (`ph_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ROUTES`
--

CREATE TABLE `ROUTES` (
  `route_id` varchar(60) NOT NULL DEFAULT '',
  `agency_id` varchar(5) DEFAULT NULL,
  `route_short_name` varchar(255) DEFAULT NULL,
  `route_long_name` varchar(255) DEFAULT NULL,
  `route_desc` varchar(250) DEFAULT NULL,
  `route_type` int(1) DEFAULT NULL,
  `route_url` varchar(105) DEFAULT NULL,
  `route_color` varchar(6) DEFAULT NULL,
  `route_text_color` varchar(6) DEFAULT NULL,
  `RouteGroup` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`route_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ROUTE_DIRECTION_HEADER`
--

CREATE TABLE `ROUTE_DIRECTION_HEADER` (
  `route_id` varchar(60) DEFAULT NULL,
  `direction_id` int(1) DEFAULT NULL,
  `day_type` int(1) DEFAULT NULL,
  `timetable_header` varchar(116) DEFAULT NULL,
  `direction` varchar(81) DEFAULT NULL,
  KEY `route_id` (`route_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SCHOOLS`
--

CREATE TABLE `SCHOOLS` (
  `school_id` int(5) DEFAULT NULL,
  `School_name` varchar(200) DEFAULT NULL,
  `school_keywords` text,
  KEY `school_id` (`school_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SCHOOL_TRIPS`
--

CREATE TABLE `SCHOOL_TRIPS` (
  `trip_id` int(6) DEFAULT NULL,
  `school_id` int(5) DEFAULT NULL,
  KEY `trip_id` (`trip_id`),
  KEY `school_id` (`school_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SHAPES`
--

CREATE TABLE `SHAPES` (
  `shape_id` int(11) NOT NULL,
  `shape_pt_lat` text NOT NULL,
  `shape_pt_lon` text NOT NULL,
  `shape_pt_sequence` int(11) NOT NULL,
  `shape_dist_traveled` int(11) NOT NULL,
  KEY `shape_id` (`shape_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `STOPS`
--

CREATE TABLE `STOPS` (
  `stop_id` int(6) NOT NULL DEFAULT '0',
  `stop_code` varchar(10) DEFAULT NULL,
  `stop_name` varchar(125) DEFAULT NULL,
  `stop_desc` varchar(250) DEFAULT NULL,
  `stop_lat` decimal(29,12) DEFAULT NULL,
  `stop_lon` decimal(28,11) DEFAULT NULL,
  `zone_id` varchar(10) DEFAULT NULL,
  `stop_url` varchar(255) DEFAULT NULL,
  `location_type` varchar(10) DEFAULT NULL,
  `parent_station` varchar(10) DEFAULT NULL,
  `wheelchair_boarding` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`stop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `STOP_TIMES`
--

CREATE TABLE `STOP_TIMES` (
  `trip_id` int(11) NOT NULL,
  `arrival_time` time NOT NULL,
  `departure_time` time NOT NULL,
  `stop_id` int(11) NOT NULL,
  `stop_sequence` int(11) NOT NULL,
  `stop_headsign` varchar(255) NOT NULL,
  `pickup_type` int(11) NOT NULL,
  `drop_off_type` int(11) NOT NULL,
  `shape_dist_traveled` float NOT NULL,
  `timepoint` int(11) NOT NULL,
  PRIMARY KEY (`trip_id`,`stop_id`,`stop_sequence`),
  KEY `trip_id` (`trip_id`),
  KEY `stop_id` (`stop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TRANSFERS`
--

CREATE TABLE `TRANSFERS` (
  `from_stop_id` int(6) DEFAULT NULL,
  `to_stop_id` int(6) DEFAULT NULL,
  `transfer_type` int(1) DEFAULT NULL,
  `min_transfer_time` varchar(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `TRIPS`
--

CREATE TABLE `TRIPS` (
  `route_id` varchar(60) DEFAULT NULL,
  `service_id` int(3) DEFAULT NULL,
  `trip_id` int(5) NOT NULL DEFAULT '0',
  `trip_headsign` varchar(52) DEFAULT NULL,
  `direction_id` int(1) DEFAULT NULL,
  `block_id` varchar(6) DEFAULT NULL,
  `shape_id` varchar(4) DEFAULT NULL,
  `wheelchair_accessible` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`trip_id`),
  KEY `route_id` (`route_id`),
  KEY `service_id` (`service_id`),
  KEY `direction_id` (`direction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
