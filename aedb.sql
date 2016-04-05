-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 05, 2016 at 06:07 AM
-- Server version: 5.7.11
-- PHP Version: 5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `ae_categories`
--

CREATE TABLE `ae_categories` (
  `id` bigint(20) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT '#000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ae_categories`
--

INSERT INTO `ae_categories` (`id`, `slug`, `name`, `color`) VALUES
(1, 'du-lich', 'Du lịch', '#00B355'),
(2, 'test-category', 'test category', '#00B355'),
(4, 'cong-hoa-xa-hoi-chu-nghia-viet-nam', 'cộng hòa xã hội chủ nghĩa việt nam', '#00B355'),
(5, 'cong-hoa-xa-hoi-chu-nghia-viet-nam-1', 'cộng hòa xã hội chủ nghĩa việt nam', '#00B355'),
(7, 'giai-tri-abc-f', 'giải trí', '#00B355'),
(8, 'tin-tuc-123', 'tin tức trong ngày', '#00B355'),
(9, 'co-so-du-lieu', 'cơ sở dữ liệu', '#00B355'),
(10, '-', '健康', '#00B355'),
(12, 'giai-tri-abc-f-1', 'giải trí abc f', '#00B355'),
(13, 'another-category', 'another category', '#000000'),
(14, 'viet-nam', 'việt nam', '#3DF278'),
(15, 'blkjdkbnkndcc', 'blkjdkbnknđcc', '#237ADE'),
(16, 'fdfdft46666-gfg-fbbv-x', 'fdfdft46666 gfg fbbv x', '#340A56');

-- --------------------------------------------------------

--
-- Table structure for table `ae_likes`
--

CREATE TABLE `ae_likes` (
  `id` bigint(20) NOT NULL,
  `user` bigint(20) NOT NULL,
  `post` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ae_notifications`
--

CREATE TABLE `ae_notifications` (
  `id` bigint(20) NOT NULL,
  `user` bigint(20) NOT NULL,
  `is_new_reply` tinyint(1) NOT NULL DEFAULT '1',
  `topic` bigint(20) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ae_notifications`
--

INSERT INTO `ae_notifications` (`id`, `user`, `is_new_reply`, `topic`, `is_read`) VALUES
(1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ae_options`
--

CREATE TABLE `ae_options` (
  `id` bigint(20) NOT NULL,
  `option_name` varchar(191) NOT NULL,
  `option_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ae_options`
--

INSERT INTO `ae_options` (`id`, `option_name`, `option_value`) VALUES
(1, 'home_url', 'http://localhost:8080/AngularExchange');

-- --------------------------------------------------------

--
-- Table structure for table `ae_posts`
--

CREATE TABLE `ae_posts` (
  `id` bigint(20) NOT NULL,
  `topic` bigint(20) NOT NULL,
  `author` bigint(20) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `content` longtext NOT NULL,
  `like_count` bigint(20) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ae_posts`
--

INSERT INTO `ae_posts` (`id`, `topic`, `author`, `created`, `modified`, `content`, `like_count`, `ip`) VALUES
(5, 3, 1, '2016-03-24 10:46:27', '2016-03-24 10:46:27', '333333\r\nbvbvbvbvb\r\n\r\nvbvbvbvbv', 0, '123.123.123.123'),
(6, 3, 1, '2016-03-24 10:46:47', '2016-03-24 10:46:47', '333333\r\nbvbvbvbvb\r\n\r\nvbvbvbvbfv 55464', 0, '123.123.123.123'),
(7, 9, 5, '2016-03-31 08:37:17', '2016-03-31 08:37:17', 'I have seen both angular.factory() and angular.service() used to declare services; however, I cannot find angular.service anywhere in official documentation.\r\n\r\nWhat is the difference between the two methods? Which should be used for what (assuming they do different things)?', 0, '321.321.321.321');

-- --------------------------------------------------------

--
-- Table structure for table `ae_topics`
--

CREATE TABLE `ae_topics` (
  `id` bigint(20) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `category` bigint(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `view_count` bigint(20) NOT NULL DEFAULT '0',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  `is_private_message` tinyint(1) NOT NULL DEFAULT '0',
  `original_poster` bigint(20) NOT NULL,
  `frequent_poster_1` bigint(20) DEFAULT NULL,
  `frequent_poster_2` bigint(20) DEFAULT NULL,
  `most_recent_poster` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ae_topics`
--

INSERT INTO `ae_topics` (`id`, `slug`, `category`, `title`, `view_count`, `comment_count`, `is_private_message`, `original_poster`, `frequent_poster_1`, `frequent_poster_2`, `most_recent_poster`) VALUES
(3, 'hello-world-3', 15, 'Hello World', 0, 0, 0, 1, NULL, NULL, NULL),
(4, 'abc-3-bbbb', 1, 'ommand. And by specifying an array of changes', 0, 0, 0, 1, NULL, NULL, NULL),
(5, 'abc-3-bbbb1', 14, 'ommand. And by specifying an array of changes 3323232323', 0, 0, 0, 1, NULL, NULL, NULL),
(6, 'abc-3-bbbb12', 2, 'ommand. And by specifying an array of changes 3323232323', 0, 0, 0, 3, NULL, NULL, NULL),
(7, 'abc-3-bbbb2', 1, 'ommand. And by specifying an array of changes 3323232323', 0, 0, 0, 13, NULL, NULL, NULL),
(8, 'hyimy-d2', 16, 'ommand. And by specifying an array of changes 3323232323', 0, 0, 0, 1, NULL, NULL, NULL),
(9, 'b-bfbfb-f', 2, 'Topbidkc kndkfm,zm bxmcn', 0, 0, 0, 5, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ae_uploads`
--

CREATE TABLE `ae_uploads` (
  `id` bigint(20) NOT NULL,
  `file_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ae_users`
--

CREATE TABLE `ae_users` (
  `id` bigint(20) NOT NULL,
  `login` varchar(60) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `display_name` varchar(60) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(100) NOT NULL,
  `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `post_count` bigint(20) NOT NULL DEFAULT '0',
  `topic_count` bigint(20) NOT NULL DEFAULT '0',
  `likes_given` bigint(20) NOT NULL DEFAULT '0',
  `likes_received` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ae_users`
--

INSERT INTO `ae_users` (`id`, `login`, `pass`, `display_name`, `role`, `email`, `registered`, `post_count`, `topic_count`, `likes_given`, `likes_received`) VALUES
(1, 'admin', '14e1b600b1fd579f47433b88e8d85291', 'Đào Tuấn Anh', 2, 'kitsudo14123@gmail.com', '2016-03-29 09:41:54', 0, 0, 0, 0),
(3, 'kbkjdn', 'kkbnbnbn', 'Nguyễn Văn A', 1, 'kk@gmail.com', '2016-03-25 10:57:47', 0, 0, 0, 0),
(4, 'JKJKJb', '157158debe02309427a0267128983213', 'Nguyễn Văn B', 1, 'kkk@gmail.com', '2016-03-29 08:08:54', 0, 0, 0, 0),
(5, 'galkb', 'acf7ef943fdeb3cbfed8dd0d8f584731', 'Guestb dd', 1, '343k4@gmail.com', '2016-03-29 08:08:01', 0, 0, 0, 0),
(6, 'kitsudo1412', '74be16979710d4c4e7c6647856088456', 'Đào Tuấn Anh', 1, 'kitsudo1412@gmail.com', '2016-03-29 07:59:03', 0, 0, 0, 0),
(12, 'kfdknvk', 'd41d8cd98f00b204e9800998ecf8427e', 'Robin Schebaski', 1, 'robin@gmail.com', '2016-03-29 08:17:54', 0, 0, 0, 0),
(13, 'barney', 'acf7ef943fdeb3cbfed8dd0d8f584731', 'Barney Stinson', 2, 'b@gmail.com', '2016-03-29 08:20:16', 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ae_categories`
--
ALTER TABLE `ae_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `ae_likes`
--
ALTER TABLE `ae_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_post` (`user`,`post`),
  ADD KEY `post` (`post`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `ae_notifications`
--
ALTER TABLE `ae_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `ae_options`
--
ALTER TABLE `ae_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `option_name` (`option_name`),
  ADD KEY `option_name_2` (`option_name`);

--
-- Indexes for table `ae_posts`
--
ALTER TABLE `ae_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic` (`topic`),
  ADD KEY `author` (`author`);

--
-- Indexes for table `ae_topics`
--
ALTER TABLE `ae_topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `slug_2` (`slug`),
  ADD KEY `category` (`category`),
  ADD KEY `original_poster` (`original_poster`),
  ADD KEY `is_private_message` (`is_private_message`);
ALTER TABLE `ae_topics` ADD FULLTEXT KEY `title` (`title`);

--
-- Indexes for table `ae_uploads`
--
ALTER TABLE `ae_uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ae_users`
--
ALTER TABLE `ae_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ae_categories`
--
ALTER TABLE `ae_categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `ae_likes`
--
ALTER TABLE `ae_likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ae_notifications`
--
ALTER TABLE `ae_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ae_options`
--
ALTER TABLE `ae_options`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ae_posts`
--
ALTER TABLE `ae_posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `ae_topics`
--
ALTER TABLE `ae_topics`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `ae_uploads`
--
ALTER TABLE `ae_uploads`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ae_users`
--
ALTER TABLE `ae_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ae_likes`
--
ALTER TABLE `ae_likes`
  ADD CONSTRAINT `fk_ae_likes_ae_posts` FOREIGN KEY (`post`) REFERENCES `ae_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ae_likes_ae_users` FOREIGN KEY (`user`) REFERENCES `ae_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ae_posts`
--
ALTER TABLE `ae_posts`
  ADD CONSTRAINT `fk_ae_posts_ae_topics` FOREIGN KEY (`topic`) REFERENCES `ae_topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ae_topics`
--
ALTER TABLE `ae_topics`
  ADD CONSTRAINT `fk_ae_topics_ae_categories` FOREIGN KEY (`category`) REFERENCES `ae_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
