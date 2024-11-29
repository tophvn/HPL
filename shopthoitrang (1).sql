-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 29, 2024 at 02:05 PM
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
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `bill_id` int NOT NULL,
  `user_id` int NOT NULL,
  `bill_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `shipping_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `shipping_fee` int DEFAULT '0',
  `total_amount` int NOT NULL,
  `payment_method` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `shipping_method` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`bill_id`, `user_id`, `bill_date`, `shipping_address`, `shipping_fee`, `total_amount`, `payment_method`, `shipping_method`) VALUES
(54, 71, '2024-11-29 21:05:30', 'Tân Hương', 0, 346750, 'Thanh toán khi nhận hàng (COD)', 'Fast');

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `bill_item_id` int NOT NULL,
  `bill_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  `original_price` int NOT NULL,
  `discount_price` int NOT NULL,
  `subtotal_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`bill_item_id`, `bill_id`, `product_id`, `product_name`, `quantity`, `original_price`, `discount_price`, `subtotal_price`) VALUES
(73, 54, 5, 'ÁO LEN TAY ĐÔI CHEEKY DEVIL', 1, 365000, 346750, 346750);

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
(15, 71, '2024-11-29 14:05:01');

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
(67, 15, 5, 1, 'S', 'Black', '346750.00');

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
(1, 'XU HƯỚNG', 'c-1.jpg'),
(2, 'ĐỘC QUYỀN', 'c-2.jpg'),
(3, 'PHỤ KIỆN', 'c-3.jpg'),
(4, 'THỜI TRANG NỮ', 'c-4.jpg'),
(5, 'THỜI TRANG TRẺ EM', 'c-5.jpg'),
(6, 'THỜI TRANG NAM', 'c-6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contacts_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(77, 71, 1, '2024-11-29 21:05:30', 'Tân Hương', 'Thanh toán khi nhận hàng (COD)', '346750.00', 'Fast');

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

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`order_detail_id`, `order_id`, `product_id`, `order_quantity`) VALUES
(146, 77, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `subcategory_id` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `discount` int DEFAULT '0',
  `type_id` int DEFAULT NULL,
  `view_count` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `category_id`, `subcategory_id`, `image`, `image2`, `image3`, `description`, `size`, `discount`, `type_id`, `view_count`, `created_at`) VALUES
(1, 'ÁO KHOÁC BOMBER MOTO STICKER VARSITY', 50000, 1, 1, 'image1_1.png', 'image1_2.png', 'image1_3.png', 'Áo sweatshirt kiểu oversized boxy với thêu Boucle cổ điển của Boy London.\n\nTrọng lượng áo T-shirt 400GSM. Màu đen.\n\nChất liệu hoàn thiện mềm mại. Người mẫu cao 6ft và mặc size M.', 'L,M,X,S', 10, NULL, 78, '2024-11-28 17:18:41'),
(2, 'ÁO PHÔNG BOY RACER', 1000000, 6, 0, 'image2_1.png', 'image2_2.png', 'image2_3.png', 'Áo T-shirt kiểu oversized boxy với logo/slogan lớn ở giữa.\n\nChất liệu hoàn thiện mềm mại.\n\nNgười mẫu cao 6ft và mặc size M.', 'L,S,M', 20, NULL, 28, '2024-11-28 17:18:41'),
(3, 'ÁO THUN MOTO STICKER BOMB', 80000, 3, 0, 'image3_1.png', 'image3_2.png', 'image3_3.png', 'Áo T-shirt với đồ họa sticker bomb 360 độ. Trọng lượng áo T-shirt 200GSM. Chất liệu hoàn thiện mềm mại.', 'M,S,XS', 10, NULL, 8, '2024-11-28 17:18:41'),
(4, 'ÁO THUN STRENGTH', 200000, 4, 0, 'image4_1.png', 'image4_2.png', 'image4_3.png', 'Áo T-shirt kiểu oversized boxy với in và thêu đồ họa ở phía trước.\n\nNhãn da ở viền dưới phía trước.\n\nTrọng lượng áo T-shirt 200GSM.\n\nChất liệu hoàn thiện mềm mại.', 'S,L,XL,XXL', 5, NULL, 0, '2024-11-28 17:18:41'),
(5, 'ÁO LEN TAY ĐÔI CHEEKY DEVIL', 365000, 1, 0, 'image5_1.png', 'image5_2.png', 'image5_3.png', 'Áo Polo Golf Nam với chất liệu chống nắng, phù hợp cho mọi sân golf.', 'S,M,XL,XXL', 5, NULL, 8, '2024-11-28 17:18:41'),
(6, 'ÁO VARSITY DENIM MOTO', 450000, 2, 0, 'image6_1.png', 'image6_2.png', 'image6_3.png', 'Áo varsity denim moto kết hợp phong cách thể thao với chất liệu denim, tạo nên vẻ ngoài năng động và cá tính.', 'XXL', 5, NULL, 6, '2024-11-28 17:18:41'),
(7, 'ÁO KHOÁC VARSITY BOMBER NỮ MOTO STICKER', 890000, 3, 0, 'image7_1.png', 'image7_2.png', 'image7_3.png', 'Áo khoác varsity bomber nữ với họa tiết sticker moto, mang lại phong cách trẻ trung và năng động. Chất liệu thoải mái và thiết kế thời trang phù hợp cho nhiều dịp.', 'L, XL,M,S', 10, NULL, 4, '2024-11-28 17:18:41'),
(8, 'ÁO T-SHIRT NỮ FUTURE BOY CORE', 390000, 4, 0, 'image8_1.png', 'image8_2.png', 'image8_3.png', 'Áo T-shirt nữ Future Boy Core với thiết kế hiện đại, mang lại phong cách trẻ trung và năng động. Chất liệu thoải mái, phù hợp cho cả những ngày thường và các hoạt động ngoài trời.', 'ONE SIZE', 10, NULL, 4, '2024-11-28 17:18:41'),
(9, 'ÁO T-SHIRT NỮ BOY RACER', 280000, 5, 0, 'image9_1.png', 'image9_2.png', 'image9_3.png', 'Áo T-shirt nữ Boy Racer có thiết kế thể thao và năng động, phù hợp cho những ai yêu thích phong cách trẻ trung.', 'ONE SIZE', 15, NULL, 0, '2024-11-28 17:18:41'),
(10, 'QUẦN JOGGING STRENGTH', 650000, 6, 0, 'image10_1.png', 'image10_2.png', 'image10_3.png', 'Quần jogging Strength được thiết kế để mang lại sự thoải mái và linh hoạt trong mọi hoạt động.', 'S, XL, XXL,M', 5, NULL, 0, '2024-11-28 17:18:41'),
(11, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU ĐEN', 300000, 3, 0, 'image11_1.png', 'image11_2.png', 'image11_3.png', 'Áo sweatshirt Boy Eagle Smudge màu đen mang đến phong cách trẻ trung và năng động.', 'M, XS,S', 0, NULL, 4, '2024-11-28 17:18:41'),
(12, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU XANH WASH', 600000, 1, 0, 'image12_1.png', 'image12_2.png', 'image12_3.png', 'Áo sweatshirt Boy Eagle Smudge màu xanh wash mang đến vẻ ngoài năng động và cá tính.', 'ONE SIZE', 0, NULL, 0, '2024-11-28 17:18:41'),
(13, 'ÁO SWEATSHIRT BOY EAGLE - MÀU ĐEN/TRẮNG', 245000, 2, 0, 'image13_1.png', 'image13_2.png', 'image13_3.png', 'Áo sweatshirt này là lựa chọn hoàn hảo cho những ngày lạnh, giúp bạn vừa thoải mái vừa thời trang.', 'S,M,L', 0, NULL, 0, '2024-11-28 17:18:41'),
(14, 'ÁO JUMPER NỮ CHEEKY DEVIL DOUBLE SLEEVE', 900000, 3, 0, 'image14_1.png', 'image14_2.png', 'image14_3.png', 'Áo jumper nữ Cheeky Devil Double Sleeve mang đến phong cách thú vị và độc đáo.', 'L,XXL,XL,S', 5, NULL, 0, '2024-11-28 17:18:41'),
(15, 'ÁO FUTURE BOY CORE', 1300000, 3, 0, 'image15_1.png', 'image15_2.png', 'image15_3.png', 'Áo T-shirt Future Boy Core mang đến phong cách hiện đại và trẻ trung.', 'ONE SIZE', 10, NULL, 0, '2024-11-28 17:18:41'),
(16, 'QUẦN JOGGERS BOY 3D EMB - MÀU ĐEN', 2450000, 6, 0, 'image16_1.png', 'image16_2.png', 'image16_3.png', 'Quần joggers Boy 3D Emb màu đen mang đến phong cách thể thao và hiện đại.', 'L,S,M', 20, NULL, 0, '2024-11-28 17:18:41'),
(17, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU TRẮNG', 790000, 2, 0, 'image17_1.png', 'image17_2.png', 'image17_3.png', 'Áo sweatshirt này là lựa chọn hoàn hảo cho những ngày lạnh, giúp bạn vừa thoải mái vừa thời trang.', 'L,XL,XXL', 0, NULL, 0, '2024-11-28 17:18:41'),
(18, 'ÁO SWEATSHIRT BOY WAFFLE RUGBY - MÀU OFF WHITE', 95000, 3, 0, 'image18_1.png', 'image18_2.png', 'image18_3.png', 'Áo sweatshirt Boy Waffle Rugby màu off white mang đến phong cách thể thao và hiện đại.', 'M,S', 5, NULL, 0, '2024-11-28 17:18:41'),
(19, 'ÁO HOODIE BOY MANIA EMBROIDERY - MÀU ĐEN', 5000000, 4, 0, 'image19_1.png', 'image19_2.png', 'image19_3.png', 'Áo hoodie Boy Mania Embroidery màu đen mang đến phong cách trẻ trung và năng động.', 'L,M', 15, NULL, 0, '2024-11-28 17:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `type_id` int NOT NULL,
  `type_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`type_id`, `type_name`) VALUES
(1, 'Quần áo'),
(2, 'Phụ kiện'),
(3, 'Giày dép'),
(4, 'Túi xách'),
(5, 'Trang sức');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int NOT NULL,
  `status_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status_color` varchar(7) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_value`, `status_color`) VALUES
(1, 'ĐANG XỬ LÝ', '#ffc107'),
(2, 'ĐANG GIAO HÀNG', '#17a2b8'),
(3, 'ĐÃ GIAO HÀNG', '#28a745'),
(4, 'ĐÃ HỦY', '#dc3545');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `subcategory_id` int NOT NULL,
  `subcategory_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`subcategory_id`, `subcategory_name`, `category_id`) VALUES
(1, 'Áo sơ mi nam', 6),
(2, 'Áo thun nam', 6),
(3, 'Quần jeans nam', 6),
(4, 'Váy đầm nữ', 4),
(5, 'Áo thun nữ', 4),
(6, 'Áo khoác nữ', 4),
(7, 'Giày cao gót', 4),
(8, 'Dép sandal', 6),
(9, 'Mũ thời trang', 3),
(10, 'Kính mát', 3),
(11, 'Ba lô trẻ em', 5),
(12, 'Quần áo bé trai', 5),
(13, 'Quần áo bé gái', 5),
(14, 'Thời trang công sở', 1),
(15, 'Thời trang dạo phố', 1),
(16, 'Phụ kiện độc quyền', 2),
(17, 'Túi xách cao cấp', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phonenumber` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `reset_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `roles` enum('admin','user') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `address1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `google_auth_secret` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `2fa_enabled` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `phonenumber`, `name`, `email`, `reset_token`, `roles`, `address1`, `address2`, `google_auth_secret`, `2fa_enabled`) VALUES
(71, '07af039d5d731bd8c6b7f70788fbb26d', 'd0777927705bc852d155e4da9e21fc98', '0793897147', 'Nguyễn Hoàng Hải', 'hoanghai07077@gmail.com', NULL, 'user', 'Tân Hương', 'Tân Phú, TP. HCM', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `voucher_id` int NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `discount_percentage` int DEFAULT NULL,
  `discount_amount` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `type_id` int DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Hoạt Động','Hết Hạn') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Hoạt Động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_usage`
--

CREATE TABLE `voucher_usage` (
  `usage_id` int NOT NULL,
  `user_id` int NOT NULL,
  `voucher_id` int NOT NULL,
  `used_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`bill_item_id`),
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `product_id` (`product_id`);

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
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contacts_id`);

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
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_products_type` (`type_id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`subcategory_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`voucher_id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `voucher_usage`
--
ALTER TABLE `voucher_usage`
  ADD PRIMARY KEY (`usage_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `voucher_id` (`voucher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `bill_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `bill_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cart_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contacts_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `order_detail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `subcategory_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `voucher_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_usage`
--
ALTER TABLE `voucher_usage`
  MODIFY `usage_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD CONSTRAINT `bill_items_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`bill_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bill_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

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
  ADD CONSTRAINT `fk_products_type` FOREIGN KEY (`type_id`) REFERENCES `product_types` (`type_id`),
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `product_types` (`type_id`);

--
-- Constraints for table `voucher_usage`
--
ALTER TABLE `voucher_usage`
  ADD CONSTRAINT `voucher_usage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `voucher_usage_ibfk_2` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`voucher_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
