-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2024 at 01:14 AM
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
-- Database: `voguevaultdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`, `category`) VALUES
(8, 'skinny jeans', 'abilijin', 350.00, '/assets/images/men2-pants.png', '2024-08-16 10:55:16', 'Men\'s'),
(9, 'trouser', 'estetik', 499.00, '/assets/images/men2-pants.png', '2024-08-16 10:55:16', 'Men\'s');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'customer',
  `gender` enum('male','female','other') NOT NULL,
  `birthday` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `gender`, `birthday`, `created_at`, `reset_token`) VALUES
(1, 'testuser1', '$2y$10$eI.MdsR00N4l6fYir2Vj3ehP2PbwU23N5iU/e/ZEkfwyMMM4BESYy', 'testuser1@example.com', 'customer', 'male', '2003-12-07', '2024-08-09 08:22:55', '6f11cd98334837ac71e2d96b12705885376f9f48916c1061dbbadeaae47bc939759da70aedaaa6b27b4ccee6edb6ccae7be3'),
(3, 'testuser2', '$2y$10$O8tj3UiJPwT6tRigBxNyWOfh66ZIdsuHdxAjVOa7KKeQ3hAynXWQO', 'testuser2@example.com', 'customer', 'male', '2004-05-15', '2024-08-09 08:54:44', NULL),
(4, 'testuser3', '$2y$10$6BxtQij9dAlsUDgaIlKQlOF41BaUcbMgnIBG07G5lQE7lGBcMzl32', 'testuser3@example.com', 'customer', 'male', '2008-03-15', '2024-08-09 09:01:50', NULL),
(5, 'testuser4', '$2y$10$nUaNh3okwqw9rcnVzcaDM.5TbpTBm0jruwxqERgI8pIfV6GZdnJXy', 'testuser4@example.com', 'customer', 'other', '2005-12-11', '2024-08-09 09:03:11', NULL),
(6, 'testuser5', '$2y$10$5OR/ojdJuLGiWnnBHks97.2gopVYbTCW/vCzMKCyvfx8pP8BvqZ6S', 'testuser5@example.com', 'customer', 'male', '2008-03-15', '2024-08-09 09:10:05', NULL),
(7, 'admin1', '$2y$10$KRkJe.8EkxruBWFZvFQWY.TynZuJad/joBwfw0q36ujMj6Kwkk.1u', 'admin1@example.com', 'admin', 'male', '2003-12-07', '2024-08-12 12:11:55', NULL),
(8, 'testuser6', '$2y$10$cRvfwf9CV29VSuXmiuMwoul1vIsUCaBeafjhlyw5NeLHm9drNFi2O', 'testuser6@example.com', 'customer', 'other', '1111-11-11', '2024-08-14 01:16:17', NULL),
(9, 'jrcs', '$2y$10$ZFDcRhKvppkf/Ac/z3YFguJ1MmlKHBWxhXuPpVzv7KpUBBUL6Vagm', 'je@gmail.com', 'customer', 'female', '2002-11-24', '2024-08-15 00:45:23', NULL),
(10, 'testuser7', '$2y$10$wSipxxCYp3ICyf6zD6ouOOqm9QnrND89AQqWoZlNUmfJKZMqzS0iK', 'testuser7@example.com', 'customer', 'male', '2000-11-11', '2024-08-15 00:55:04', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
