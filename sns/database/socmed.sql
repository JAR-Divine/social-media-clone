-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2021 at 07:47 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `socmed`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_body` text NOT NULL,
  `posted_by` varchar(60) NOT NULL,
  `posted_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `removed` varchar(3) NOT NULL,
  `posts_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_body`, `posted_by`, `posted_to`, `date_added`, `removed`, `posts_id`) VALUES
(1, 'I\'ll be posting more later!', 'jar_asuncion', 'jar_asuncion', '2021-01-28 05:38:43', 'no', 1),
(2, 'Same! Good thing you told me about this!', 'emma_richardson', 'jar_asuncion', '2021-01-28 05:42:27', 'no', 1);

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `user_from` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `user_to`, `user_from`) VALUES
(0, 'tony_stark', 'jar_asuncion');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `posts_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `username`, `posts_id`) VALUES
(11, 'jar_asuncion', 4);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `posts_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `posted_by` varchar(60) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `post_deleted` varchar(3) NOT NULL,
  `likes` int(11) NOT NULL,
  `images` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`posts_id`, `body`, `posted_by`, `user_to`, `date_added`, `user_closed`, `post_deleted`, `likes`, `images`) VALUES
(1, 'Just Joined! This is my first Buzz!', 'jar_asuncion', 'none', '2021-01-28 05:38:25', 'no', 'no', 0, ''),
(2, 'After so much trial and error! I can almost finish this!\r\n', 'jar_asuncion', 'none', '2021-01-28 05:41:27', 'no', 'no', 0, ''),
(3, 'Looking forward to this app!\r\n#FirstBuzz', 'emma_richardson', 'none', '2021-01-28 05:42:02', 'no', 'no', 0, ''),
(4, 'Looking forward to this app!\r\n#FirstBuzz', 'emma_richardson', 'none', '2021-01-28 05:44:52', 'no', 'no', 1, ''),
(6, 'uhhh... I can\'t figure this out again!', 'jar_asuncion', 'none', '2021-01-31 04:35:31', 'no', 'no', 0, ''),
(7, 'Hello~!', 'jar_asuncion', 'emma_richardson', '2021-01-31 04:37:40', 'no', 'no', 0, ''),
(8, 'Jennie so pretty!', 'emma_richardson', 'none', '2021-01-31 09:52:20', 'no', 'no', 0, ''),
(9, 'Jennie is soo pretty!', 'emma_richardson', 'none', '2021-01-31 09:53:26', 'no', 'no', 0, ''),
(10, 'Jennie is soo pretty!', 'emma_richardson', 'none', '2021-01-31 09:59:14', 'no', 'no', 0, ''),
(11, 'PRETTY', 'emma_richardson', 'none', '2021-01-31 09:59:34', 'no', 'no', 0, ''),
(12, 'PRETTY', 'emma_richardson', 'none', '2021-01-31 10:11:42', 'no', 'no', 0, ''),
(13, 'So Pretty~', 'emma_richardson', 'none', '2021-01-31 10:16:05', 'no', 'no', 0, 'public/assets/images/posts/60168365ca38c20210121_054258.jpg'),
(14, 'I think I\'m almost done, but not really?', 'emma_richardson', 'none', '2021-01-31 10:20:08', 'no', 'no', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `register_date` date NOT NULL,
  `profile_img` varchar(255) NOT NULL,
  `posts_no` int(11) NOT NULL,
  `likes_no` int(11) NOT NULL,
  `user_active` varchar(3) NOT NULL,
  `friends` text NOT NULL,
  `gender` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `email`, `password`, `register_date`, `profile_img`, `posts_no`, `likes_no`, `user_active`, `friends`, `gender`) VALUES
(27, 'Jar', 'Asuncion', 'jar_asuncion', 'jar@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2021-01-22', 'public/assets/images/profile_img/default/ddp_male.png', 5, 0, 'no', ',emma_richardson,jennie_kim,', 'Male'),
(28, 'Emma', 'Richardson', 'emma_richardson', 'emrich@gmail.com', '25f9e794323b453885f5181f1b624d0b', '2021-01-25', 'public/assets/images/profile_img/default/ddp_female.png', 9, 0, 'no', ',jar_asuncion,', 'Female'),
(29, 'Tony', 'Stark', 'tony_stark', 'stark@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2021-01-27', 'public/assets/images/profile_img/default/ddp_male.png', 0, 0, 'no', ',', 'Male'),
(30, 'Jennie', 'Kim', 'jennie_kim', 'jen@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2021-01-27', 'public/assets/images/profile_img/default/ddp_female.png', 0, 0, 'no', ',jar_asuncion,', 'Female');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`posts_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `posts_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
