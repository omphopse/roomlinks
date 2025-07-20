-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 11:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rooms`
--

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `images` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` varchar(1200) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location`, `created_at`, `updated_at`) VALUES
(1, 'om', '2025-07-18 17:14:10', '2025-07-18 17:14:10');

-- --------------------------------------------------------

--
-- Table structure for table `massage`
--

CREATE TABLE `massage` (
  `id` int(11) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `massage` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `who_are_you` varchar(100) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `avatar_id` int(11) DEFAULT NULL,
  `uploaded_picture` varchar(255) DEFAULT NULL,
  `vibes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `name`, `phone`, `who_are_you`, `gender`, `location`, `avatar_id`, `uploaded_picture`, `vibes`) VALUES
(34, 'Om Phopse', '9874563210', 'looking_room', 'male', '1', 10, NULL, NULL),
(35, 'Om Phopse', '1236547890', 'looking_room', 'male', '1', 9, NULL, NULL),
(36, 'Om Phopse', '9871234560', 'looking_room', 'male', '1', 9, NULL, NULL),
(37, 'Om Phopse', '9856327410', 'looking_room', 'male', '1', 11, NULL, NULL),
(38, 'Om Phopse', '2236547890', 'looking_room', 'male', '1', 11, NULL, '10 | 5 | 12 | 7 | 8'),
(40, 'Om Phopse', '6589874100', 'looking_room', 'male', '1', 10, NULL, NULL),
(41, 'Om Phopse', '1239087456', 'looking_room', 'male', '1', 13, NULL, '1 | 12 | 7 | 8 | 9 | 11');

-- --------------------------------------------------------

--
-- Table structure for table `profile_pictures`
--

CREATE TABLE `profile_pictures` (
  `id` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_pictures`
--

INSERT INTO `profile_pictures` (`id`, `gender`, `image`, `created_at`) VALUES
(1, 'women', 'assets/profile/women/profile_687af1fce3d107.08186229.png', '2025-07-19 01:16:44'),
(2, 'women', 'assets/profile/women/profile_687af1fce67868.15741154.png', '2025-07-19 01:16:44'),
(3, 'women', 'assets/profile/women/profile_687af1fceabe83.54656504.png', '2025-07-19 01:16:44'),
(4, 'women', 'assets/profile/women/profile_687af1fcebd165.11358117.png', '2025-07-19 01:16:44'),
(5, 'women', 'assets/profile/women/profile_687af2189b2df8.45791186.png', '2025-07-19 01:17:12'),
(6, 'women', 'assets/profile/women/profile_687af2189c65d3.13480477.png', '2025-07-19 01:17:12'),
(7, 'women', 'assets/profile/women/profile_687af2189d7e64.10814640.png', '2025-07-19 01:17:12'),
(8, 'women', 'assets/profile/women/profile_687af2189ebd55.79380564.png', '2025-07-19 01:17:12'),
(9, 'men', 'assets/profile/men/profile_687af250472096.36727297.png', '2025-07-19 01:18:08'),
(10, 'men', 'assets/profile/men/profile_687af250499c69.63957044.png', '2025-07-19 01:18:08'),
(11, 'men', 'assets/profile/men/profile_687af2504b5d37.15242728.png', '2025-07-19 01:18:08'),
(12, 'men', 'assets/profile/men/profile_687af2504c95e8.99020003.png', '2025-07-19 01:18:08'),
(13, 'men', 'assets/profile/men/profile_687af2504d8639.22630833.png', '2025-07-19 01:18:08'),
(14, 'men', 'assets/profile/men/profile_687af2504eb158.13211831.png', '2025-07-19 01:18:08'),
(15, 'men', 'assets/profile/men/profile_687af2504fc216.24695435.png', '2025-07-19 01:18:08'),
(16, 'men', 'assets/profile/men/profile_687af25055a7a9.99120744.png', '2025-07-19 01:18:08');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `phone`, `role`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$zSOmZKuvsVC5dJ51ynT/6OAiiGNpsg/MlDfDwLxBGtbBddukt4LJ6', '9623908967', 'admin'),
(34, 'om12@mail.com', 'om12@mail.com', '$2y$10$k8Lg/QXh2VlbzatRc7RRjeHcQeBElr8wSXVsExuRC4WclOdn3Gg0C', '9874563210', 'user'),
(35, 'om64@mail.com', 'om64@mail.com', '$2y$10$6BeNXpMFBryMEfEm/EOiTe.BQERueI8wKJrel5rU9chrndFJHqqie', '1236547890', 'user'),
(36, 'om12324@mail.com', 'om12324@mail.com', '$2y$10$fCkZP3.5AzQ.zrNQiPrp3O8ZaXA8ghIUEuai082gZEYEJir7NZnYy', '9871234560', 'user'),
(37, 'om4523@mail.com', 'om4523@mail.com', '$2y$10$26F1KXlzx/Vx1pdr2Hbd0.qYPjSHvpNqfSNYDqhx5bVC29GbyOjhO', '9856327410', 'user'),
(38, 'Om232@mail.com', 'Om232@mail.com', '$2y$10$QgDkTT0OSlW7HUjoaeGpierZNMQw/guYAiaId2ItwdqnSyzdHHZl6', '2236547890', 'user'),
(39, 'om4242@mail.com', 'om4242@mail.com', '$2y$10$tqUC72Wjq3dPapKq/iQ4S.6ZvX4Vb6u2CAF4XPrpKQPkvkC2XSvN2', NULL, 'user'),
(40, 'Om543@mail.com', 'Om543@mail.com', '$2y$10$L0FHIHpOJ8fH085.3Arv3uE6/.DTH2mqDn8vhppNE0BwEPtf0Zx5q', '6589874100', 'user'),
(41, 'om121232@mail.com', 'om121232@mail.com', '$2y$10$bFQyvzFJcxSkTz3.SDcw9ea3ySOc4aWNt897tG.tpW4xdnGrlVSyi', '1239087456', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `vibes`
--

CREATE TABLE `vibes` (
  `id` int(11) NOT NULL,
  `images` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vibes`
--

INSERT INTO `vibes` (`id`, `images`, `created_at`, `updated_at`, `name`) VALUES
(1, 'assets/vibes/Chai_Lover_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Chai Lover'),
(2, 'assets/vibes/Chill_Dude_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Chill Dude'),
(3, 'assets/vibes/Gamer_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Gamer'),
(4, 'assets/vibes/Foodie_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Foodie'),
(5, 'assets/vibes/Early_Bird_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Early Bird'),
(6, 'assets/vibes/Gym_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Gym'),
(7, 'assets/vibes/Party_Person_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Party Person'),
(8, 'assets/vibes/Reader_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Reader'),
(9, 'assets/vibes/Simple_Living_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Simple Living'),
(10, 'assets/vibes/Cinephile_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Cinephile'),
(11, 'assets/vibes/Student_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Student'),
(12, 'assets/vibes/Night_Owl_20250719034757.png', '2025-07-19 01:47:57', '2025-07-19 01:47:57', 'Night Owl');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `massage`
--
ALTER TABLE `massage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatar_id` (`avatar_id`);

--
-- Indexes for table `profile_pictures`
--
ALTER TABLE `profile_pictures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vibes`
--
ALTER TABLE `vibes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `massage`
--
ALTER TABLE `massage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile_pictures`
--
ALTER TABLE `profile_pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `vibes`
--
ALTER TABLE `vibes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profile_ibfk_2` FOREIGN KEY (`avatar_id`) REFERENCES `profile_pictures` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
