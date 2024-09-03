-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2024 at 02:18 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'testuser2', 'testuser2@example.com', 'ahaha', '2024-08-28 12:20:20');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
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
(9, 1, 6, 1, 13440.00, 'pending', '2024-08-24 21:25:38', 'Diyan lang sa may tabi', 'cash_on_delivery'),
(10, 1, 6, 1, 13440.00, 'pending', '2024-08-25 19:48:36', NULL, NULL),
(11, 1, 10, 1, 6049.00, 'pending', '2024-08-25 19:55:51', NULL, NULL),
(12, 1, 10, 1, 6049.00, 'pending', '2024-08-25 19:55:51', NULL, NULL),
(13, 1, 14, 1, 6399.00, 'pending', '2024-08-25 19:56:16', NULL, NULL),
(14, 1, 9, 1, 799.00, 'pending', '2024-08-25 20:34:32', NULL, NULL),
(15, 1, 5, 1, 4099.00, 'pending', '2024-08-25 20:35:08', NULL, NULL),
(16, 1, 13, 1, 7399.00, 'pending', '2024-08-25 20:35:17', NULL, NULL),
(17, 1, 10, 1, 6049.00, 'pending', '2024-08-25 21:07:55', NULL, NULL),
(18, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:37', NULL, NULL),
(19, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:39', NULL, NULL),
(20, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:39', NULL, NULL),
(21, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:40', NULL, NULL),
(22, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:40', NULL, NULL),
(23, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:40', NULL, NULL),
(24, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:40', NULL, NULL),
(25, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:40', NULL, NULL),
(26, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:40', NULL, NULL),
(27, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:41', NULL, NULL),
(28, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:41', NULL, NULL),
(29, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:41', NULL, NULL),
(30, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:41', NULL, NULL),
(31, 1, 5, 1, 4099.00, 'pending', '2024-08-25 21:20:41', NULL, NULL),
(32, 1, 9, 1, 799.00, 'pending', '2024-08-25 21:20:55', NULL, NULL),
(33, 1, 9, 1, 799.00, 'pending', '2024-08-25 21:20:56', NULL, NULL),
(34, 1, 9, 1, 799.00, 'pending', '2024-08-25 21:20:56', NULL, NULL),
(35, 1, 9, 1, 799.00, 'pending', '2024-08-25 21:20:56', NULL, NULL),
(36, 1, 9, 1, 799.00, 'pending', '2024-08-25 21:20:56', NULL, NULL),
(37, 1, 9, 1, 799.00, 'pending', '2024-08-25 21:20:56', NULL, NULL),
(38, 1, 9, 1, 799.00, 'pending', '2024-08-25 21:20:58', NULL, NULL),
(39, 1, 9, 1, 799.00, 'pending', '2024-08-25 21:20:58', NULL, NULL),
(40, 1, 9, 1, 799.00, 'pending', '2024-08-25 21:20:58', NULL, NULL),
(41, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:17:57', NULL, NULL),
(42, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:17:58', NULL, NULL),
(43, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:17:58', NULL, NULL),
(44, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:17:59', NULL, NULL),
(45, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:18:00', NULL, NULL),
(46, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:18:00', NULL, NULL),
(47, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:18:00', NULL, NULL),
(48, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:18:00', NULL, NULL),
(49, 1, 9, 1, 799.00, 'pending', '2024-08-26 02:18:17', NULL, NULL),
(50, 1, 9, 1, 799.00, 'pending', '2024-08-26 02:18:17', NULL, NULL),
(51, 1, 9, 1, 799.00, 'pending', '2024-08-26 02:18:17', NULL, NULL),
(52, 1, 14, 1, 6399.00, 'pending', '2024-08-26 02:18:29', NULL, NULL),
(53, 1, 14, 1, 6399.00, 'pending', '2024-08-26 02:18:29', NULL, NULL),
(54, 1, 10, 1, 6049.00, 'pending', '2024-08-26 02:18:57', NULL, NULL),
(55, 1, 10, 1, 6049.00, 'pending', '2024-08-26 02:18:58', NULL, NULL),
(56, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:30:27', 'dyan lang sa tabi', 'paypal'),
(57, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:30:56', NULL, NULL),
(58, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:30:57', NULL, NULL),
(59, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:30:57', NULL, NULL),
(60, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:30:58', NULL, NULL),
(61, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:30:58', NULL, NULL),
(62, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:30:58', NULL, NULL),
(63, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:30:59', NULL, NULL),
(64, 1, 6, 1, 13440.00, 'pending', '2024-08-26 02:30:59', NULL, NULL),
(65, 1, 8, 1, 899.00, 'pending', '2024-08-26 02:32:03', NULL, NULL),
(66, 1, 8, 1, 899.00, 'pending', '2024-08-26 02:32:04', NULL, NULL),
(67, 1, 8, 1, 899.00, 'pending', '2024-08-26 02:32:04', NULL, NULL),
(68, 1, 8, 1, 899.00, 'pending', '2024-08-26 02:32:04', NULL, NULL),
(69, 1, 8, 1, 899.00, 'pending', '2024-08-26 02:32:05', NULL, NULL),
(70, 1, 8, 1, 899.00, 'pending', '2024-08-26 02:32:06', NULL, NULL),
(71, 1, 5, 1, 4099.00, 'pending', '2024-08-26 02:37:46', NULL, NULL),
(72, 1, 5, 1, 4099.00, 'pending', '2024-08-26 02:37:47', NULL, NULL),
(73, 1, 5, 1, 4099.00, 'pending', '2024-08-26 02:37:47', NULL, NULL),
(74, 1, 5, 1, 4099.00, 'pending', '2024-08-26 02:39:07', NULL, NULL),
(75, 1, 5, 1, 4099.00, 'pending', '2024-08-26 02:39:08', NULL, NULL),
(76, 1, 5, 1, 4099.00, 'pending', '2024-08-26 02:39:08', NULL, NULL),
(77, 1, 10, 1, 6049.00, 'pending', '2024-08-26 02:42:59', NULL, NULL),
(78, 1, 6, 4, 53760.00, 'pending', '2024-08-26 03:04:15', 'dyan lang ulit', 'cash_on_delivery'),
(79, 1, 10, 3, 18147.00, 'pending', '2024-08-26 03:04:15', 'dyan lang ulit', 'cash_on_delivery'),
(80, 1, 13, 2, 14798.00, 'pending', '2024-08-26 03:04:15', 'dyan lang ulit', 'cash_on_delivery'),
(81, 1, 8, 2, 1798.00, 'pending', '2024-08-26 03:04:15', 'dyan lang ulit', 'cash_on_delivery'),
(82, 1, 5, 1, 4099.00, 'pending', '2024-08-26 03:04:15', 'dyan lang ulit', 'cash_on_delivery'),
(83, 1, 14, 1, 6399.00, 'pending', '2024-08-26 03:04:15', 'dyan lang ulit', 'cash_on_delivery'),
(84, 1, 10, 1, 6049.00, 'pending', '2024-08-26 03:15:42', 'dyan lang ulit', 'cash_on_delivery'),
(85, 1, 9, 1, 799.00, 'pending', '2024-08-26 03:15:42', 'dyan lang ulit', 'cash_on_delivery'),
(86, 1, 3, 1, 6199.00, 'pending', '2024-08-26 03:41:37', 'Dyan lang sa may tabi', 'cash_on_delivery'),
(87, 1, 10, 5, 30245.00, 'pending', '2024-08-26 03:57:35', 'Sa may tabi', 'credit_card'),
(88, 1, 11, 1, 4599.00, 'pending', '2024-08-26 03:57:35', 'Sa may tabi', 'credit_card'),
(89, 1, 8, 1, 899.00, 'pending', '2024-08-26 03:58:33', 'Dyan lang sa may tabi', 'cash_on_delivery'),
(90, 1, 9, 1, 799.00, 'pending', '2024-08-26 03:59:22', 'dyan lang', 'cash_on_delivery'),
(91, 1, 9, 1, 799.00, 'pending', '2024-08-26 04:00:01', 'dyan lang haha', 'cash_on_delivery'),
(92, 1, 3, 1, 6199.00, 'pending', '2024-08-26 04:04:18', 'dyan lang haha', 'cash_on_delivery'),
(93, 1, 9, 1, 799.00, 'pending', '2024-08-26 04:06:38', 'dyan lang haha', 'cash_on_delivery'),
(94, 1, 9, 1, 799.00, 'pending', '2024-08-26 04:06:47', 'dyan lang haha', 'cash_on_delivery'),
(95, 1, 9, 1, 799.00, 'pending', '2024-08-26 04:15:48', 'dyan lang haha', 'cash_on_delivery'),
(96, 1, 5, 1, 4099.00, 'pending', '2024-08-26 04:16:25', 'dyan lang haha', 'cash_on_delivery'),
(97, 1, 4, 1, 1990.00, 'pending', '2024-08-26 04:16:25', 'dyan lang haha', 'cash_on_delivery'),
(98, 1, 3, 1, 6199.00, 'pending', '2024-08-26 04:16:25', 'dyan lang haha', 'cash_on_delivery'),
(99, 1, 6, 1, 13440.00, 'pending', '2024-08-26 04:16:25', 'dyan lang haha', 'cash_on_delivery'),
(100, 4, 9, 2, 1598.00, 'pending', '2024-08-28 11:27:14', 'Diyan lang ulit sa may tabi', 'cash_on_delivery'),
(101, 4, 11, 1, 4599.00, 'pending', '2024-08-28 11:27:14', 'Diyan lang ulit sa may tabi', 'cash_on_delivery'),
(103, 1, 10, 1, 6049.00, 'pending', '2024-09-03 12:13:17', 'dyan lang haha', 'cash_on_delivery'),
(104, 1, 12, 1, 2799.00, 'pending', '2024-09-03 12:13:17', 'dyan lang haha', 'cash_on_delivery'),
(105, 1, 7, 1, 499.00, 'pending', '2024-09-03 12:13:17', 'dyan lang haha', 'cash_on_delivery'),
(106, 1, 11, 1, 4599.00, 'pending', '2024-09-03 12:13:17', 'dyan lang haha', 'cash_on_delivery');

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
(3, '(Men\'s) Modular Jacket', 'Carhartt Modular Jacket', 'Men\'s', 6199.00, '../uploads/men-1modularjacket.png', '2024-08-23 10:01:18', 4.8, 5, 97),
(4, '(Men\'s) Cargo Pants', 'Uniqlo Cargo Pants', 'Men\'s', 1990.00, '../uploads/men-2pants.png', '2024-08-23 10:01:52', 4.75, 4, 99),
(5, '(Men\'s) Converse', 'Converse Chuck \'70s', 'Men\'s', 4099.00, '../uploads/men-3shoes.jpg', '2024-08-23 10:03:22', 5, 4, 72),
(6, '(Men\'s) Aviator Sunglasses', 'Ray-Ban Polarized Metal Aviator Sunglasses', 'Men\'s', 13440.00, '../uploads/men-4rayban.png', '2024-08-23 10:04:55', 4.78571, 14, 72),
(7, '(Women\'s) Cap-Sleeve Tshirt', 'Uniqlo Cap-Sleeve Tshirt', 'Women\'s', 499.00, '../uploads/woman-1shirt.png', '2024-08-23 10:08:09', 4.66667, 6, 89),
(8, '(Women\'s) Oversized Japanese Style Cardigan', 'Uniqlo Oversized Japanese Style Cardigan', 'Women\'s', 899.00, '../uploads/woman-2cardigan.png', '2024-08-23 10:10:09', 5, 2, 91),
(9, '(Women\'s) Wide-Leg Trouser', 'Uniqlo Wide-Leg Trouser', 'Women\'s', 799.00, '../uploads/woman-3trouser.png', '2024-08-23 10:12:30', 4.75, 8, 79),
(10, '(Women\'s) Puma', 'Puma Palermo Leather Shoes', 'Women\'s', 6049.00, '../uploads/woman-4shoes.png', '2024-08-23 10:15:50', 5, 2, 81),
(11, '(Kid\'s) School Collection', 'Back-To-School Collection for kids', 'Kid\'s', 4599.00, '../uploads/kid-01.jpg', '2024-08-23 10:25:26', 0, 0, 97),
(12, '(Kid\'s) Summer Collection', 'Summer Outfit for kids', 'Kid\'s', 2799.00, '../uploads/kid-02.jpg', '2024-08-23 10:32:46', 0, 0, 99),
(13, '(Kid\'s) Casual Collection', 'Casual Collection for kids', 'Kid\'s', 7399.00, '../uploads/kid-03.jpg', '2024-08-23 10:37:53', 5, 2, 97),
(14, '(Kid\'s) Winter Collection', 'Winter Outfit for kids', 'Kid\'s', 6399.00, '../uploads/kid-4.png', '2024-08-23 10:39:44', 5, 2, 96);

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
  `reset_token` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `gender`, `birthday`, `created_at`, `reset_token`, `address`) VALUES
(1, 'testuser1', '$2y$10$o00lpTYhfdokiMQ5MKJW4un27mxOzu6iPUx5kCZajK31vZs5Hn4mO', 'testuser1@example.com', 'customer', 'male', '2003-12-07', '2024-08-23 08:55:28', NULL, 'dyan lang haha'),
(2, 'admin1', '$2y$10$hRChidKpexOrdui/zLWSk.3ST9GIbtj2JCLSJThbC5FXspfnaZcqq', 'admin1@example.com', 'admin', NULL, NULL, '2024-08-23 09:48:31', NULL, NULL),
(4, 'testuser2', '$2y$10$iRT0JvNjAVj6QLbr7pNmIeGS7tuQ6Nmp1kV4KSoCgLtLBo.NgfgwG', 'testuser2@example.com', 'customer', 'male', '2003-12-07', '2024-08-28 11:15:51', NULL, 'Diyan lang ulit sa may tabi');

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
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
