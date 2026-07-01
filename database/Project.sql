-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 01, 2026 at 12:44 PM
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
-- Database: `Project`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_to_cart`
--

CREATE TABLE `add_to_cart` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Admin_user`
--

CREATE TABLE `Admin_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Admin_user`
--

INSERT INTO `Admin_user` (`id`, `name`, `email`, `password`) VALUES
(1, 'sahil', 'sahil@gmail.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Tv', 'tv', 1, '2026-06-19 02:11:29', '2026-06-19 23:34:10'),
(3, 'Mobile', 'mobile', 1, '2026-06-19 02:11:58', '2026-06-19 23:34:09'),
(6, 'Laptop', 'laptop', 0, '2026-06-19 04:09:24', '2026-07-01 01:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ahmedabad', NULL, NULL),
(2, 1, 'Surat', NULL, NULL),
(3, 1, 'Amreli', NULL, NULL),
(4, 2, 'Mumbai', NULL, NULL),
(5, 2, 'Pune', NULL, NULL),
(6, 3, 'Jaipur', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'India', NULL, NULL),
(2, 'America', NULL, NULL),
(3, 'Russia', NULL, NULL),
(4, 'China', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `user_id`, `name`, `email`, `number`, `address`, `gender`, `image`, `created_at`, `updated_at`) VALUES
(34, 8, 'Sahil', 'sahil@gmail.com', '6359950829', 'Thaltej', 'Male', '1781871578.png', NULL, NULL),
(36, 8, 'Dhruvi', 'dhruvi@gmail.com', '9874654654', 'Ishanpur', 'Female', '1781938877.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_11_074906_data', 2),
(5, '2026_06_11_104556_user', 3),
(6, '2026_06_12_075626_admin_user', 4),
(7, '2026_06_12_102738_admin_user', 5),
(8, '2026_06_12_121955_country', 6),
(9, '2026_06_12_122001_state', 6),
(10, '2026_06_12_122009_city', 6),
(11, '2026_06_15_092706_add_location_column_to_data_table', 7),
(12, '2026_06_15_103928_add_status_to_user_table', 8),
(13, '2026_06_15_115502_add_user_id_to_data_table', 9),
(14, '2026_06_16_092325_user_location_mapping', 10),
(15, '2026_06_17_045228_add_data_id_to_user_location_mapping_table', 11),
(16, '2026_06_17_054905_create_personal_access_tokens_table', 11),
(17, '2026_06_19_060509_category', 12),
(18, '2026_06_19_064414_product', 13),
(19, '2026_06_23_051629_add_to_cart', 14),
(20, '2026_06_23_095416_orders', 15),
(21, '2026_06_23_100741_orders', 16),
(22, '2026_06_23_100758_orders_item', 17),
(23, '2026_06_24_043017_orders', 18),
(24, '2026_07_01_051209_payment', 19),
(25, '2026_07_01_053153_orders', 20),
(26, '2026_07_01_053637_order_detail', 21),
(27, '2026_07_01_053729_payments', 22),
(28, '2026_07_01_055636_payments', 23),
(29, '2026_07_01_055827_payments', 24),
(30, '2026_07_01_074344_add_reset_otp_columns_to_user_table', 25);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `address` text NOT NULL,
  `status` enum('Pending','Processing','Confirmed','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `amount`, `address`, `status`, `created_at`, `updated_at`) VALUES
(19, 17, 'ORD20260701095436', 94999.00, 'Thaltej, Ahmedabad, Gujarat - 385241', 'Pending', '2026-07-01 04:24:36', '2026-07-01 04:24:36'),
(20, 17, 'ORD20260701095516', 37999.00, 'Ishanpur, Ahmedabad, Gujarat - 380059', 'Delivered', '2026-07-01 04:25:16', '2026-07-01 05:13:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(21, 19, 11, 1, 94999.00, '2026-07-01 04:24:36', '2026-07-01 04:24:36'),
(22, 20, 10, 1, 37999.00, '2026-07-01 04:25:16', '2026-07-01 04:25:16');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_status` enum('Pending','Paid','Failed','Refunded') NOT NULL DEFAULT 'Pending',
  `razorpay_payment_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `user_id`, `amount`, `payment_method`, `payment_status`, `razorpay_payment_id`, `created_at`, `updated_at`) VALUES
(18, 19, 17, 94999.00, 'COD', 'Pending', NULL, '2026-07-01 04:24:36', '2026-07-01 04:24:36'),
(19, 20, 17, 37999.00, 'CARDLESS_EMI', 'Paid', 'pay_T8CEev0Y0RgaDk', '2026-07-01 04:25:16', '2026-07-01 04:25:41');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `c_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `c_id`, `name`, `slug`, `price`, `image`, `description`, `created_at`, `updated_at`) VALUES
(5, 3, 'Samsung Galaxy S26 Ultra 5G', 'samsung-galaxy-s26-ultra-5g', 129999.00, '1782108977.png', 'Samsung Galaxy S26 Ultra 5G comes with 12GB RAM and 256GB Storage for ultra-fast performance and smooth multitasking. It features a 6.8-inch Dynamic AMOLED 2X 120Hz display, a powerful 200MP + 50MP + 12MP + 10MP quad rear camera, a 12MP front camera, and a 5000mAh battery with 45W fast charging. Powered by the Snapdragon 8 Gen 3 processor, it\'s perfect for gaming, photography, and productivity.', '2026-06-19 06:51:09', '2026-06-22 00:46:17'),
(6, 3, 'Apple iPhone 17 Pro Max', 'apple-iphone-17-pro-max', 159900.00, '1782108987.webp', 'Apple iPhone 17Pro Max features 8GB RAM and 256GB Storage, powered by the A17 Pro chip for exceptional performance. It includes a 6.7-inch Super Retina XDR OLED display, a 48MP + 12MP + 12MP triple camera system, a 12MP front camera, and a 4441mAh battery with fast charging. Built with a premium titanium design and USB-C connectivity.', '2026-06-19 06:51:50', '2026-06-22 00:46:27'),
(7, 3, 'OnePlus 15 5G', 'oneplus-15-5g', 64999.00, '1782109002.webp', 'OnePlus 15G is equipped with 12GB RAM and 256GB Storage for flagship performance. It offers a 6.82-inch AMOLED 120Hz display, a 50MP + 64MP + 48MP Hasselblad camera setup, a 32MP selfie camera, and a 5400mAh battery with 100W SUPERVOOC fast charging. Ideal for gaming, photography, and everyday use.', '2026-06-19 07:25:37', '2026-06-22 00:46:42'),
(8, 3, 'iQOO 15 5G', 'iqoo-15-5g', 52999.00, '1782109010.webp', 'iQOO 15 5G comes with 12GB RAM and 256GB Storage, powered by the Snapdragon 8 Gen 3 processor. It features a 6.78-inch AMOLED 144Hz display, a 50MP + 64MP + 50MP triple rear camera, a 16MP front camera, and a 5000mAh battery with 120W FlashCharge technology for ultra-fast charging and top-tier gaming performance.', '2026-06-22 00:08:30', '2026-06-22 00:46:50'),
(9, 2, 'Samsung 55\"', 'samsung-55-inch', 54999.00, '1782109932.webp', 'Samsung 55-inch Crystal UHD 4K Smart TV features a stunning 55-inch 4K UHD LED display with HDR10+ support for lifelike picture quality. Powered by Tizen OS, it includes Netflix, Prime Video, YouTube, Disney+ Hotstar, and voice assistant support. Enjoy immersive 20W Dolby Digital sound, Wi-Fi, Bluetooth, HDMI, USB connectivity, and a sleek bezel-less design.', '2026-06-22 00:58:38', '2026-06-22 01:02:12'),
(10, 2, 'LG 43\"', 'lg-43-inch', 37999.00, '1782110099.webp', 'LG 43-inch UHD AI ThinQ Smart TV features a 43-inch 4K Ultra HD display with HDR10 and AI Picture technology. Powered by webOS, it offers Netflix, YouTube, Prime Video, Disney+ Hotstar, AI ThinQ voice control, 20W AI Sound, Wi-Fi, Bluetooth, HDMI, USB connectivity, and a slim modern design.', '2026-06-22 00:59:10', '2026-06-22 01:04:59'),
(11, 2, 'Sony Bravia 65\"', 'sony-bravia-65', 94999.00, '1782110124.webp', 'Sony Bravia 65-inch 4K Google TV comes with a premium 65-inch 4K HDR LED display powered by the 4K HDR Processor X1. Running on Google TV, it supports Google Assistant, Chromecast built-in, Netflix, YouTube, Prime Video, Dolby Vision, Dolby Atmos, 20W speakers, Wi-Fi, Bluetooth, HDMI, USB ports, and a premium bezel-less design.', '2026-06-22 00:59:46', '2026-06-22 01:05:24'),
(12, 2, 'TCL 50\"', 'tcl-50-inch', 32999.00, '1782110231.webp', 'TCL 50-inch 4K Android Smart TV features a 50-inch Ultra HD LED display with HDR10 support. Powered by Android TV, it includes Google Assistant, Chromecast built-in, Netflix, Prime Video, YouTube, Dolby Audio 24W speakers, Wi-Fi, Bluetooth, HDMI, USB connectivity, and a stylish bezel-less design.', '2026-06-22 01:00:18', '2026-06-22 01:07:11');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('rcplt1hl0CyJCw2lc1U4LgBFXPn44RmXTJy4a1It', 17, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaHJKMHhvT0U1NHdkS05JeUVwVkt0U3ljeVdqblVTcHR2WnBvSGxVOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9teS1vcmRlcnMiO3M6NToicm91dGUiO3M6OToibXkub3JkZXJzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTc7czoxMToicmVzZXRfZW1haWwiO3M6MjU6InNhaGlscGF0ZWw1NTUwMEBnbWFpbC5jb20iO30=', 1782902617),
('XjOxQ2ZcpmRWZCREYxmwySVDlN7U6jFSRIcCTT8p', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVDIySnhJZEtmeUtsQWFUSTVtcTJWN2F6YzZUTXJmRTQ2NXY3Wmd4dSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9vcmRlcnMiO3M6NToicm91dGUiO3M6MTI6ImFkbWluLm9yZGVycyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTU6ImFkbWluX2xvZ2dlZF9pbiI7YjoxO3M6ODoiYWRtaW5faWQiO2k6MTtzOjEwOiJhZG1pbl9uYW1lIjtzOjU6InNhaGlsIjt9', 1782902615);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Gujarat', NULL, NULL),
(2, 1, 'Maharastra', NULL, NULL),
(3, 1, 'Rajsthan', NULL, NULL),
(4, 1, 'Uttar Pradesh', NULL, NULL),
(5, 2, 'New York', NULL, NULL),
(6, 2, 'Washington', NULL, NULL),
(7, 2, 'Texas', NULL, NULL),
(8, 2, 'Nevada', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_otp` varchar(6) DEFAULT NULL,
  `reset_otp_expiry` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `number`, `email`, `password`, `reset_otp`, `reset_otp_expiry`, `created_at`, `updated_at`, `status`) VALUES
(17, 'Sahil', '6359950829', 'sahilpatel55500@gmail.com', '$2y$12$oQi5TAnGgzlHw4P/amP2xeuU43Jm32ncOyyd1x9QGjqAsZmjQ2rk2', '793756', '2026-07-01 05:16:35', '2026-06-20 03:52:07', '2026-07-01 05:11:35', 'Active'),
(18, 'Dhruvi', '9846541654', 'dhruvi@gamil.com', '$2y$12$ylW5oElZye1vik33.mxRZumW9Q2H5mS8.9Ouvj.E9A3t.xlfV3kSG', NULL, NULL, '2026-06-20 03:52:27', '2026-06-30 23:00:23', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `user_location_mapping`
--

CREATE TABLE `user_location_mapping` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `data_id` int(11) DEFAULT NULL,
  `countries_id` bigint(20) UNSIGNED NOT NULL,
  `states_id` bigint(20) UNSIGNED NOT NULL,
  `cities_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_location_mapping`
--

INSERT INTO `user_location_mapping` (`id`, `user_id`, `data_id`, `countries_id`, `states_id`, `cities_id`) VALUES
(11, 8, 34, 1, 1, 3),
(13, 8, 36, 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_to_cart`
--
ALTER TABLE `add_to_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Admin_user`
--
ALTER TABLE `Admin_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_user_email_unique` (`email`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_slug_unique` (`slug`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_state_id_foreign` (`state_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `data_email_unique` (`email`),
  ADD UNIQUE KEY `data_number_unique` (`number`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_slug_unique` (`slug`),
  ADD KEY `product_c_id_foreign` (`c_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `states_country_id_foreign` (`country_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_email_unique` (`email`);

--
-- Indexes for table `user_location_mapping`
--
ALTER TABLE `user_location_mapping`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_to_cart`
--
ALTER TABLE `add_to_cart`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `Admin_user`
--
ALTER TABLE `Admin_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_location_mapping`
--
ALTER TABLE `user_location_mapping`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_c_id_foreign` FOREIGN KEY (`c_id`) REFERENCES `category` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
