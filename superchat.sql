-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2023 at 02:49 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `username1` varchar(30) NOT NULL,
  `username2` varchar(30) NOT NULL,
  `last_message` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `username1`, `username2`, `last_message`) VALUES
(13, 'swayx', 'hadidev', '2023-03-27 11:54:07'),
(14, 'hadidev', 'hadiswaydan', '2023-03-27 11:55:06'),
(15, 'hadiswaydan', 'test', NULL),
(16, 'hadiswaydan', 'greenpin', '2023-03-27 11:57:25'),
(17, 'hadiswaydan', 'swayx', '2023-03-27 12:04:16');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `sender_id` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- --------------------------------------------------------

--
-- Table structure for table `seen_messages`
--

CREATE TABLE `seen_messages` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `seen_at` datetime NOT NULL DEFAULT current_timestamp(),
  `chat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seen_messages`
--

INSERT INTO `seen_messages` (`id`, `message_id`, `username`, `seen_at`, `chat_id`) VALUES
(217, 165, 'swayx', '2023-03-27 14:41:25', 13),
(218, 166, 'swayx', '2023-03-27 14:41:28', 13),
(219, 165, 'hadidev', '2023-03-27 14:41:45', 13),
(220, 166, 'hadidev', '2023-03-27 14:41:45', 13),
(221, 167, 'hadidev', '2023-03-27 14:42:03', 13),
(222, 167, 'swayx', '2023-03-27 14:43:26', 13),
(223, 168, 'hadidev', '2023-03-27 14:53:58', 13),
(224, 168, 'swayx', '2023-03-27 14:53:58', 13),
(225, 169, 'swayx', '2023-03-27 14:54:07', 13),
(226, 169, 'hadidev', '2023-03-27 14:54:07', 13),
(227, 170, 'hadidev', '2023-03-27 14:54:32', 14),
(228, 171, 'hadidev', '2023-03-27 14:54:38', 14),
(229, 170, 'hadiswaydan', '2023-03-27 14:55:02', 14),
(230, 171, 'hadiswaydan', '2023-03-27 14:55:03', 14),
(231, 172, 'hadiswaydan', '2023-03-27 14:55:06', 14),
(232, 172, 'hadidev', '2023-03-27 14:55:07', 14),
(233, 173, 'hadiswaydan', '2023-03-27 14:57:25', 16),
(234, 174, 'swayx', '2023-03-27 15:04:16', 17),
(235, 174, 'hadiswaydan', '2023-03-27 15:04:49', 17);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `created`, `email`) VALUES
('greenpin', '82be04caa7eb385b28346d3f878345b3', '2023-03-26 15:28:24', 'awdawf@gmail.com'),
('hadidev', '82be04caa7eb385b28346d3f878345b3', '2023-01-10 14:17:04', 'revelationbribe@gmail.com'),
('hadiswaydan', '82be04caa7eb385b28346d3f878345b3', '2023-03-26 14:11:15', 'swearandblear2@gmail.com'),
('swayx', '82be04caa7eb385b28346d3f878345b3', '2023-01-10 14:00:36', 'hadiswaydane2002@gmail.com'),
('test', '82be04caa7eb385b28346d3f878345b3', '2023-03-26 14:15:03', 'peyoz@xcodes.net');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username1` (`username1`),
  ADD KEY `username2` (`username2`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `seen_messages`
--
ALTER TABLE `seen_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `username` (`username`),
  ADD KEY `fk_seen_messages_chat_id` (`chat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `seen_messages`
--
ALTER TABLE `seen_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`username1`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`username2`) REFERENCES `users` (`username`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`username`);

--
-- Constraints for table `seen_messages`
--
ALTER TABLE `seen_messages`
  ADD CONSTRAINT `fk_seen_messages_chat_id` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `seen_messages_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`),
  ADD CONSTRAINT `seen_messages_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
