-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2025 at 11:40 AM
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
-- Database: `khana_khazana`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`) VALUES
(1, 'Harsh', 'admin@gmail.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `menu_item_id`, `quantity`, `created_at`) VALUES
(60, 5, 2, 1, '2025-07-30 08:55:05'),
(65, 8, 9, 1, '2025-08-01 05:59:44'),
(66, 3, 19, 1, '2025-08-01 14:49:09'),
(102, 6, 2, 1, '2025-08-02 07:00:20'),
(112, 1, 2, 1, '2025-08-03 08:46:21');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `stock_qty` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `category`, `stock_qty`, `image`, `available`) VALUES
(1, 'Paneer Butter Masala', 'Soft paneer in creamy tomato gravy', 180.00, 'Veg', 20, 'paneer.jpg', 1),
(2, 'Chicken Biryani', 'Spicy basmati rice with chicken', 220.00, 'Non-Veg', 15, 'biryani.jpg', 1),
(3, 'Veg Chowmein', 'Stir-fried noodles with vegetables', 110.00, 'Veg', 30, 'chowmin.jpg', 1),
(4, 'Gulab Jamun', 'Soft sweet balls in syrup', 50.00, 'Veg', 50, 'gulabjamun.jpg', 1),
(5, 'Tandoori Roti', 'Whole wheat tandoor roti', 15.00, 'Veg', 100, 'tandooriroti.jpg', 1),
(6, 'Cold Drink', 'Chilled soft drink (250ml)', 35.00, 'Veg', 40, 'cold_drink.jpg', 1),
(7, 'Paneer Tikka', 'Grilled paneer cubes marinated in spices', 120.00, 'Veg', 25, 'paneer_tikka.jpg', 1),
(8, 'Chicken Korma', 'Creamy chicken curry with rich spices', 205.00, 'Non-Veg', 21, 'chiken_korma.jpg', 1),
(9, 'Veg Pulao', 'Fragrant rice cooked with vegetables', 90.00, 'Veg', 30, 'veg_pulao.jpg', 1),
(10, 'Chicken  Tikka', 'Spicy deep-fried chicken appetizer', 190.00, 'Non-Veg', 18, 'chiken_tikka.jpg', 1),
(11, 'Palak Paneer', 'Spinach gravy with soft paneer cubes', 150.00, 'Veg', 22, 'palak_paneer.jpg', 1),
(12, 'Mutton Rogan Josh', 'Kashmiri-style mutton curry', 410.00, 'Non-Veg', 2, 'mutton.jpg', 1),
(13, 'Aloo Gobi', 'Potato and cauliflower dry curry', 60.00, 'Veg', 20, 'gobi.jpg', 1),
(14, 'Fish Curry', 'Fish cooked in tangy tomato gravy', 199.00, 'Non-Veg', 16, 'fish_curry.jpg', 1),
(15, 'Mix Veg', 'Assorted vegetables cooked in Indian spices', 69.00, 'Veg', 35, 'mix_veg.jpg', 1),
(16, 'Chicken Do Pyaza', 'Chicken cooked with lots of onions', 320.00, 'Non-Veg', 14, 'Chicken-do-pyaza.jpg', 1),
(17, 'Shahi Paneer', 'Rich and creamy paneer curry with nuts', 160.00, 'Veg', 20, 'shahi_paneer.jpg', 1),
(18, 'Chicken Fried Rice', 'Stir-fried rice with chicken and sauces', 130.00, 'Non-Veg', 25, 'chiken_fried_rice.jpg', 1),
(19, 'Aloo Samosa', 'Golden-fried pastry stuffed with spiced potatoes and peas.', 15.00, 'Veg', 30, '1753955663_samosa.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','preparing','dispatched','delivered','cancelled') DEFAULT 'pending',
  `delivery_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `delivery_address`, `created_at`) VALUES
(7, 1, 299.50, 'delivered', '456, MG Road, Lucknow', '2025-07-26 05:37:00'),
(8, 1, 899.00, 'delivered', '789, PGI, Noida', '2025-07-26 05:37:00'),
(45, 9, 400.00, 'delivered', 'Jaipur, Rajashtan', '2025-07-30 08:49:07'),
(48, 1, 35.00, 'delivered', 'Lucknow', '2025-07-31 03:30:35'),
(49, 8, 69.00, 'pending', 'Noida , Uttarpradesh', '2025-08-01 05:58:35'),
(50, 1, 180.00, 'pending', 'Lucknow', '2025-08-01 15:30:27'),
(51, 1, 400.00, 'preparing', 'Lucknow', '2025-08-01 15:31:33'),
(52, 1, 1160.00, 'dispatched', 'Lucknow', '2025-08-01 16:20:52'),
(53, 1, 620.00, 'delivered', 'Lucknow', '2025-08-02 04:07:28'),
(54, 1, 430.00, 'delivered', 'Lucknow', '2025-08-02 06:11:47'),
(55, 1, 50.00, 'delivered', 'Lucknow', '2025-08-03 05:14:08'),
(56, 1, 220.00, 'pending', 'Lucknow', '2025-08-03 07:21:18');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_item_id`, `quantity`, `price`) VALUES
(9, 7, 1, 2, 149.75),
(10, 7, 2, 1, 299.50),
(11, 8, 3, 3, 899.00),
(62, 45, 1, 1, 180.00),
(63, 45, 2, 1, 220.00),
(66, 48, 6, 1, 35.00),
(67, 49, 15, 1, 69.00),
(68, 50, 1, 1, 180.00),
(69, 51, 2, 1, 220.00),
(70, 51, 1, 1, 180.00),
(71, 52, 1, 1, 180.00),
(72, 52, 2, 2, 220.00),
(73, 52, 10, 1, 190.00),
(74, 52, 3, 2, 110.00),
(75, 52, 18, 1, 130.00),
(76, 53, 2, 2, 220.00),
(77, 53, 1, 1, 180.00),
(78, 54, 6, 1, 35.00),
(79, 54, 2, 1, 220.00),
(80, 54, 3, 1, 110.00),
(81, 54, 4, 1, 50.00),
(82, 54, 5, 1, 15.00),
(83, 55, 4, 1, 50.00),
(84, 56, 2, 1, 220.00);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `user_id`, `menu_item_id`, `rating`, `created_at`) VALUES
(5, 1, 6, 2, '2025-08-03 05:55:29'),
(6, 1, 2, 2, '2025-08-03 05:55:52'),
(7, 1, 3, 3, '2025-08-03 06:25:23'),
(8, 1, 3, 3, '2025-08-03 06:25:23'),
(9, 1, 1, 5, '2025-08-03 06:29:05'),
(10, 1, 1, 2, '2025-08-03 06:29:05'),
(11, 1, 1, 3, '2025-08-03 06:29:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `created_at`) VALUES
(1, 'Prashant Kumar', 'pk@gmail.com', '12345678', '9693625814', 'Lucknow', '2025-07-26 05:05:13'),
(2, 'raj', 'raj@abc.com', '123', '9693625814', 'Lucknow', '2025-07-26 08:47:44'),
(3, 'Ram ', 'rk@gmail.com', '123', '9998936416', 'MOHANLALGANJ', '2025-07-26 09:13:01'),
(4, 'John', 'john@abc.com', '123', '9456789234', 'pune', '2025-07-26 10:00:34'),
(5, 'Shyam', 'sk@abc.com', '123', '8456789234', 'Delhi', '2025-07-26 10:04:27'),
(6, 'Nitin', 'nk@gmail.com', '123', '9456789934', 'Goa', '2025-07-26 10:15:21'),
(7, 'Noor', 'noor@gmail.com', '123', '9456789937', 'Pune, Maharastra', '2025-07-28 11:40:07'),
(8, 'Rohit', 'rohit@gmail.com', '123', '6456789234', 'Noida , Uttarpradesh', '2025-07-28 17:39:08'),
(9, 'Adarsh Tiwari', 'adarsh@gmail.com', '123', '9456789238', 'Jaipur, Rajashtan', '2025-07-28 17:42:48'),
(10, 'Prateek Kumar', 'prateek@gmail.com', '123', '9456789245', 'Patna, Bihar', '2025-07-29 04:25:34'),
(11, 'Nishant Kumar ', 'nishant@gmail.com', '123', '9856362598', 'Jankipuram, Lucknow ', '2025-07-29 12:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_items`
--

CREATE TABLE `wishlist_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist_items`
--

INSERT INTO `wishlist_items` (`id`, `user_id`, `menu_item_id`, `created_at`) VALUES
(60, 5, 2, '2025-07-30 08:55:05'),
(65, 8, 9, '2025-08-01 05:59:44'),
(66, 3, 19, '2025-08-01 14:49:09'),
(102, 6, 2, '2025-08-02 07:00:20'),
(121, 1, 3, '2025-08-03 08:46:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
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
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_item_id` (`menu_item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
