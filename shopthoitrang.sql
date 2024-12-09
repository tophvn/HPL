-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 09, 2024 at 04:46 AM
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
(90, 83, '2024-12-03 09:18:17', 'Tân Hương', 50000, 1120000, 'COD', 'Express'),
(91, 83, '2024-12-03 10:14:02', 'Tân Hương', 50000, 295000, 'COD', 'Express'),
(92, 83, '2024-12-03 10:33:29', 'Tân Hương', 0, 790000, 'Credit/Debit Card', 'Fast'),
(94, 83, '2024-12-03 15:38:55', 'Tân Hương 2', 0, 845000, 'COD', 'Fast'),
(95, 83, '2024-12-05 19:22:54', 'Tân Hương 2', 0, 1717000, 'COD', 'Fast'),
(96, 83, '2024-12-06 11:34:28', 'Tân Hương 2', 50000, 320000, 'COD', 'Express'),
(97, 83, '2024-12-06 11:38:40', 'Tân Hương 2', 0, 0, 'COD', 'Fast'),
(98, 83, '2024-12-06 11:42:13', 'Tân Hương 2', 0, 800000, 'COD', 'Fast'),
(99, 83, '2024-12-06 13:10:53', 'Tân Hương 2', 0, 283000, 'COD', 'Fast'),
(101, 88, '2024-12-07 23:40:20', '62 tân hương', 50000, 4823000, 'COD', 'Express');

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `bill_item_id` int NOT NULL,
  `bill_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `size` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `color` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `original_price` int NOT NULL,
  `discount_price` int NOT NULL,
  `subtotal_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`bill_item_id`, `bill_id`, `product_id`, `product_name`, `quantity`, `size`, `color`, `original_price`, `discount_price`, `subtotal_price`) VALUES
(121, 90, 1, 'ÁO KHOÁC BOMBER MOTO STICKER VARSITY', 4, NULL, NULL, 50000, 45000, 180000),
(122, 90, 1, 'ÁO KHOÁC BOMBER MOTO STICKER VARSITY', 2, NULL, NULL, 50000, 45000, 90000),
(123, 90, 2, 'ÁO PHÔNG BOY RACER', 1, NULL, NULL, 1000000, 800000, 800000),
(124, 91, 13, 'ÁO SWEATSHIRT BOY EAGLE - MÀU ĐEN/TRẮNG', 1, NULL, NULL, 245000, 245000, 245000),
(125, 92, 17, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU TRẮNG', 1, NULL, NULL, 790000, 790000, 790000),
(126, 94, 2, 'ÁO PHÔNG BOY RACER', 1, NULL, NULL, 1000000, 800000, 800000),
(127, 94, 1, 'ÁO KHOÁC BOMBER MOTO STICKER VARSITY', 1, NULL, NULL, 50000, 45000, 45000),
(128, 95, 2, 'ÁO PHÔNG BOY RACER', 2, NULL, NULL, 1000000, 800000, 1600000),
(129, 95, 3, 'ÁO THUN MOTO STICKER BOMB', 1, NULL, NULL, 80000, 72000, 72000),
(130, 95, 1, 'ÁO KHOÁC BOMBER MOTO STICKER VARSITY', 1, NULL, NULL, 50000, 45000, 45000),
(131, 96, 1, 'ÁO KHOÁC BOMBER MOTO STICKER VARSITY', 6, 'M', 'ĐỎ', 50000, 45000, 270000),
(132, 98, 2, 'ÁO PHÔNG BOY RACER', 1, 'S', 'TRẮNG', 1000000, 800000, 800000),
(133, 99, 1, 'ÁO KHOÁC BOMBER MOTO STICKER VARSITY', 1, 'M', 'ĐỎ', 50000, 45000, 45000),
(134, 99, 9, 'ÁO T-SHIRT NỮ BOY RACER', 1, 'ONE SIZE', 'TRẮNG', 280000, 238000, 238000),
(137, 101, 7, 'ÁO KHOÁC VARSITY BOMBER NỮ MOTO STICKER', 3, 'XL', 'ĐEN', 890000, 801000, 2403000),
(138, 101, 17, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU TRẮNG', 3, 'XXL', 'TRẮNG', 790000, 790000, 2370000);

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
(1, 'Nike'),
(2, 'Adidas'),
(3, 'Gucci'),
(4, 'Puma'),
(5, 'Reebok'),
(6, 'Under Armour'),
(7, 'Converse'),
(8, 'Vans'),
(9, 'New Balance'),
(10, 'Asics'),
(11, 'Skechers'),
(12, 'Fila'),
(13, 'Champion'),
(14, 'Long Dreagon'),
(15, 'Tommy Hilfiger'),
(16, 'Ralph Lauren'),
(17, 'Lacoste'),
(18, 'H&M'),
(19, 'Zara'),
(20, 'Uniqlo'),
(21, 'Gap'),
(22, 'Calvin Klein'),
(23, 'Diesel'),
(24, 'Guess'),
(25, 'Mango'),
(26, 'Abercrombie & Fitch'),
(27, 'Hollister'),
(28, 'Burberry'),
(29, 'Prada'),
(30, 'Versace'),
(31, 'Boy London');

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
(21, 83, '2024-12-03 01:36:37'),
(22, 88, '2024-12-07 15:37:43');

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
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`cart_item_id`, `cart_id`, `product_id`, `quantity`, `size`, `color`, `price`) VALUES
(134, 21, 162, 1, 'S', 'ĐEN', 285000),
(135, 21, 163, 1, 'M', 'XANH RÊU', 712500);

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

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`contacts_id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(8, 'hoang hai', 'hoanghai07077@gmail.com', 'lỗi ', '123', '2024-12-07 15:21:06'),
(9, 'hoang hai', 'hoanghai07077@gmail.com', '2', 'ok', '2024-12-07 16:07:56');

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
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `history_id` int NOT NULL,
  `user_id` int NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  `payment_method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
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
  `subcategory_id` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `brand_id` int DEFAULT NULL,
  `size` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `color` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `discount` int DEFAULT '0',
  `view_count` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `category_id`, `subcategory_id`, `image`, `image2`, `image3`, `description`, `brand_id`, `size`, `color`, `discount`, `view_count`, `created_at`) VALUES
(1, 'ÁO KHOÁC BOMBER MOTO STICKER VARSITY', 50000, 1, 1, 'image1_1.png', 'image1_2.png', 'image1_3.png', '', 31, 'L,M,X,S', 'ĐEN,ĐỎ', 10, 158, '2024-11-28 17:18:41'),
(2, 'ÁO PHÔNG BOY RACER', 1000000, 6, 2, 'image2_1.png', 'image2_2.png', 'image2_3.png', 'Áo T-shirt kiểu oversized boxy với logo/slogan lớn ở giữa.\r\n\r\nChất liệu hoàn thiện mềm mại.\r\n\r\nNgười mẫu cao 6ft và mặc size M.', 31, 'L,S,M', 'TRẮNG', 20, 142, '2024-11-28 17:18:41'),
(3, 'ÁO THUN MOTO STICKER BOMB', 80000, 6, 2, 'image3_1.png', 'image3_2.png', 'image3_3.png', 'Áo T-shirt với đồ họa sticker bomb 360 độ. Trọng lượng áo T-shirt 200GSM. Chất liệu hoàn thiện mềm mại.', 31, 'M,S,XS', 'ĐEN, TRẮNG', 10, 34, '2024-11-28 17:18:41'),
(4, 'ÁO THUN STRENGTH', 200000, 6, 2, 'image4_1.png', 'image4_2.png', 'image4_3.png', 'Áo T-shirt kiểu oversized boxy với in và thêu đồ họa ở phía trước.\r\n\r\nNhãn da ở viền dưới phía trước.\r\n\r\nTrọng lượng áo T-shirt 200GSM.\r\n\r\nChất liệu hoàn thiện mềm mại.', 14, 'S,L,XL,XXL', 'ĐEN', 5, 165, '2024-11-28 17:18:41'),
(5, 'ÁO LEN TAY ĐÔI CHEEKY DEVIL', 365000, 1, 5, 'image5_1.png', 'image5_2.png', 'image5_3.png', 'Áo Polo Golf Nam với chất liệu chống nắng, phù hợp cho mọi sân golf.', 15, 'S,M,XL,XXL', 'ĐỎ', 5, 10, '2024-11-28 17:18:41'),
(6, 'ÁO VARSITY DENIM MOTO', 450000, 2, 6, 'image6_1.png', 'image6_2.png', 'image6_3.png', 'Áo varsity denim moto kết hợp phong cách thể thao với chất liệu denim, tạo nên vẻ ngoài năng động và cá tính.', 10, 'XXL', 'ĐEN', 5, 14, '2024-11-28 17:18:41'),
(7, 'ÁO KHOÁC VARSITY BOMBER NỮ MOTO STICKER', 890000, 4, 6, 'image7_1.png', 'image7_2.png', 'image7_3.png', 'Áo khoác varsity bomber nữ với họa tiết sticker moto, mang lại phong cách trẻ trung và năng động. Chất liệu thoải mái và thiết kế thời trang phù hợp cho nhiều dịp.', 31, 'L, XL,M,S', 'XÁM,ĐEN', 10, 16, '2024-11-28 17:18:41'),
(8, 'ÁO T-SHIRT NỮ FUTURE BOY CORE', 390000, 4, 5, 'image8_1.png', 'image8_2.png', 'image8_3.png', 'Áo T-shirt nữ Future Boy Core với thiết kế hiện đại, mang lại phong cách trẻ trung và năng động. Chất liệu thoải mái, phù hợp cho cả những ngày thường và các hoạt động ngoài trời.', 27, 'ONE SIZE', 'TRẮNG', 10, 8, '2024-11-28 17:18:41'),
(9, 'ÁO T-SHIRT NỮ BOY RACER', 280000, 4, 5, 'image9_1.png', 'image9_2.png', 'image9_3.png', 'Áo T-shirt nữ Boy Racer có thiết kế thể thao và năng động, phù hợp cho những ai yêu thích phong cách trẻ trung.', 16, 'ONE SIZE', 'ĐEN, TRẮNG', 15, 3, '2024-11-28 17:18:41'),
(10, 'QUẦN JOGGING STRENGTH', 650000, 6, 2, 'image10_1.png', 'image10_2.png', 'image10_3.png', 'Quần jogging Strength được thiết kế để mang lại sự thoải mái và linh hoạt trong mọi hoạt động.', 25, 'S, XL, XXL,M', 'XÁM', 5, 8, '2024-11-28 17:18:41'),
(11, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU ĐEN', 300000, 6, 15, 'image11_1.png', 'image11_2.png', 'image11_3.png', 'Áo sweatshirt Boy Eagle Smudge màu đen mang đến phong cách trẻ trung và năng động.', 31, 'M, XS,S', 'ĐEN', 0, 4, '2024-11-28 17:18:41'),
(12, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU XANH WASH', 600000, 1, 2, 'image12_1.png', 'image12_2.png', 'image12_3.png', 'Áo sweatshirt Boy Eagle Smudge màu xanh wash mang đến vẻ ngoài năng động và cá tính.', 31, 'ONE SIZE', 'XÁM XANH', 0, 5, '2024-11-28 17:18:41'),
(13, 'ÁO SWEATSHIRT BOY EAGLE - MÀU ĐEN/TRẮNG', 245000, 6, 5, 'image13_1.png', 'image13_2.png', 'image13_3.png', 'Áo sweatshirt này là lựa chọn hoàn hảo cho những ngày lạnh, giúp bạn vừa thoải mái vừa thời trang.', 29, 'S,M,L', 'ĐEN', 0, 6, '2024-11-28 17:18:41'),
(14, 'ÁO JUMPER NỮ CHEEKY DEVIL DOUBLE SLEEVE', 900000, 4, 15, 'image14_1.png', 'image14_2.png', 'image14_3.png', 'Áo jumper nữ Cheeky Devil Double Sleeve mang đến phong cách thú vị và độc đáo.', 16, 'L,XXL,XL,S', '', 5, 2, '2024-11-28 17:18:41'),
(15, 'ÁO FUTURE BOY CORE', 1300000, 1, 2, 'image15_1.png', 'image15_2.png', 'image15_3.png', 'Áo T-shirt Future Boy Core mang đến phong cách hiện đại và trẻ trung.', 31, 'ONE SIZE', 'ĐEN, TRẮNG', 10, 1, '2024-11-28 17:18:41'),
(16, 'QUẦN JOGGERS BOY 3D EMB - MÀU ĐEN', 2450000, 6, 3, 'image16_1.png', 'image16_2.png', 'image16_3.png', 'Quần joggers Boy 3D Emb màu đen mang đến phong cách thể thao và hiện đại.', 30, 'L,S,M', 'ĐEN, XÁM', 20, 3, '2024-11-28 17:18:41'),
(17, 'ÁO SWEATSHIRT BOY EAGLE SMUDGE - MÀU TRẮNG', 790000, 2, 2, 'image17_1.png', 'image17_2.png', 'image17_3.png', 'Áo sweatshirt này là lựa chọn hoàn hảo cho những ngày lạnh, giúp bạn vừa thoải mái vừa thời trang.', 8, 'L,XL,XXL', 'TRẮNG', 0, 13, '2024-11-28 17:18:41'),
(18, 'ÁO SWEATSHIRT BOY WAFFLE RUGBY - MÀU OFF WHITE', 95000, 6, 2, 'image18_1.png', 'image18_2.png', 'image18_3.png', 'Áo sweatshirt Boy Waffle Rugby màu off white mang đến phong cách thể thao và hiện đại.', 31, 'M,S', 'TRẮNG', 5, 3, '2024-11-28 17:18:41'),
(19, 'ÁO HOODIE BOY MANIA EMBROIDERY - MÀU ĐEN', 5000000, 6, 5, 'image19_1.png', 'image19_2.png', 'image19_3.png', 'Áo hoodie Boy Mania Embroidery màu đen mang đến phong cách trẻ trung và năng động.', 12, 'L,M', 'ĐEN', 15, 0, '2024-11-28 17:18:41'),
(161, 'ÁO PHÔNG FUTURE BOY - MÀU TÍM SƯƠNG', 890000, 6, 2, 'FUTURE-BOY-T-SHIRT-PURPLE-FOG-2.png', 'FUTURE-BOY-T-SHIRT-PURPLE-FOG-3.png', 'FUTURE-BOY-T-SHIRT-PURPLE-FOG-4.png', '', 31, 'M,S,XS', 'TÍM', 5, 1, '2024-12-06 11:05:23'),
(162, 'Áo Phông dài tay giữ nhiệt basic FWTL001', 300000, 6, 15, 'image2.png', 'image1.png', 'image3.png', '📍Tên sản phẩm: Áo Phông dài tay giữ nhiệt TORANO basic FWTL001\r\n\r\n📍Chất liệu: 92% Cotton + 8% Spandex\r\n\r\n📍Phom dáng: Slimfit hơi ôm\r\n\r\n📍Size: S, M, L, XL\r\n\r\n📍Xuất xứ: Việt Nam', 17, 'S,M,L', 'ĐEN, TRẮNG', 5, 2, '2024-12-09 10:10:36'),
(163, 'Sơ mi ngắn tay trơn hiệu ứng FSTB023', 750000, 6, 1, 'Sơ mi ngắn tay trơn hiệu ứng FSTB023-2.png', 'Sơ mi ngắn tay trơn hiệu ứng FSTB023.png', 'Sơ mi ngắn tay trơn hiệu ứng FSTB023-3.png', 'Mùa hè chắc chắn không thể thiếu áo sơ mi ngắn tay để luôn đảm bảo mát mẻ mà vẫn lịch sự. Với chất liệu Bamboo mát mịn, thấm hút tốt, áo sơ mi ngắn tay trơn giúp anh em không phải lo lắng về vấn đề nóng bí, khó chịu.', 23, 'L, XL,M,S', 'TRẮNG, XANH RÊU', 5, 6, '2024-12-09 10:23:14'),
(164, 'MŨ LEN BOY EAGLE', 160000, 3, 9, 'BOY-EAGLE-BEANIE-KIDS-BLACK.png', 'BOY-EAGLE-BEANIE-KIDS-BLACK-2.png', 'BOY-EAGLE-BEANIE-KIDS-BLACK-3.png', '', 8, 'ONE SIZE', 'ĐEN', 10, 1, '2024-12-09 10:29:18'),
(165, 'Quần jeans basic FABJ003', 670000, 6, 3, 'Quần jeans basic FABJ003-1.png', 'Quần jeans basic FABJ003-2.png', 'Quần jeans basic FABJ003.png', '📍Tên sản phẩm: Quần Jeans nam TORANO dáng basic FABJ003\r\n\r\n📍 Chất liệu: Jeans dày dặn, siêu bền, không phai màu\r\n\r\n📍Màu sắc: Xanh da trời nhạt, Darknavy, Xanh da trời đậm\r\n\r\n📍Phom dáng: basic hơi ôm\r\n\r\n📍Size: 29-30-31-32-33\r\n\r\n📍Xuất xứ: Việt Nam', 24, 'S,M,XL', 'XANH DA TRỜI, XANH DA TRỜI ĐẬM', 10, 0, '2024-12-09 10:33:16'),
(166, 'chân váy dáng suông nữ dd', 800000, 4, 4, 'Screenshot 2024-12-09 104921.png', 'Screenshot 2024-12-09 104941.png', 'Screenshot 2024-12-09 104958.png', '- Tên sản phẩm:  Chân Váy Dáng Suông Nữ DD\r\n\r\n- Độ tuổi: > 13 tuổi\r\n\r\n- Phù hợp: Mặc đi làm, đi học, đi chơi.\r\n\r\n- Chất liệu: Vải kaki\r\n\r\n- Màu sắc: Đen/Trắng \r\n\r\n- Họa tiết: Trơn\r\n\r\n- Xuất xứ: Tự thiết kế và sản xuất bởi FM Style tại Việt Nam ', 14, 'S,M,L', 'ĐEN, TRẮNG', 5, 3, '2024-12-09 10:50:37'),
(167, 'yếm kaki trẻ em váy 1 túi', 500000, 5, 13, 'yếm kaki trẻ em váy 1 túi-1.png', 'yếm kaki trẻ em váy 1 túi-2.png', 'yếm kaki trẻ em váy 1 túi-3.png', '- Tên sản phẩm: Yếm kaki Trẻ Em Váy 1 Túi\r\n\r\n- Độ tuổi: < 10 tuổi\r\n\r\n- Phù hợp: Mặc đi học, đi chơi, ở nhà.\r\n\r\n- Chất liệu: Vải kaki\r\n\r\n- Màu sắc: Đỏ Cam/ Xanh/ Be\r\n\r\n- Họa tiết: trơn\r\n\r\n- Xuất xứ: Tự thiết kế và sản xuất bởi FM Style tại Việt Nam ', 10, '1-10', 'XANH, ĐỎ CAM, TRẮNG', 10, 0, '2024-12-09 10:54:22'),
(168, 'Urbas Love+ 24 - Oyster White', 2000000, 2, 20, 'Pro_ALP2402_5-1.jpg', 'Pro_ALP2402_1-1.png', 'Pro_ALP2402_6-1.jpg', '', 5, '37,38,39,42,43,45', 'TRẮNG XÁM', 20, 1, '2024-12-09 11:01:16'),
(169, 'Kính gọng kim loại Basic mắt vuông bo góc chân bọc nhựa', 500000, 3, 10, '20241209_lhSH6gCYeb.jpeg', '20241209_lhSH6gCYeb.jpeg', '20241209_lhSH6gCYeb.jpeg', '\r\nKính gọng kim loại Basic mắt vuông bo góc chân bọc nhựa', 17, 'ONE SIZE', 'ĐEN', 0, 4, '2024-12-09 11:05:18'),
(170, 'Dép kín mũi Retro 1 màu khoét cạnh đế cao', 50000, 3, 26, '20241205_MDhurockcY.jpeg', '20241205_q4tDaapxhk.jpeg', '20241205_q4tDaapxhk.jpeg', '', 19, 'ONE SIZE', 'ĐEN, TRẮNG', 0, 1, '2024-12-09 11:16:46'),
(171, 'Túi đeo vải lông xù Basic 1 màu 36x40', 300000, 3, 17, '20241207_qud8IkAmgA.jpeg', '20241207_qud8IkAmgA.jpeg', '20241207_qud8IkAmgA.jpeg', '', 18, 'ONE SIZE', 'ĐEN', 5, 1, '2024-12-09 11:18:16'),
(172, ' Túi đeo gấu bông Sanrio family Cinnamoroll Kuromi cosplay little friend face', 130000, 3, 24, '20241119_A5hYV5ru.jpeg', '20241119_aNQ9O0PrSn.jpeg', '20241119_b7TEvAbvfN.jpeg', '', 21, 'ONE SIZE', 'TÍM, XANH', 5, 2, '2024-12-09 11:20:17'),
(173, 'Túi đeo chéo da lộn hình bầu dục nổi đồng màu dây xích', 200000, 3, 24, '20241114_3NPUkoJx.jpg', '20241114_geoizVB4.jpg', '20241114_oGl5B7qx.jpg', '', 26, 'ONE SIZE', 'ĐEN, BE', 5, 0, '2024-12-09 11:21:59'),
(176, 'LEVIs', 1800000, 2, 9, '00.png', '00.png', '00.png', 'Được thành lập năm 1873, Levi’s® được thế giới biết đến & ngợi khen bởi những sản phẩm jeans đạt chuẩn mực trong thiết kế.', 13, 'ONE SIZE', 'TRẮNG', 10, 0, '2024-12-09 11:25:18');

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
(1, 'Áo sơ mi', 6),
(2, 'Áo thun nam', 6),
(3, 'Quần jeans', 6),
(4, 'Váy đầm', 4),
(5, 'Áo thun nữ', 4),
(6, 'Áo khoác', 4),
(7, 'Giày cao gót', 4),
(8, 'Dép sandal', 6),
(9, 'Mũ thời trang', 3),
(10, 'Kính mát', 3),
(11, 'Ba lô trẻ em', 5),
(12, 'Quần áo bé trai', 5),
(13, 'Quần áo bé gái', 5),
(14, 'Thời trang công sở', 1),
(15, 'Thời trang dạo phố', 1),
(16, 'Phụ kiện', 2),
(17, 'Túi xách', 2),
(18, 'Áo khoác nam', 6),
(19, 'Giày thể thao', 6),
(20, 'Giày boot', 6),
(21, 'Quần short', 6),
(22, 'Đầm maxi', 4),
(23, 'Váy công sở', 4),
(24, 'Túi xách nữ', 4),
(25, 'Túi xách nam', 6),
(26, 'Dép', 3),
(27, 'Khăn choàng', 3),
(28, 'Đồng hồ nam', 2),
(29, 'Đồng hồ nữ', 2),
(30, 'Giày sandal', 6);

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
(83, '07af039d5d731bd8c6b7f70788fbb26d', '4e390db6b97e8776b7261eee8229dc1c', '0793897147', 'Nguyễn Hoàng Hải', 'hoanghai07077@gmail.com', NULL, 'admin', 'ok', 'Tân Phú, TP. HCM', NULL, NULL),
(88, '633e839ad7980d0276d2aea8ca20fbe8', '4e390db6b97e8776b7261eee8229dc1c', '8707765578', 'Fashion HPL', 'hplfashionvn@gmail.com', NULL, 'user', '62 tân hương', '', '45FXED32ZNBK5N26', 1);

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
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

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
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`history_id`),
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
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_brand_id` (`brand_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `bill_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `bill_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cart_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contacts_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `history_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `order_detail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `subcategory_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

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
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_brand_id` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
