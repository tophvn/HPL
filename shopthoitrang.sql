-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 29, 2024 at 01:36 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopthoitrang`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int NOT NULL,
  `brand_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`) VALUES
(1, 'Louis Vuitton'),
(2, 'Gucci'),
(3, 'Chanel'),
(4, 'Coach'),
(5, 'Dior');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--
CREATE TABLE `cart` (
  `cart_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  PRIMARY KEY (`cart_id`),
  FOREIGN KEY (`user_id`) REFERENCES users(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cart_item` (
  `cart_item_id` INT NOT NULL AUTO_INCREMENT,
  `cart_id` INT DEFAULT NULL,
  `product_id` INT DEFAULT NULL,
  `quantity` INT DEFAULT NULL,
  `color` VARCHAR(50),
  PRIMARY KEY (`cart_item_id`),
  FOREIGN KEY (`cart_id`) REFERENCES cart(`cart_id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES products(`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category_image` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_image`) VALUES
(1, 'Áo', ''),
(2, 'Quần', ''),
(3, 'Phụ Kiện', ''),
(4, 'Áo Khoác', ''),
(5, 'Thắt Lưng', ''),
(6, 'Giày', '');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `comment_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `time_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `status_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `order_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_order` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_detail_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `order_quantity` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `size` enum('XS','S','M','L','XL') COLLATE utf8mb4_general_ci DEFAULT 'M'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `category_id`, `image`, `image2`, `image3`, `description`, `quantity`, `size`) VALUES
(1, 'Áo Polo Nam', 2999, 1, 'image1_1.png', 'image1_2.png', 'image1_3.png', '0', 100, 'M'),
(2, 'Quần Jeans Nam', 3999, 2, 'image2_1.jpg', 'image2_2.png', 'image2_3.png', 'Quần Jeans Nam có kiểu dáng hiện đại, chất liệu thoáng khí và thoải mái, là sự lựa chọn hoàn hảo cho phong cách cá nhân.', 80, 'M'),
(3, 'Giày Sneakers nike', 4999, 3, 'image3_1.jpg', 'image3_2.jpg', 'image3_3.jpg', 'Giày Sneakers với thiết kế đẹp mắt, êm ái và bền bỉ, là sự kết hợp hoàn hảo giữa thời trang và thoải mái.', 120, 'M'),
(4, 'Áo Khoác Đông', 5999, 4, 'image4_1.png', 'image4_2.png', 'image4_3.png', 'Áo Khoác Đông chống nước và giữ ấm tốt, là sự lựa chọn tuyệt vời cho những ngày lạnh giá.', 60, 'M'),
(5, 'Áo Polo GolfBaudi Nam', 4599, 1, 'image5_1.png', 'image5_2.png', 'image5_3.png', 'Áo Polo Golf Nam với chất liệu chống nắng, phù hợp cho mọi sân golf.', 90, 'M'),
(6, 'Quần ba tư Nike Nam', 3599, 2, 'image6_1.jpg', 'image6_2.jpg', 'image6_3.png', 'Quần Linen Nam với chất liệu nhẹ và thoáng khí, là lựa chọn tuyệt vời cho mùa hè.', 100, 'M'),
(7, 'Giày Mules Nike 2 dây', 4999, 3, 'image7_1.jpg', 'image7_2.jpg', 'image7_3.jpg', 'Dép Mules với thiết kế đơn giản, thoải mái cho những bước đi dạo phố.', 120, 'M'),
(8, 'Áo Khoác Puffer blue water', 5999, 4, 'image8_1.png', 'image8_2.png', 'image8_3.png', 'Áo Khoác Puffer với lớp đệm ấm áp, giữ ấm hiệu quả trong mùa đông.', 80, 'M'),
(9, 'Áo Hoodie Nữ', 2599, 5, 'image9_1.png', 'image9_2.png', 'image9_3.png', 'Áo Hoodie Nữ với thiết kế thoải mái và ấm áp, phù hợp cho mùa đông.', 80, 'M'),
(10, 'Quần Legging Nữ', 1799, 6, 'image10_1.jpg', 'image10_2.jpg', 'image10_3.jpg', 'Quần Legging Nữ với chất liệu co giãn, ôm sát cơ thể và thoải mái cho hoạt động thể thao.', 120, 'M'),
(11, 'Giày thắp Gót', 5999, 3, 'image11_1.jpg', 'image11_2.jpg', 'image11_3.jpg', 'Giày Cao Gót với kiểu dáng sang trọng, phù hợp cho những dịp đặc biệt.', 50, 'M'),
(12, 'Áo Thun Nam', 1899, 1, 'image12_1.png', 'image12_2.png', 'image12_3.png', 'Áo Thun Nam kiểu dáng đơn giản, phù hợp cho mọi hoạt động hàng ngày.', 150, 'M'),
(13, 'Quần Jogger Nam', 2499, 2, 'image13_1.jpg', 'image13_2.jpg', 'image13_3.jpg', 'Quần Jogger Nam với phong cách thể thao và thoải mái, là sự lựa chọn tuyệt vời cho mọi dịp.', 100, 'M'),
(14, 'Giày Bốt Đinh Tán', 6999, 3, 'image14_1.jpg', 'image14_2.jpg', 'image14_3.jpg', 'Giày Bốt Đinh Tán với kiểu dáng cá tính, phù hợp cho những ngày lạnh.', 70, 'M'),
(15, 'Áo Sơ Mi Nữ', 3299, 5, 'image15_1.png', 'image15_2.png', 'image15_3.png', 'Áo Sơ Mi Nữ với thiết kế thanh lịch, phù hợp cho công việc và các sự kiện quan trọng.', 90, 'M'),
(16, 'Quần Charlotes Nữ', 2199, 6, 'image16_1.jpg', 'image16_2.jpg', 'image16_3.jpg', 'Quần Charlotes Nữ với kiểu dáng rộng rãi và thoải mái, là sự lựa chọn thời trang cho mùa hè.', 110, 'M'),
(17, 'Quần Linen Nam', 3599, 2, 'image17_1.jpg', 'image17_2.jpg', 'image17_3.jpg', 'Quần Linen Nam với chất liệu nhẹ và thoáng khí, là lựa chọn tuyệt vời cho mùa hè.', 100, 'M'),
(18, 'Giày Mules', 4999, 3, 'image18_1.jpg', 'image18_2.jpg', 'image18_3.jpg', 'Dép Mules với thiết kế đơn giản, thoải mái cho những bước đi dạo phố.', 120, 'M'),
(19, 'Áo Khoác Puffer', 5999, 4, 'image19_1.png', 'image19_2.png', 'image19_3.png', 'Áo Khoác Puffer với lớp đệm ấm áp, giữ ấm hiệu quả trong mùa đông.', 80, 'M');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int NOT NULL,
  `status_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_value`) VALUES
(1, 'Đã đặt hàng'),
(2, 'Đang xử lý'),
(3, 'Đã giao hàng'),
(4, 'Đã hủy');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phonenumber` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `roles` enum('admin','user') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `phonenumber`, `name`, `email`, `roles`) VALUES
(29, 'hoanghai', '202cb962ac59075b964b07152d234b70', '0793897147', 'Hoàng Hải', '123@gmail.com', 'user'),
(30, 'hoang1', '202cb962ac59075b964b07152d234b70', '0793897147', 'vua trò chơi ', 'hoanghaii1710@gmail.com', 'user'),
(31, 'hoàng', '202cb962ac59075b964b07152d234b70', '123', 'Hoàng Hải', 'hoanghaimcpe@gmail.comg', 'user'),
(32, 'hoang12', '202cb962ac59075b964b07152d234b70', '0330330333', 'hai', 'khongbiet@gmail.deptrai', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cart_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=567;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `order_detail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
