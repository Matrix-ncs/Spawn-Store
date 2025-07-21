-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 02:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_info`
--

CREATE TABLE `admin_info` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_info`
--

INSERT INTO `admin_info` (`admin_id`, `username`, `password`) VALUES
(4, 'admin1', '$2y$10$J1hWVXUHOcsI7pyCIvwAw.2/KgTjq7h0A.YMqT8nNlVIXVEtZwSc2');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `downloaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`id`, `user_id`, `game_id`, `downloaded_at`) VALUES
(1, 18, 7, '2025-05-16 19:52:34'),
(2, 18, 2, '2025-05-16 23:18:49');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `game_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`game_id`, `name`, `price`, `description`, `size`, `file_path`) VALUES
(1, 'Apex Legends', 0.00, 'Apex Legends is a 2019 battle royale-hero shooter video game developed by Respawn Entertainment and published by Electronic Arts.', '27.8GB', 'ApexLegends.zip'),
(2, 'Battlefield V', 19.99, 'Battlefield V is a 2018 first-person shooter game developed by DICE and published by Electronic Arts.', '52GB', 'BattlefieldV.zip'),
(3, 'Call of Duty Warzone', 29.99, 'Call of Duty: Warzone was a 2020 free-to-play battle royale first-person shooter game developed by Raven Software and Infinity Ward.', '42GB', NULL),
(4, 'Dead by Daylight', 15.99, 'Dead by Daylight is an online asymmetric multiplayer survival horror video game developed and published by Canadian studio Behaviour Interactive.', '10GB', 'DeadbyDaylight.zip'),
(5, 'EA SPORTS FC™ 25', 39.99, 'EA Sports FC 25 is a football video game published by EA Sports. It is the second installment in the EA Sports FC series EA Sports.', '62.5GB', NULL),
(6, 'Elden Ring', 0.00, 'Elden Ring is a 2022 action role-playing game developed by FromSoftware. It was directed by Hidetaka Miyazaki with worldbuilding.', '60GB', NULL),
(7, 'Fortnite', 0.00, 'Fortnite is an online video game and game platform developed by Epic Games and released in 2017.', '53GB', 'Fortnite.zip'),
(8, 'Fall Guys', 4.99, 'Fall Guys is a free-to-play platform battle royale game developed by Mediatonic and originally published by Devolver Digital for Windows.', '8.7GB', NULL),
(9, 'Grand Theft Auto V', 12.99, 'Grand Theft Auto V is a 2013 action-adventure game developed by Rockstar North and published by Rockstar Games.', '110GB', NULL),
(10, 'Genshin Impact', 0.00, 'The game features an anime-style open world environment and an action-based battle system using elemental magic and character-switching.', '80.2GB', NULL),
(11, 'League of Legends', 0.00, 'League of Legends commonly referred to as League, is a 2009 multiplayer online battle arena video game developed and published by Riot Games.', '32GB', NULL),
(12, 'Minecraft', 5.99, 'Minecraft is a 2011 sandbox game developed and published by Swedish video game developer Mojang Studios.', '3GB', NULL),
(13, 'NFS Payback', 29.99, 'Need for Speed (NFS) is a racing game franchise published by Electronic Arts and currently developed by Criterion Games.', '52.5GB', NULL),
(14, 'Overwatch 2', 0.00, 'Overwatch 2 is a 2023 first-person shooter video game produced by Blizzard Entertainment.', '42GB', NULL),
(15, 'Palworld', 14.99, 'Palworld is an upcoming action-adventure, survival, and monster-taming game created and published by Japanese developer Pocketpair.', '53GB', NULL),
(16, 'Roblox', 0.00, 'Roblox is an online game platform developed by Roblox Corporation that allows users to program and play games created by themselves or other users.', '3.7GB', NULL),
(17, 'Red Dead Redemption 2', 16.99, 'Red Dead Redemption 2 is a 2018 action-adventure game developed and published by Rockstar Games.', '75.6GB', NULL),
(18, 'Resident Evil 4', 19.99, 'Resident Evil 4 is a 2005 survival horror game developed and published by Capcom for the GameCube.', '46.2GB', NULL),
(19, 'Rocket League', 0.00, 'Rocket League is a 2015 vehicular soccer video game developed and published by Psyonix for various home consoles and computers.', '37.2GB', NULL),
(20, 'Rust', 14.99, 'Rust is a multiplayer survival video game developed by Facepunch Studios.', '41GB', NULL),
(21, 'The Witcher 3 Wild Hunt', 19.99, 'The Witcher 3: Wild Hunt is a 2015 action role-playing game developed and published by the Polish studio CD Projekt.', '46.5GB', NULL),
(22, 'Valorant', 0.00, 'Valorant is a 2020 first-person tactical hero shooter video game developed and published by Riot Games.', '29.9GB', NULL),
(23, 'Vampire The Masquerade', 19.99, 'Vampire: The Masquerade is a tabletop role-playing game created by Mark Rein-Hagen and released in 1991 by White Wolf Publishing.', '43GB', NULL),
(24, 'WWE 2k25', 39.99, 'WWE 2K25 is a professional wrestling sports video game developed by Visual Concepts and published by 2K.', '61.7GB', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login_info`
--

CREATE TABLE `login_info` (
  `id` int(11) NOT NULL,
  `eaddress` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_code` varchar(6) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_info`
--

INSERT INTO `login_info` (`id`, `eaddress`, `password`, `reset_code`, `reset_expiry`, `name`, `dob`) VALUES
(18, '123@gmail.com', '$2y$10$nwh6EuTCENRVtnNUMbxUcOtU18.lZhTaMvh0fTXW0oTfdvBfWR2XC', NULL, NULL, 'Matrix', '2003-01-01'),
(19, 'test1@gmail.com', '$2y$10$8xoxKMF0PYOnAD1r2ymSpuHz/xui/8nyvwamrEg5coAX6Jn5PqooC', NULL, NULL, 'Atik', NULL),
(20, 'test2@gmail.com', '$2y$10$4M9u1vhWYQVlXudQmhaw.uZZxkPrIK.3447lqOa2ujf/7PdMlkpiG', NULL, NULL, 'Jamil', NULL),
(21, 'test3@gmail.com', '$2y$10$ZkLwuLPjDtDO.i61HNZldeBk8r22UM/6nHSPYHyf7BLtxttd6Lb7W', NULL, NULL, 'Zidan', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `my_games`
--

CREATE TABLE `my_games` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `purchased_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `my_games`
--

INSERT INTO `my_games` (`id`, `user_id`, `game_id`, `purchased_at`) VALUES
(21, 18, 4, '2025-04-26 20:01:10'),
(22, 18, 2, '2025-04-26 21:15:41'),
(23, 18, 7, '2025-04-26 22:10:38'),
(24, 19, 2, '2025-05-19 00:07:10'),
(25, 19, 4, '2025-05-19 00:07:11'),
(26, 19, 7, '2025-05-19 00:07:11'),
(27, 20, 2, '2025-05-19 00:15:13'),
(28, 20, 4, '2025-05-19 00:15:13'),
(29, 20, 7, '2025-05-19 00:15:13'),
(30, 21, 2, '2025-05-19 00:19:54'),
(31, 21, 4, '2025-05-19 00:19:54'),
(32, 18, 20, '2025-05-31 21:52:57'),
(33, 18, 3, '2025-05-31 21:54:26');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'completed',
  `ordered_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `game_id`, `price`, `status`, `ordered_at`) VALUES
(49, 18, 4, 0.00, 'completed', '2025-04-26 20:01:09'),
(50, 18, 2, 0.00, 'completed', '2025-04-26 21:15:41'),
(51, 18, 7, 0.00, 'completed', '2025-04-26 22:10:38'),
(52, 19, 2, 0.00, 'completed', '2025-05-19 00:07:10'),
(53, 19, 4, 0.00, 'completed', '2025-05-19 00:07:10'),
(54, 19, 7, 0.00, 'completed', '2025-05-19 00:07:11'),
(55, 20, 2, 0.00, 'completed', '2025-05-19 00:15:13'),
(56, 20, 4, 0.00, 'completed', '2025-05-19 00:15:13'),
(57, 20, 7, 0.00, 'completed', '2025-05-19 00:15:13'),
(58, 21, 2, 0.00, 'completed', '2025-05-19 00:19:54'),
(59, 21, 4, 0.00, 'completed', '2025-05-19 00:19:54'),
(60, 18, 20, 0.00, 'completed', '2025-05-31 21:52:57'),
(61, 18, 3, 0.00, 'completed', '2025-05-31 21:54:26');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `game_id`, `rating`, `comment`, `created_at`) VALUES
(2, 18, 7, 3, 'Good Game....', '2025-05-16 22:24:00'),
(5, 18, 4, 2, 'nice game.', '2025-05-16 22:57:25'),
(6, 18, 2, 2, 'Great game from Electronic Arts!', '2025-05-16 23:11:54'),
(7, 19, 2, 4, 'Optimization is on upper level.', '2025-05-19 00:08:05'),
(8, 19, 4, 3, 'Such a great zombie game till now.', '2025-05-19 00:08:31'),
(9, 19, 7, 5, 'This season battle pass is fully worthable.', '2025-05-19 00:09:38'),
(10, 20, 2, 1, 'Getting Worst FPS!', '2025-05-19 00:15:49'),
(11, 20, 4, 2, 'Second installment is more fun.', '2025-05-19 00:16:35'),
(12, 20, 7, 4, 'Game graphics is getting higher day by day.', '2025-05-19 00:17:26'),
(13, 21, 2, 1, 'Worst game! Worst Graphics...', '2025-05-19 00:20:15'),
(14, 21, 4, 3, 'Good Game for lower end user.', '2025-05-19 00:20:39'),
(16, 18, 3, 3, 'Good', '2025-05-31 21:58:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_info`
--
ALTER TABLE `admin_info`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`game_id`);

--
-- Indexes for table `login_info`
--
ALTER TABLE `login_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_games`
--
ALTER TABLE `my_games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_game` (`user_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_order` (`user_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `unique_review` (`user_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_info`
--
ALTER TABLE `admin_info`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `login_info`
--
ALTER TABLE `login_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `my_games`
--
ALTER TABLE `my_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login_info` (`id`);

--
-- Constraints for table `downloads`
--
ALTER TABLE `downloads`
  ADD CONSTRAINT `downloads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login_info` (`id`),
  ADD CONSTRAINT `downloads_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`);

--
-- Constraints for table `my_games`
--
ALTER TABLE `my_games`
  ADD CONSTRAINT `my_games_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login_info` (`id`),
  ADD CONSTRAINT `my_games_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login_info` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login_info` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
