-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 21, 2024 at 05:52 AM
-- Server version: 10.2.38-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `module3`
--

-- --------------------------------------------------------

--
-- Table structure for table `commentTable`
--

CREATE TABLE `commentTable` (
  `comment_id` tinyint(3) UNSIGNED NOT NULL,
  `comment` varchar(200) NOT NULL,
  `user_id` tinyint(3) UNSIGNED NOT NULL,
  `username` varchar(200) NOT NULL,
  `story_id` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commentTable`
--

INSERT INTO `commentTable` (`comment_id`, `comment`, `user_id`, `username`, `story_id`) VALUES
(1, 'blue!', 1, 'jenny', 2),
(2, 'matcha!', 2, 'selina', 3);

-- --------------------------------------------------------

--
-- Table structure for table `storyTable`
--

CREATE TABLE `storyTable` (
  `title` varchar(200) NOT NULL,
  `story_id` tinyint(3) UNSIGNED NOT NULL,
  `author` varchar(200) NOT NULL,
  `author_id` tinyint(3) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `link` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `storyTable`
--

INSERT INTO `storyTable` (`title`, `story_id`, `author`, `author_id`, `content`, `link`) VALUES
('favorite color', 2, 'selina', 2, 'green', 'https://upload.wikimedia.org/wikipedia/commons/f/f3/Green.PNG'),
('favorite drink', 3, 'jenny', 1, 'coffee', 'https://upload.wikimedia.org/wikipedia/commons/e/e4/Latte_and_dark_coffee.jpg'),
('Copy of favorite drink', 4, 'jenny', 1, 'coffee', 'https://upload.wikimedia.org/wikipedia/commons/e/e4/Latte_and_dark_coffee.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `userTable`
--

CREATE TABLE `userTable` (
  `user_id` tinyint(3) UNSIGNED NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `question` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userTable`
--

INSERT INTO `userTable` (`user_id`, `username`, `password`, `question`) VALUES
(1, 'jenny', '$2y$10$h/iWyoIG/NmbrSO/md08kufJuG3h.biBzceAn0ZkewYyY2Tq3KVCu', 'korea'),
(2, 'selina', '$2y$10$7zC5r2hqNNiO.XTCjgQlkecVzo0VgCD9NpDe6/ud1ajyAdSAmLRm2', 'korea'),
(3, 'hy', '$2y$10$AbNfTACncN6VVMHZlb5OwOeIRZIx0qospqXFAxv7Dw9N1ax7tTj3W', 'korea'),
(4, 'mj', '$2y$10$E76wa2zkUGxiBr03a.RoOeztSh.FlxDpu8Md27fbqGNI/0Ub/oFc.', 'korea');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commentTable`
--
ALTER TABLE `commentTable`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `storyTable`
--
ALTER TABLE `storyTable`
  ADD PRIMARY KEY (`story_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `userTable`
--
ALTER TABLE `userTable`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commentTable`
--
ALTER TABLE `commentTable`
  MODIFY `comment_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `storyTable`
--
ALTER TABLE `storyTable`
  MODIFY `story_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `userTable`
--
ALTER TABLE `userTable`
  MODIFY `user_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commentTable`
--
ALTER TABLE `commentTable`
  ADD CONSTRAINT `commentTable_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `userTable` (`user_id`);

--
-- Constraints for table `storyTable`
--
ALTER TABLE `storyTable`
  ADD CONSTRAINT `storyTable_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `userTable` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
