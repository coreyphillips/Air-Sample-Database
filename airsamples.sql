-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2014 at 03:13 AM
-- Server version: 6.0.11-alpha-community
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `airsamples`
--
CREATE DATABASE `airsamples` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `airsamples`;

-- --------------------------------------------------------

--
-- Table structure for table `client_info`
--

CREATE TABLE IF NOT EXISTS `client_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(100) DEFAULT NULL,
  `client` varchar(100) DEFAULT NULL,
  `client_sample_id` varchar(100) DEFAULT NULL,
  `client_project_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `project_id_2` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE IF NOT EXISTS `information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(100) DEFAULT NULL,
  `sample_id` varchar(100) DEFAULT NULL,
  `test_code` varchar(40) DEFAULT NULL,
  `instrument_id` varchar(100) DEFAULT NULL,
  `sample_type` varchar(50) DEFAULT NULL,
  `test_notes` varchar(200) DEFAULT NULL,
  `date_collected` date DEFAULT NULL,
  `date_received` date DEFAULT NULL,
  `date_analyzed` date DEFAULT NULL,
  `volume_analyzed` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mblank`
--

CREATE TABLE IF NOT EXISTS `mblank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(100) NOT NULL,
  `cas` varchar(50) DEFAULT NULL,
  `compound` varchar(50) DEFAULT NULL,
  `result_ugm3` decimal(10,4) DEFAULT NULL,
  `mrl_ugm3` decimal(10,4) DEFAULT NULL,
  `result_ppbv` decimal(10,4) DEFAULT NULL,
  `mrl_ppbv` decimal(10,4) DEFAULT NULL,
  `data_qualifier` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mblank_tic`
--

CREATE TABLE IF NOT EXISTS `mblank_tic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(100) NOT NULL,
  `retention` decimal(10,4) DEFAULT NULL,
  `compound` varchar(50) DEFAULT NULL,
  `concentration_ugm3` decimal(10,4) DEFAULT NULL,
  `data_qualifier` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mblanktwo`
--

CREATE TABLE IF NOT EXISTS `mblanktwo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(100) NOT NULL,
  `cas` varchar(50) DEFAULT NULL,
  `compound` varchar(50) DEFAULT NULL,
  `result_ugm3` decimal(10,4) DEFAULT NULL,
  `mrl_ugm3` decimal(10,4) DEFAULT NULL,
  `result_ppbv` decimal(10,4) DEFAULT NULL,
  `mrl_ppbv` decimal(10,4) DEFAULT NULL,
  `data_qualifier` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sample`
--

CREATE TABLE IF NOT EXISTS `sample` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(100) DEFAULT NULL,
  `cas` varchar(100) DEFAULT NULL,
  `compound` varchar(100) DEFAULT NULL,
  `result_ugm3` decimal(10,4) DEFAULT NULL,
  `mrl_ugm3` decimal(10,4) DEFAULT NULL,
  `result_ppbv` decimal(10,4) DEFAULT NULL,
  `mrl_ppbv` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sampletwo`
--

CREATE TABLE IF NOT EXISTS `sampletwo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(100) NOT NULL,
  `cas` varchar(100) DEFAULT NULL,
  `compound` varchar(100) DEFAULT NULL,
  `result_ugm3` decimal(10,4) DEFAULT NULL,
  `mrl_ugm3` decimal(10,4) DEFAULT NULL,
  `result_ppbv` decimal(10,4) DEFAULT NULL,
  `mrl_ppbv` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `surrogates`
--

CREATE TABLE IF NOT EXISTS `surrogates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(100) NOT NULL,
  `client_sample_id` varchar(100) DEFAULT NULL,
  `cas_sample_id` varchar(100) DEFAULT NULL,
  `dichlor` decimal(10,4) DEFAULT NULL,
  `toluene` decimal(10,4) DEFAULT NULL,
  `bromo` decimal(10,4) DEFAULT NULL,
  `limits` varchar(200) DEFAULT NULL,
  `data_qualifier` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tic`
--

CREATE TABLE IF NOT EXISTS `tic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(100) NOT NULL,
  `retention` decimal(10,4) DEFAULT NULL,
  `compound` varchar(100) DEFAULT NULL,
  `concentration_ugm3` decimal(10,4) NOT NULL,
  `data_qualifier` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `information`
--
ALTER TABLE `information`
  ADD CONSTRAINT `information_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `client_info` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mblank`
--
ALTER TABLE `mblank`
  ADD CONSTRAINT `mblank_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `client_info` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mblank_tic`
--
ALTER TABLE `mblank_tic`
  ADD CONSTRAINT `mblank_tic_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `client_info` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mblanktwo`
--
ALTER TABLE `mblanktwo`
  ADD CONSTRAINT `mblanktwo_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `client_info` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sample`
--
ALTER TABLE `sample`
  ADD CONSTRAINT `sample_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `client_info` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sampletwo`
--
ALTER TABLE `sampletwo`
  ADD CONSTRAINT `sampletwo_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `client_info` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `surrogates`
--
ALTER TABLE `surrogates`
  ADD CONSTRAINT `surrogates_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `client_info` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tic`
--
ALTER TABLE `tic`
  ADD CONSTRAINT `tic_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `client_info` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
