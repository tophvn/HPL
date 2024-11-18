-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2024 at 01:56 PM
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
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `created_at`) VALUES
(11, 29, '2024-11-18 13:03:08');

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `cart_item_id` int NOT NULL,
  `cart_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `size` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`cart_item_id`, `cart_id`, `product_id`, `quantity`, `size`, `color`, `price`) VALUES
(50, 11, 2, 3, '', '', '800000.00'),
(51, 11, 1, 2, '', '', '45000.00');

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
(1, 'SALE', 'c-1.jpg'),
(2, 'ĐỘC QUYỀN', 'c-2.jpg'),
(3, 'PHỤ KIỆN', 'c-3.jpg'),
(4, 'NỮ', 'c-4.jpg'),
(5, 'TRẺ EM', 'c-5.jpg'),
(6, 'NAM', 'c-6.jpg');

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

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `product_id`, `user_id`, `comment_text`, `time_create`) VALUES
(17, 2, 29, 'sản phẩm chất lượng', '2024-11-18 13:39:12'),
(18, 2, 29, 'ok', '2024-11-18 13:39:54'),
(19, 2, 29, 'good', '2024-11-18 13:40:47'),
(20, 2, 29, 'good', '2024-11-18 13:42:24'),
(21, 1, 29, '1', '2024-11-18 13:44:02'),
(22, 1, 29, 'good', '2024-11-18 13:49:54');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(37, 29, 1, '2024-11-16 07:50:50'),
(38, 29, 2, '2024-11-18 13:03:03');

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
  `payment_method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `user_id`, `status_id`, `order_date`, `order_address`, `payment_method`, `total_amount`, `shipping_method`) VALUES
(45, 29, 1, '2024-11-18 20:10:34', '61 Tân Hương, Tân Phú, TP.HCM', 'Thẻ nội địa NAPAS', '1695000.00', 'Express');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_detail_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `order_quantity` int DEFAULT NULL,
  `status_id` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`order_detail_id`, `order_id`, `product_id`, `order_quantity`, `status_id`) VALUES
(65, 45, 2, 2, 1),
(66, 45, 1, 1, 1);

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
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `discount` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `category_id`, `image`, `image2`, `image3`, `description`, `type`, `size`, `discount`) VALUES
(1, 'ÁO KHOÁC BOMBER MOTO STICKER VARSITY', 50000, 1, 'image1_1.png', 'image1_2.png', 'image1_3.png', 'Áo sweatshirt kiểu oversized boxy với thêu Boucle cổ điển của Boy London.\n\nTrọng lượng áo T-shirt 400GSM. Màu đen.\n\nChất liệu hoàn thiện mềm mại. Người mẫu cao 6ft và mặc size M.', NULL, 'L,M,X,S', 10),
(2, 'ÁO PHÔNG BOY RACER', 1000000, 6, 'image2_1.png', 'image2_2.png', 'image2_3.png', 'Áo T-shirt kiểu oversized boxy với logo/slogan lớn ở giữa.\n\nChất liệu hoàn thiện mềm mại.\n\nNgười mẫu cao 6ft và mặc size M.', NULL, 'L,S,M', 20),
(3, 'ÁO THUN MOTO STICKER BOMB', 80000, 3, 'image3_1.png', 'image3_2.png', 'image3_3.png', 'Áo T-shirt với đồ họa sticker bomb 360 độ. Trọng lượng áo T-shirt 200GSM. Chất liệu hoàn thiện mềm mại.', NULL, 'M,S,XS', 10),
(4, 'ÁO THUN STRENGTH', 200000, 4, 'image4_1.png', 'image4_2.png', 'image4_3.png', 'Áo T-shirt kiểu oversized boxy với in và thêu đồ họa ở phía trước.\n\nNhãn da ở viền dưới phía trước.\n\nTrọng lượng áo T-shirt 200GSM.\n\nChất liệu hoàn thiện mềm mại.', NULL, 'S,L,XL,XXL', 5),
(5, 'ÁO LEN TAY ĐÔI CHEEKY DEVIL', 365000, 1, 'image5_1.png', 'image5_2.png', 'image5_3.png', 'Áo Polo Golf Nam với chất liệu chống nắng, phù hợp cho mọi sân golf.', NULL, 'S,M,XL,XXL', 5),
(6, 'ÁO VARSITY DENIM MOTO', 450000, 2, 'image6_1.png', 'image6_2.png', 'image6_3.png', 'Áo varsity denim moto kết hợp phong cách thể thao với chất liệu denim, tạo nên vẻ ngoài năng động và cá tính.', NULL, 'XXL', 5),
(7, 'ÁO KHOÁC VARSITY BOMBER NỮ MOTO STICKER', 890000, 3, 'image7_1.png', 'image7_2.png', 'image7_3.png', 'Áo khoác varsity bomber nữ với họa tiết sticker moto, mang lại phong cách trẻ trung và năng động. Chất liệu thoải mái và thiết kế thời trang phù hợp cho nhiều dịp.', NULL, 'L, XL,M,S', 10),
(8, 'ÁO T-SHIRT NỮ FUTURE BOY CORE', 390000, 4, 'image8_1.png', 'image8_2.png', 'image8_3.png', 'Áo T-shirt nữ Future Boy Core với thiết kế hiện đại, mang lại phong cách trẻ trung và năng động. Chất liệu thoải mái, phù hợp cho cả những ngày thường và các hoạt động ngoài trời.', NULL, 'ONE SIZE', 10),
(9, 'ÁO T-SHIRT NỮ BOY RACER', 280000, 5, 'image9_1.png', 'image9_2.png', 'image9_3.png', 'Áo T-shirt nữ Boy Racer có thiết kế thể thao và năng động, phù hợp cho những ai yêu thích phong cách trẻ trung.', NULL, 'ONE SIZE', 15),
(10, 'QUẦN JOGGING STRENGTH', 650000, 6, 'image10_1.png', 'image10_2.png', 'image10_3.png', 'Quần jogging Strength được thiết kế để mang lại sự thoải mái và linh hoạt trong mọi hoạt động.', NULL, 'S, XL, XXL,M', 5),
(11, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU ĐEN', 300000, 3, 'image11_1.png', 'image11_2.png', 'image11_3.png', 'Áo sweatshirt Boy Eagle Smudge màu đen mang đến phong cách trẻ trung và năng động.', NULL, 'M, XS,S', 0),
(12, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU XANH WASH', 600000, 1, 'image12_1.png', 'image12_2.png', 'image12_3.png', 'Áo sweatshirt Boy Eagle Smudge màu xanh wash mang đến vẻ ngoài năng động và cá tính.', NULL, 'ONE SIZE', 0),
(13, 'ÁO SWEATSHIRT BOY EAGLE - MÀU ĐEN/TRẮNG', 245000, 2, 'image13_1.png', 'image13_2.png', 'image13_3.png', 'Áo sweatshirt này là lựa chọn hoàn hảo cho những ngày lạnh, giúp bạn vừa thoải mái vừa thời trang.', NULL, 'S,M,L', 0),
(14, 'ÁO JUMPER NỮ CHEEKY DEVIL DOUBLE SLEEVE', 900000, 3, 'image14_1.png', 'image14_2.png', 'image14_3.png', 'Áo jumper nữ Cheeky Devil Double Sleeve mang đến phong cách thú vị và độc đáo.', NULL, 'L,XXL,XL,S', 5),
(15, 'ÁO FUTURE BOY CORE', 1300000, 3, 'image15_1.png', 'image15_2.png', 'image15_3.png', 'Áo T-shirt Future Boy Core mang đến phong cách hiện đại và trẻ trung.', NULL, 'ONE SIZE', 10),
(16, 'QUẦN JOGGERS BOY 3D EMB - MÀU ĐEN', 2450000, 6, 'image16_1.png', 'image16_2.png', 'image16_3.png', 'Quần joggers Boy 3D Emb màu đen mang đến phong cách thể thao và hiện đại.', NULL, 'L,S,M', 20),
(17, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU TRẮNG', 790000, 2, 'image17_1.png', 'image17_2.png', 'image17_3.png', 'Áo sweatshirt này là lựa chọn hoàn hảo cho những ngày lạnh, giúp bạn vừa thoải mái vừa thời trang.', NULL, 'L,XL,XXL', 0),
(18, 'ÁO SWEATSHIRT BOY WAFFLE RUGBY - MÀU OFF WHITE', 95000, 3, 'image18_1.png', 'image18_2.png', 'image18_3.png', 'Áo sweatshirt Boy Waffle Rugby màu off white mang đến phong cách thể thao và hiện đại.', NULL, 'M,S', 5),
(19, 'ÁO HOODIE BOY MANIA EMBROIDERY - MÀU ĐEN', 5000000, 4, 'image19_1.png', 'image19_2.png', 'image19_3.png', 'Áo hoodie Boy Mania Embroidery màu đen mang đến phong cách trẻ trung và năng động.', 'Hoodie', 'L,M', 15);

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
(1, 'ĐANG XỬ LÝ'),
(2, 'ĐANG GIAO HÀNG'),
(3, 'ĐÃ GIAO HÀNG'),
(4, 'ĐÃ HỦY');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phonenumber` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reset_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `roles` enum('admin','user') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `address1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `phonenumber`, `name`, `email`, `reset_token`, `roles`, `address1`, `address2`) VALUES
(29, 'hoanghai', '202cb962ac59075b964b07152d234b70', '0793897147', 'Hoàng Hải', '123@gmail.com', NULL, 'user', '61 Tân Hương, Tân Phú, TP.HCM', 'Tân Phú, TP. HCM'),
(30, 'hoang1', '202cb962ac59075b964b07152d234b70', '0793897147', 'vua trò chơi ', 'hoanghaii1710@gmail.com', NULL, 'user', NULL, NULL),
(32, 'hoang12', '202cb962ac59075b964b07152d234b70', '0330330333', 'hai', 'khongbiet@gmail.deptrai', NULL, 'user', NULL, NULL),
(33, 'hoanghai2', '202cb962ac59075b964b07152d234b70', '0793897147', 'SIMXtractor', 'hai0777@gmail.com', 'df3bd791c3f90b04111c235a4e014d570b4c3675821d4e77001913d8f88360b2c80c10f489f3d7384356ef01ebfdce9112ed', 'user', '11 Tan Huong, Tan Phu, Ho Chi Minh', ''),
(34, 'hoang14', '202cb962ac59075b964b07152d234b70', '0793897147', 'SIMXtractor', 'hoanghai070727@gmail.com', NULL, 'user', NULL, NULL),
(35, 'hoanghaine', '202cb962ac59075b964b07152d234b70', '0793897147', 'Hải Đẹp Trai', 'hoanghai113@gmail.com', NULL, 'user', '140 lê trọng tấn TP. HCM', ''),
(36, '113', '202cb962ac59075b964b07152d234b70', '0793897147', '113', '12345@gmail.com', NULL, 'user', '', ''),
(37, 'hoang17', '202cb962ac59075b964b07152d234b70', '0793897147', '113', 'hai077@gmail.com', NULL, 'user', '1 Tan Huong, Tan Phu, Ho Chi Minh', ''),
(38, 'hoangQ', '202cb962ac59075b964b07152d234b70', '0793897147', '113', '123456@gmail.com', NULL, 'user', NULL, NULL),
(39, 'hoanghai07077@gmail.com', '67f14b7d30f09c94a55a845a09df07e9', NULL, 'Nguyễn Hoàng Hải', 'hoanghai07077@gmail.com', '120a04cd2730ad48866c0bbfefbec442ec63018ed54faeca30fc2d1fe75003e538432cde86d7b6e14ef2090508942fed4b77', 'user', NULL, NULL),
(40, 'hoangdev', '202cb962ac59075b964b07152d234b70', '0793897147', 'hoàng hải', 'hoanghaimcpe@gmail.com', '00559a38a74595ecbd624f161a5f7dc482ff38573a0034e8cc11c8710cc0c87aa6ce8d80bcecfaa67c34417bd48eb9513d59', 'user', NULL, NULL),
(41, '8989', '202cb962ac59075b964b07152d234b70', '0112232132', '8989', '8989@gmail.com', NULL, 'user', NULL, NULL),
(42, '222', 'bcbe3365e6ac95ea2c0343a2395834dd', '222', '113', '122234@gmail.com', NULL, 'user', NULL, NULL),
(43, 'hoang', 'e10adc3949ba59abbe56e057f20f883e', '0793897147', 'hpl', 'hai07r77@gmail.com', NULL, 'user', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

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
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cart_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `order_detail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

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
