-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 17, 2015 at 11:09 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `neatejm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `adoption_request`
--

CREATE TABLE IF NOT EXISTS `adoption_request` (
  `adoptionID` int(10) NOT NULL,
  `userID` varchar(30) NOT NULL,
  `animalID` int(10) NOT NULL,
  `approved` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adoption_request`
--

INSERT INTO `adoption_request` (`adoptionID`, `userID`, `animalID`, `approved`) VALUES
(1, 'test1', 1, 1),
(2, 'test1', 4, 1),
(3, 'test2', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `animal`
--

CREATE TABLE IF NOT EXISTS `animal` (
  `animalID` int(10) NOT NULL,
  `name` varchar(90) NOT NULL,
  `type` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `description` varchar(300) NOT NULL,
  `photo_link` varchar(300) DEFAULT NULL,
  `available` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `animal`
--

INSERT INTO `animal` (`animalID`, `name`, `type`, `date_of_birth`, `description`, `photo_link`, `available`) VALUES
(1, 'Guppy', 'Goldfish', '2010-03-27', 'A big massive goldfish - Probably resulting from alien testing.', 'img/goldfish.png', 0),
(2, 'Snakey', 'Snake', '2014-05-01', 'Poisonous coral snake - Requires careful attention!', 'img/snake.png', 0),
(3, 'Bert', 'Beetle', '2008-06-01', 'One of those hard indestructible beetles you find everywhere - This one is invincible.', 'img/beetle.png', 1),
(4, 'Barry', 'Brown Bear', '2013-02-06', 'Young brown bear - Requires special vegan diet.', 'img/bear.png', 0),
(5, 'Snappy', 'Crocodile', '2014-06-02', 'House trained crocodile - Suitable companion for walks around the park.', 'img/crocodile.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `owns`
--

CREATE TABLE IF NOT EXISTS `owns` (
  `ownID` int(10) NOT NULL,
  `userID` varchar(30) NOT NULL,
  `animalID` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `owns`
--

INSERT INTO `owns` (`ownID`, `userID`, `animalID`) VALUES
(1, 'test1', 1),
(2, 'test2', 2),
(3, 'staff', 3),
(4, 'test1', 4),
(5, 'staff', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` varchar(30) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `staff` tinyint(1) NOT NULL,
  `questionID` int(3) NOT NULL,
  `sec_ans` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `email`, `password`, `staff`, `questionID`, `sec_ans`) VALUES
('neate', 'james.neate@capgemini.com', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 'red'),
('test1', 'test@test1.com', '133987b0b6ad0c01fc0ccbdae1b95449', 0, 1, 'Green'),
('test2', 'test@test2.com', '133987b0b6ad0c01fc0ccbdae1b95449', 0, 3, 'Cat');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adoption_request`
--
ALTER TABLE `adoption_request`
  ADD PRIMARY KEY (`adoptionID`);

--
-- Indexes for table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`animalID`);

--
-- Indexes for table `owns`
--
ALTER TABLE `owns`
  ADD PRIMARY KEY (`ownID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adoption_request`
--
ALTER TABLE `adoption_request`
  MODIFY `adoptionID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `animal`
--
ALTER TABLE `animal`
  MODIFY `animalID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `owns`
--
ALTER TABLE `owns`
  MODIFY `ownID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
