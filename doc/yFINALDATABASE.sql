-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2023 at 03:41 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `y`
--

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `ID` int(11) UNSIGNED NOT NULL,
  `user_ID` int(10) UNSIGNED NOT NULL,
  `postID` int(11) UNSIGNED NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`ID`, `user_ID`, `postID`, `time`) VALUES
(48, 18, 71, '2023-12-12 03:27:53'),
(50, 19, 71, '2023-12-12 03:33:06'),
(54, 17, 70, '2023-12-13 02:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `ID` int(11) UNSIGNED NOT NULL,
  `user_ID` int(11) UNSIGNED NOT NULL,
  `Content` varchar(180) NOT NULL,
  `fileUpload` varchar(64) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`ID`, `user_ID`, `Content`, `fileUpload`, `time`) VALUES
(70, 17, 'Noah was here', 'uploads/R.jpg', '2023-12-12 03:26:08'),
(71, 18, 'SQL IS AMAZING!', '', '2023-12-12 03:27:51');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `ID` int(11) UNSIGNED NOT NULL,
  `user_ID` int(10) UNSIGNED NOT NULL,
  `postID` int(11) UNSIGNED NOT NULL,
  `Content` varchar(90) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`ID`, `user_ID`, `postID`, `Content`, `time`) VALUES
(15, 17, 71, 'i agree Nick! I love sql.', '2023-12-12 03:28:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(10) UNSIGNED NOT NULL,
  `username` varchar(25) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `adminStatus` tinyint(3) UNSIGNED NOT NULL,
  `Bio` varchar(120) DEFAULT NULL,
  `profilePic` varchar(64) DEFAULT NULL,
  `profileBanner` varchar(64) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `Password`, `adminStatus`, `Bio`, `profilePic`, `profileBanner`, `time`) VALUES
(17, 'noah', '$2y$10$SFclAW/5HD6kg1Rn6ogXOO42c42/NV1F2GDnXU98.e2j6em2sPNRe', 1, 'This is noah\'s bio!', 'fileuploads/Picture1.png', 'fileuploads/Screenshot 2023-09-16 121143.png', '2023-12-12 03:24:48'),
(18, 'nick', '$2y$10$lUs83n4HH5pZ16dsZ1PVYeC053XeSMaMo4kUpJNAPZo9TCzaJ1lxS', 0, 'Nicks bio guys!', 'fileuploads/Screenshot 2023-10-05 105131.png', 'fileuploads/Screenshot 2023-11-02 104844.png', '2023-12-12 03:26:25'),
(19, 'admin', '$2y$10$EmujZyoPgedVhUAdDRQaWeaWhFr/rXgVZmRc0SYlIs1xddbiiklDa', 1, 'This is Caporusso\'s account!', 'fileuploads/jr-korpa-E2i7Hftb_rI-unsplash.jpg', 'fileuploads/pawel-czerwinski-6lQDFGOB1iw-unsplash.jpg', '2023-12-12 03:29:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `likes_user_ID_to_users_ID` (`user_ID`),
  ADD KEY `likes_posts_fk` (`postID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_ID_to_users_ID` (`user_ID`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `reply_user_ID_to_users_ID` (`user_ID`),
  ADD KEY `reply_post_fk` (`postID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_posts_fk` FOREIGN KEY (`postID`) REFERENCES `posts` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_user_ID_to_users_ID` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `user_ID_to_users_ID` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `reply_post_fk` FOREIGN KEY (`postID`) REFERENCES `posts` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `reply_user_ID_to_users_ID` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
