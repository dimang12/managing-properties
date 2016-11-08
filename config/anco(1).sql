-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 22, 2014 at 05:50 PM
-- Server version: 5.5.37-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `anco`
--

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `img_file` varchar(150) NOT NULL,
  `img_type` int(2) NOT NULL,
  `img_ordering` int(11) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`image_id`, `property_id`, `img_file`, `img_type`, `img_ordering`) VALUES
(1, 2, '1.jpg', 1, 1),
(2, 2, '2.jpg', 0, 2),
(3, 2, '3.jpg', 0, 3),
(4, 2, '4.jpg', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `map`
--

CREATE TABLE IF NOT EXISTS `map` (
  `map_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `map_lat` varchar(15) NOT NULL,
  `map_lng` varchar(15) NOT NULL,
  PRIMARY KEY (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `price`
--

CREATE TABLE IF NOT EXISTS `price` (
  `price_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `price_origional` float NOT NULL,
  `price_tax` float NOT NULL,
  `price_latest` float NOT NULL,
  `price_market` float NOT NULL,
  PRIMARY KEY (`price_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `price`
--

INSERT INTO `price` (`price_id`, `property_id`, `price_origional`, `price_tax`, `price_latest`, `price_market`) VALUES
(1, 2, 100000, 110000, 120000, 145000);

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE IF NOT EXISTS `property` (
  `property_id` int(11) NOT NULL AUTO_INCREMENT,
  `province_id` int(3) NOT NULL,
  `prop_name` varchar(200) NOT NULL,
  `prop_location` varchar(100) NOT NULL,
  `prop_address` varchar(255) NOT NULL,
  `prop_dimension` varchar(80) NOT NULL,
  `prop_size` float NOT NULL,
  `prop_road_type` varchar(40) NOT NULL,
  `prop_north` varchar(255) NOT NULL,
  `prop_south` varchar(255) NOT NULL,
  `prop_eath` varchar(255) NOT NULL,
  `prop_west` varchar(255) NOT NULL,
  `prop_description` text NOT NULL,
  `prop_rang` int(5) NOT NULL,
  PRIMARY KEY (`property_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`property_id`, `province_id`, `prop_name`, `prop_location`, `prop_address`, `prop_dimension`, `prop_size`, `prop_road_type`, `prop_north`, `prop_south`, `prop_eath`, `prop_west`, `prop_description`, `prop_rang`) VALUES
(2, 1, 'Land in Kampong Spue', 'in Town', 'in Kampong Spue', '25m x 200m (5000)', 5000, 'Main road', 'Mr. A''s land', 'Mr. B''s land', 'Mr. C''s land', 'Mr. D''s land', 'Located only 1 Km from Black Mountain Golf Course, on a Tarmac road, with electric and water, is this prime piece of development land, (30 Rai) With no land fill required with 3 access roads and outstanding mountain views. The land is now being used to grow Pineapples. Price: 2.3m per Rai. Total cost 69m. 50/50 on transfer costs. The price of land in this up and coming area averages 3.5m per Rai, making this plot exceptional value for money.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE IF NOT EXISTS `province` (
  `province_id` int(2) NOT NULL AUTO_INCREMENT,
  `prov_name` varchar(30) NOT NULL,
  `prov_ordering` int(2) NOT NULL,
  PRIMARY KEY (`province_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_pass` varchar(64) CHARACTER SET latin1 NOT NULL,
  `user_type` int(2) DEFAULT NULL,
  `user_created_date` datetime NOT NULL,
  `user_last_login` datetime NOT NULL,
  `user_first_name` varchar(30) CHARACTER SET latin1 NOT NULL,
  `user_last_name` varchar(30) CHARACTER SET latin1 NOT NULL,
  `user_ordering` int(5) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `role_id`, `user_name`, `user_pass`, `user_type`, `user_created_date`, `user_last_login`, `user_first_name`, `user_last_name`, `user_ordering`) VALUES
(1, 1, 'Channath', '123', 1, '2014-03-10 00:00:00', '2014-03-12 00:00:00', 'Lis', 'Channath', 1),
(2, 2, 'Emo', '12345', 2, '2014-03-12 00:00:00', '2014-03-12 00:00:00', 'E', 'Mo', 2),
(3, 1, 'dimang', 'dimang', 1, '2014-04-08 00:00:00', '0000-00-00 00:00:00', 'CHOU', 'Dimang', 3),
(4, 1, 'Sev Sean', '12345', 2, '2014-07-01 00:00:00', '2014-07-03 00:00:00', 'Sean', 'Sev', 0),
(5, 1, 'Admin', 'admin', 1, '2014-07-04 00:00:00', '2014-07-04 00:00:00', 'Admin', '', 0),
(6, 1, 'Sale', '123', 3, '2014-08-01 09:28:31', '2014-08-01 09:28:36', 'Sale', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
