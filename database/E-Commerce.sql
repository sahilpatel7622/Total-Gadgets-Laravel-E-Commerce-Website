-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 07, 2026 at 02:49 PM
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
-- Database: `E-Commerce`
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

--
-- Dumping data for table `add_to_cart`
--

INSERT INTO `add_to_cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(17, 32, 5, 1, '2026-07-07 03:37:24', '2026-07-07 03:37:24'),
(18, 32, 7, 1, '2026-07-07 03:37:33', '2026-07-07 03:37:33');

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
(9, 'Laptop', 'laptop', 0, '2026-07-07 07:02:08', '2026-07-07 07:02:08');

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

-- --------------------------------------------------------

--
-- Table structure for table `Maintenance_Mode`
--

CREATE TABLE `Maintenance_Mode` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Maintenance_Mode`
--

INSERT INTO `Maintenance_Mode` (`id`, `maintenance_mode`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-07-03 03:53:47', '2026-07-06 22:55:03');

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
(30, '2026_07_01_074344_add_reset_otp_columns_to_user_table', 25),
(31, '2026_07_02_055827_otps', 26),
(32, '2026_07_02_073002_otps', 27),
(33, '2026_07_02_073403_otps', 28),
(34, '2026_07_03_053340_add_role_to_user_table', 29),
(35, '2026_07_03_073823_add_to_cart', 30),
(36, '2026_07_03_090208_maintenance__mode', 31),
(37, '2026_07_03_132212_create_personal_access_tokens_table', 32),
(38, '2026_07_07_122841_remove_restrict_on_delete', 33);

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
(39, 24, 'TG20260703064300', 52999.00, 'Amreli, Amreli, Gujarat - 464654', 'Cancelled', '2026-07-03 01:13:00', '2026-07-03 01:13:48'),
(40, 24, 'TG20260703064428', 32999.00, 'Mumbai, Mumbai, Maharastra - 390001', 'Shipped', '2026-07-03 01:14:28', '2026-07-03 02:23:01'),
(66, 32, 'TG20260707090818', 32999.00, 'Thaltej, Ahedabad, Gujarat - 380059', 'Delivered', '2026-07-07 03:38:18', '2026-07-07 03:40:17'),
(67, 32, 'TG20260707090947', 159900.00, 'Ishanpur, Ahmedabad, Gujarat - 985496', 'Shipped', '2026-07-07 03:39:47', '2026-07-07 03:40:19'),
(68, 32, 'TG20260707112708', 37999.00, 'Delhi, Delhi, Delhi - 456654', 'Processing', '2026-07-07 05:57:08', '2026-07-07 05:57:40');

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
(41, 39, 8, 1, 52999.00, '2026-07-03 01:13:00', '2026-07-03 01:13:00'),
(42, 40, 12, 1, 32999.00, '2026-07-03 01:14:28', '2026-07-03 01:14:28'),
(67, 66, 12, 1, 32999.00, '2026-07-07 03:38:18', '2026-07-07 03:38:18'),
(68, 67, 6, 1, 159900.00, '2026-07-07 03:39:47', '2026-07-07 03:39:47'),
(69, 68, 10, 1, 37999.00, '2026-07-07 05:57:08', '2026-07-07 05:57:08');

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `type` varchar(255) NOT NULL,
  `expiry` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otps`
--

INSERT INTO `otps` (`id`, `user_id`, `email`, `otp`, `type`, `expiry`, `created_at`, `updated_at`) VALUES
(17, 24, 'dhruvi@gmail.com', '674237', 'order_verify', '2026-07-03 01:19:17', '2026-07-03 01:14:17', '2026-07-03 01:14:17'),
(37, 32, 'sahilpatel55500@gmail.com', '258194', 'order_verify', '2026-07-07 06:01:53', '2026-07-07 05:56:53', '2026-07-07 05:56:53');

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
(38, 39, 24, 52999.00, 'RAZORPAY', 'Failed', NULL, '2026-07-03 01:13:00', '2026-07-03 02:24:09'),
(39, 40, 24, 32999.00, 'COD', 'Pending', NULL, '2026-07-03 01:14:28', '2026-07-03 01:14:28'),
(56, 66, 32, 32999.00, 'NETBANKING', 'Paid', 'pay_TAYduX3B47Bv6q', '2026-07-07 03:38:18', '2026-07-07 03:38:48'),
(57, 67, 32, 159900.00, 'COD', 'Pending', NULL, '2026-07-07 03:39:47', '2026-07-07 03:39:47'),
(58, 68, 32, 37999.00, 'WALLET', 'Paid', 'pay_TAb0PeNdXyVwu0', '2026-07-07 05:57:08', '2026-07-07 05:57:28');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(20, 'App\\Models\\User', 27, 'myapp', 'f805861f4bfe8801072362a6600706a46b6a324d5fdba95a709a96a7fde937d1', '[\"*\"]', '2026-07-07 00:17:36', NULL, '2026-07-07 00:17:07', '2026-07-07 00:17:36'),
(21, 'App\\Models\\User', 21, 'myapp', '18b3cd3b661afce1a10e44c2a7355ecac515fd8918a5186568d42f20f03952fe', '[\"*\"]', '2026-07-07 00:50:46', NULL, '2026-07-07 00:18:47', '2026-07-07 00:50:46');

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
(5, 3, 'Samsung Galaxy S26 Ultra 5G', 'samsung-galaxy-s26-ultra-5g', 129999.00, '1783423469.webp', 'Samsung Galaxy S26 Ultra 5G comes with 12GB RAM and 256GB Storage for ultra-fast performance and smooth multitasking. It features a 6.8-inch Dynamic AMOLED 2X 120Hz display, a powerful 200MP + 50MP + 12MP + 10MP quad rear camera, a 12MP front camera, and a 5000mAh battery with 45W fast charging. Powered by the Snapdragon 8 Gen 3 processor, it\'s perfect for gaming, photography, and productivity.', '2026-06-19 06:51:09', '2026-07-07 05:54:29'),
(6, 3, 'Apple iPhone 17 Pro Max', 'apple-iphone-17-pro-max', 159900.00, '1783423402.webp', 'Apple iPhone 17Pro Max features 8GB RAM and 256GB Storage, powered by the A17 Pro chip for exceptional performance. It includes a 6.7-inch Super Retina XDR OLED display, a 48MP + 12MP + 12MP triple camera system, a 12MP front camera, and a 4441mAh battery with fast charging. Built with a premium titanium design and USB-C connectivity.', '2026-06-19 06:51:50', '2026-07-07 05:53:22'),
(7, 3, 'OnePlus 15 5G', 'oneplus-15-5g', 64999.00, '1783423332.webp', 'OnePlus 15G is equipped with 12GB RAM and 256GB Storage for flagship performance. It offers a 6.82-inch AMOLED 120Hz display, a 50MP + 64MP + 48MP Hasselblad camera setup, a 32MP selfie camera, and a 5400mAh battery with 100W SUPERVOOC fast charging. Ideal for gaming, photography, and everyday use.', '2026-06-19 07:25:37', '2026-07-07 05:52:12'),
(8, 3, 'iQOO 15 5G', 'iqoo-15-5g', 52999.00, '1783423286.webp', 'iQOO 15 5G comes with 12GB RAM and 256GB Storage, powered by the Snapdragon 8 Gen 3 processor. It features a 6.78-inch AMOLED 144Hz display, a 50MP + 64MP + 50MP triple rear camera, a 16MP front camera, and a 5000mAh battery with 120W FlashCharge technology for ultra-fast charging and top-tier gaming performance.', '2026-06-22 00:08:30', '2026-07-07 05:51:26'),
(9, 2, 'Samsung 55\"', 'samsung-55-inch', 54999.00, '1783423233.webp', 'Samsung 55-inch Crystal UHD 4K Smart TV features a stunning 55-inch 4K UHD LED display with HDR10+ support for lifelike picture quality. Powered by Tizen OS, it includes Netflix, Prime Video, YouTube, Disney+ Hotstar, and voice assistant support. Enjoy immersive 20W Dolby Digital sound, Wi-Fi, Bluetooth, HDMI, USB connectivity, and a sleek bezel-less design.', '2026-06-22 00:58:38', '2026-07-07 05:50:33'),
(10, 2, 'LG 43\"', 'lg-43-inch', 37999.00, '1783423164.webp', 'LG 43-inch UHD AI ThinQ Smart TV features a 43-inch 4K Ultra HD display with HDR10 and AI Picture technology. Powered by webOS, it offers Netflix, YouTube, Prime Video, Disney+ Hotstar, AI ThinQ voice control, 20W AI Sound, Wi-Fi, Bluetooth, HDMI, USB connectivity, and a slim modern design.', '2026-06-22 00:59:10', '2026-07-07 05:49:24'),
(11, 2, 'Sony Bravia 65\"', 'sony-bravia-65', 94999.00, '1783423117.webp', 'Sony Bravia 65-inch 4K Google TV comes with a premium 65-inch 4K HDR LED display powered by the 4K HDR Processor X1. Running on Google TV, it supports Google Assistant, Chromecast built-in, Netflix, YouTube, Prime Video, Dolby Vision, Dolby Atmos, 20W speakers, Wi-Fi, Bluetooth, HDMI, USB ports, and a premium bezel-less design.', '2026-06-22 00:59:46', '2026-07-07 05:48:37'),
(12, 2, 'TCL 50\"', 'tcl-50-inch', 32999.00, '1783422952.webp', 'TCL 50-inch 4K Android Smart TV features a 50-inch Ultra HD LED display with HDR10 support. Powered by Android TV, it includes Google Assistant, Chromecast built-in, Netflix, Prime Video, YouTube, Dolby Audio 24W speakers, Wi-Fi, Bluetooth, HDMI, USB connectivity, and a stylish bezel-less design.', '2026-06-22 01:00:18', '2026-07-07 05:45:52');

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
('NndHQvWBz8mVQRCWV4oOg25SZ3IkzkxfeYuqPwlf', 32, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZ0ZURHAzaUpHYVJ5N28xRVRrUkpaUURYZXZVZmNDUVduM0lleHZqUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MzI7czoxMDoib3JkZXJfZGF0YSI7YToxMDp7czo2OiJfdG9rZW4iO3M6NDA6ImdGVERwM2lKR2FSeTdvMUVUa1JKWlFEWGV2VWZjQ1FXbjNJZXh2alMiO3M6MTg6ImJ1eV9ub3dfcHJvZHVjdF9pZCI7czoyOiIxMCI7czo0OiJuYW1lIjtzOjU6IlNhaGlsIjtzOjY6Im51bWJlciI7czoxMDoiNjM1OTk1MDgyOSI7czo1OiJlbWFpbCI7czoyNToic2FoaWxwYXRlbDU1NTAwQGdtYWlsLmNvbSI7czo3OiJhZGRyZXNzIjtzOjU6IkRlbGhpIjtzOjQ6ImNpdHkiO3M6NToiRGVsaGkiO3M6NToic3RhdGUiO3M6NToiRGVsaGkiO3M6NzoicGluY29kZSI7czo2OiI0NTY2NTQiO3M6MTQ6InBheW1lbnRfbWV0aG9kIjtzOjg6IlJBWk9SUEFZIjt9fQ==', 1783428392),
('rmmAnJJJkPzAaVWm5yPySTvNk0tmaBc7WiErt0ep', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV2JaY2ZyeHhGTHdaTndSOXNmN3IwTVdkMUVNa0preWthcFpsdXNxdiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9jYXRlZ29yeSI7czo1OiJyb3V0ZSI7Tjt9czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMzt9', 1783427908);

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
  `role` enum('user','admin','super_admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `number`, `email`, `password`, `role`, `created_at`, `updated_at`, `status`) VALUES
(23, 'Sahil', '', 'sahil@gmail.com', '123456', 'admin', NULL, NULL, 'Active'),
(24, 'Dhruvi', '9876542563', 'dhruvi@gmail.com', '$2y$12$4PneWBTLXBBJJthFWd5x9OHXQwCYDJZK0n01lbs09Te6TufM3Zo6y', 'user', '2026-07-03 01:11:33', '2026-07-03 07:28:54', 'Active'),
(27, 'Bhumi', '8325325124', 'bhumi@gmail.com', '$2y$12$9jKQq7Rb6pXHkTUqjtXY0eGuvJpnGsDBcv521SOKB3x1Zr5lMdetG', 'user', '2026-07-06 04:06:27', '2026-07-07 03:40:39', 'Inactive'),
(32, 'Sahil', '6359950829', 'sahilpatel55500@gmail.com', '$2y$12$.b76vIF3dLhzc3lJ3cxFdOr8R7.Xp1Awz/fjyMAz.rJa9XQSO8byy', 'user', '2026-07-07 01:29:41', '2026-07-07 01:29:41', 'Active');

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
-- Indexes for dumped tables
--

--
-- Indexes for table `add_to_cart`
--
ALTER TABLE `add_to_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `add_to_cart_user_id_foreign` (`user_id`);

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
-- Indexes for table `Maintenance_Mode`
--
ALTER TABLE `Maintenance_Mode`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otps_user_id_foreign` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `Maintenance_Mode`
--
ALTER TABLE `Maintenance_Mode`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user_location_mapping`
--
ALTER TABLE `user_location_mapping`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `add_to_cart`
--
ALTER TABLE `add_to_cart`
  ADD CONSTRAINT `add_to_cart_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `otps`
--
ALTER TABLE `otps`
  ADD CONSTRAINT `otps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `product_c_id_foreign` FOREIGN KEY (`c_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
