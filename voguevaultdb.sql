-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2024 at 11:55 PM
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
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(3, 3, 6, 1, '2024-08-24 16:33:31', '2024-08-24 16:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `status`, `created_at`, `shipping_address`, `payment_method`) VALUES
(2, 1, 5, 4, 16396.00, 'pending', '2024-08-23 15:54:04', NULL, NULL),
(3, 1, 6, 1, 13440.00, 'pending', '2024-08-23 16:49:50', NULL, NULL),
(4, 1, 7, 10, 4990.00, 'pending', '2024-08-24 17:09:09', NULL, NULL),
(5, 1, 6, 1, 13440.00, 'pending', '2024-08-24 20:25:38', NULL, NULL),
(6, 1, 5, 1, 4099.00, 'pending', '2024-08-24 21:02:46', NULL, NULL),
(7, 1, 6, 3, 40320.00, 'pending', '2024-08-24 21:24:34', NULL, NULL),
(8, 1, 10, 3, 18147.00, 'pending', '2024-08-24 21:24:42', NULL, NULL),
(9, 1, 6, 1, 13440.00, 'pending', '2024-08-24 21:25:38', 'Diyan lang sa may tabi', 'cash_on_delivery');

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
  `category` enum('Men''s','Women''s','Kid''s') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` float DEFAULT 0,
  `rating_count` int(11) DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `category`, `price`, `image`, `created_at`, `rating`, `rating_count`, `stock`) VALUES
(3, '(Men\'s) Modular Jacket', 'Carhartt Modular Jacket', 'Men\'s', 6199.00, '../uploads/men-1modularjacket.png', '2024-08-23 10:01:18', 4.8, 5, 100),
(4, '(Men\'s) Cargo Pants', 'Uniqlo Cargo Pants', 'Men\'s', 1990.00, '../uploads/men-2pants.png', '2024-08-23 10:01:52', 4.75, 4, 100),
(5, '(Men\'s) Converse', 'Converse Chuck \'70s', 'Men\'s', 4099.00, '../uploads/men-3shoes.jpg', '2024-08-23 10:03:22', 5, 4, 95),
(6, '(Men\'s) Aviator Sunglasses', 'Ray-Ban Polarized Metal Aviator Sunglasses', 'Men\'s', 13440.00, '../uploads/men-4rayban.png', '2024-08-23 10:04:55', 4.78571, 14, 95),
(7, '(Women\'s) Cap-Sleeve Tshirt', 'Uniqlo Cap-Sleeve Tshirt', 'Women\'s', 499.00, '../uploads/woman-1shirt.png', '2024-08-23 10:08:09', 4.66667, 6, 90),
(8, '(Women\'s) Oversized Japanese Style Cardigan', 'Uniqlo Oversized Japanese Style Cardigan', 'Women\'s', 899.00, '../uploads/woman-2cardigan.png', '2024-08-23 10:10:09', 5, 2, 100),
(9, '(Women\'s) Wide-Leg Trouser', 'Uniqlo Wide-Leg Trouser', 'Women\'s', 799.00, '../uploads/woman-3trouser.png', '2024-08-23 10:12:30', 4.75, 8, 100),
(10, '(Women\'s) Puma', 'Puma Palermo Leather Shoes', 'Women\'s', 6049.00, '../uploads/woman-4shoes.png', '2024-08-23 10:15:50', 5, 2, 97),
(11, '(Kid\'s) School Collection', 'Back-To-School Collection for kids', 'Kid\'s', 4599.00, '../uploads/kid-01.jpg', '2024-08-23 10:25:26', 0, 0, 100),
(12, '(Kid\'s) Summer Collection', 'Summer Outfit for kids', 'Kid\'s', 2799.00, '../uploads/kid-02.jpg', '2024-08-23 10:32:46', 0, 0, 100),
(13, '(Kid\'s) Casual Collection', 'Casual Collection for kids', 'Kid\'s', 7399.00, '../uploads/kid-03.jpg', '2024-08-23 10:37:53', 0, 0, 100),
(14, '(Kid\'s) Winter Collection', 'Winter Outfit for kids', 'Kid\'s', 6399.00, '../uploads/kid-4.png', '2024-08-23 10:39:44', 0, 0, 100);

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
  `gender` enum('male','female','other') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `gender`, `birthday`, `created_at`, `reset_token`) VALUES
(1, 'testuser1', '$2y$10$bp.moFEisbTkVUDFoEV/YekwEuxOVJ69fIJ62L5pdndXc3Z90QJWm', 'testuser1@example.com', 'customer', 'male', '2003-12-07', '2024-08-23 08:55:28', '78cb8a3536851e223fb1fe496e16787277ead06361e040247eef36779eaa532c62df1aa0b618ab57c575b7b16745f19c5f19'),
(2, 'admin1', '$2y$10$hRChidKpexOrdui/zLWSk.3ST9GIbtj2JCLSJThbC5FXspfnaZcqq', 'admin1@example.com', 'admin', NULL, NULL, '2024-08-23 09:48:31', NULL),
(3, 'Rence', '$2y$10$dVlli./KQW7MeFn42nBZeuTHV6bHQETw8kR.d04zb1sfjho/QOFoW', 'rence@gmail.com', 'customer', 'other', '2003-04-17', '2024-08-24 16:28:56', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

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
