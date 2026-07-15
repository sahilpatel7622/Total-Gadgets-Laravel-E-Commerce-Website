-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 15, 2026 at 03:22 PM
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
(51, 37, 5, 1, '2026-07-15 07:45:13', '2026-07-15 07:45:13'),
(52, 37, 9, 1, '2026-07-15 07:45:18', '2026-07-15 07:45:18');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Tv', 'tv', 1, '2026-06-19 02:11:29', '2026-07-10 04:30:27', NULL),
(3, 'Mobile', 'mobile', 1, '2026-06-19 02:11:58', '2026-07-10 04:23:25', NULL),
(9, 'Laptop', 'laptop', 0, '2026-07-07 07:02:08', '2026-07-14 23:53:33', NULL);

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
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` enum('fixed','percentage') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `user_type` enum('all','selected') NOT NULL DEFAULT 'all',
  `minimum_order_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `usage_limit` int(10) UNSIGNED DEFAULT NULL,
  `per_user_limit` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `discount_value`, `user_type`, `minimum_order_amount`, `usage_limit`, `per_user_limit`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(21, 'TGCHYJ8T', 'percentage', 10.00, 'all', 15000.00, 5, 1, '2026-07-15 00:00:00', '2026-08-30 00:00:00', 1, '2026-07-15 01:05:21', '2026-07-15 06:25:09'),
(22, 'TGV4OFOE', 'fixed', 1000.00, 'selected', 10000.00, 4, 1, '2026-07-15 00:00:00', '2026-07-20 00:00:00', 1, '2026-07-15 01:08:34', '2026-07-15 07:50:22'),
(23, 'TG2ZUXM3', 'fixed', 2000.00, 'selected', 30000.00, 10, 2, '2026-07-15 00:00:00', '2027-01-01 00:00:00', 1, '2026-07-15 07:46:23', '2026-07-15 07:48:06');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_users`
--

CREATE TABLE `coupon_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupon_users`
--

INSERT INTO `coupon_users` (`id`, `coupon_id`, `user_id`, `created_at`, `updated_at`) VALUES
(18, 22, 24, NULL, NULL),
(19, 22, 37, NULL, NULL),
(20, 23, 37, NULL, NULL);

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(23, 'default', '{\"uuid\":\"40d66903-9a69-4ec7-a0fd-da23a1544c7f\",\"displayName\":\"App\\\\Mail\\\\CouponCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\CouponCreatedMail\\\":4:{s:6:\\\"coupon\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\Coupon\\\";s:2:\\\"id\\\";i:23;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:24;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:16:\\\"dhruvi@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1784121384,\"delay\":null}', 0, NULL, 1784121384, 1784121384),
(24, 'default', '{\"uuid\":\"1dd8ffc4-50a2-4850-9ee6-ac60c34c4836\",\"displayName\":\"App\\\\Mail\\\\CouponCreatedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":17:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\CouponCreatedMail\\\":4:{s:6:\\\"coupon\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\Coupon\\\";s:2:\\\"id\\\";i:23;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:37;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:25:\\\"sahilpatel55500@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\",\"batchId\":null},\"createdAt\":1784121384,\"delay\":null}', 0, NULL, 1784121384, 1784121384);

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
(1, 1, '2026-07-03 03:53:47', '2026-07-15 02:34:35');

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
(38, '2026_07_07_122841_remove_restrict_on_delete', 33),
(39, '2026_07_08_114010_create_order_details_table', 34),
(40, '2026_07_10_062121_add_status_to_product_table', 35),
(41, '2026_07_10_071306_add_deleted_at_to_user_table', 36),
(42, '2026_07_10_091000_add_deleted_at_to_category_table', 37),
(43, '2026_07_10_091001_add_deleted_at_to_product_table', 37),
(44, '2026_07_14_052554_create_wishlists_table', 38),
(45, '2026_07_14_105615_create_coupons_table', 39),
(46, '2026_07_14_114622_create_coupon_users_table', 40),
(47, '2026_07_15_054617_create_jobs_table', 41),
(48, '2026_07_15_101202_add_coupon_columns_to_orders_table', 42);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `coupon_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Processing','Confirmed','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `coupon_id`, `coupon_code`, `coupon_discount`, `order_number`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(109, 37, 21, 'TGCHYJ8T', 15990.00, 'TG20260715115616', 143910.00, 'Processing', '2026-07-15 06:26:16', '2026-07-15 06:54:27'),
(132, 37, 22, 'TGV4OFOE', 1000.00, 'TG20260715131438', 31999.00, 'Pending', '2026-07-15 07:44:38', '2026-07-15 07:44:38'),
(133, 24, 22, 'TGV4OFOE', 1000.00, 'TG20260715131858', 36999.00, 'Pending', '2026-07-15 07:48:58', '2026-07-15 07:48:58'),
(134, 24, 21, 'TGCHYJ8T', 12999.90, 'TG20260715132101', 116999.10, 'Pending', '2026-07-15 07:51:01', '2026-07-15 07:51:01');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `name`, `number`, `email`, `address`, `created_at`, `updated_at`) VALUES
(40, 109, 'Sahil', '6359950829', 'sahilpatel55500@gmail.com', 'Thaltej, Ahmedabad, Gujarat - 380059', '2026-07-15 06:26:16', '2026-07-15 06:26:16'),
(63, 132, 'Sahil', '6359950829', 'sahilpatel55500@gmail.com', 'Ishanpur, Ahmedabad, Gujarat - 786786', '2026-07-15 07:44:38', '2026-07-15 07:44:38'),
(64, 133, 'Dhruvi', '9876542563', 'dhruvi@gmail.com', 'Mumbai, Mumbai, Maharastra - 378386', '2026-07-15 07:48:58', '2026-07-15 07:48:58'),
(65, 134, 'Dhruvi', '9876542563', 'dhruvi@gmail.com', 'Delhi, Delhi, Delhi - 868676', '2026-07-15 07:51:01', '2026-07-15 07:51:01');

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
(110, 109, 6, 1, 159900.00, '2026-07-15 06:26:16', '2026-07-15 06:26:16'),
(133, 132, 12, 1, 32999.00, '2026-07-15 07:44:38', '2026-07-15 07:44:38'),
(134, 133, 10, 1, 37999.00, '2026-07-15 07:48:58', '2026-07-15 07:48:58'),
(135, 134, 5, 1, 129999.00, '2026-07-15 07:51:01', '2026-07-15 07:51:01');

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
(92, 37, 'sahilpatel55500@gmail.com', '288035', 'order_verify', '2026-07-15 07:49:25', '2026-07-15 07:44:25', '2026-07-15 07:44:25'),
(94, 24, 'dhruvi@gmail.com', '543532', 'order_verify', '2026-07-15 07:55:49', '2026-07-15 07:50:49', '2026-07-15 07:50:49');

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
(97, 109, 37, 143910.00, 'Cash On Delivery', 'Pending', NULL, '2026-07-15 06:26:16', '2026-07-15 06:26:16'),
(120, 132, 37, 31999.00, 'CARDLESS_EMI', 'Paid', 'pay_TDn6wcIb8rxsj9', '2026-07-15 07:44:38', '2026-07-15 07:44:59'),
(121, 133, 24, 36999.00, 'NETBANKING', 'Paid', 'pay_TDnBXcw6c1p86f', '2026-07-15 07:48:58', '2026-07-15 07:49:25'),
(122, 134, 24, 116999.10, 'Cash On Delivery', 'Pending', NULL, '2026-07-15 07:51:01', '2026-07-15 07:51:01');

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
(21, 'App\\Models\\User', 21, 'myapp', '18b3cd3b661afce1a10e44c2a7355ecac515fd8918a5186568d42f20f03952fe', '[\"*\"]', '2026-07-07 00:50:46', NULL, '2026-07-07 00:18:47', '2026-07-07 00:50:46'),
(22, 'App\\Models\\User', 32, 'myapp', '619bf814f543a0f151faff0a4450e5d48b2a86d22500e0303af81cd0976bf223', '[\"*\"]', '2026-07-09 00:37:27', NULL, '2026-07-07 23:31:21', '2026-07-09 00:37:27'),
(23, 'App\\Models\\User', 37, 'myapp', '4a77f85157a870be52aec4f934f2a31b5f6244268bd17b3a8a2eddd37b8659ef', '[\"*\"]', '2026-07-14 04:10:45', NULL, '2026-07-13 23:40:40', '2026-07-14 04:10:45');

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
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `c_id`, `name`, `slug`, `price`, `image`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 3, 'Samsung Galaxy S26 Ultra 5G', 'samsung-galaxy-s26-ultra-5g', 129999.00, '1783423469.webp', 'Samsung Galaxy S26 Ultra 5G comes with 12GB RAM and 256GB Storage for ultra-fast performance and smooth multitasking. It features a 6.8-inch Dynamic AMOLED 2X 120Hz display, a powerful 200MP + 50MP + 12MP + 10MP quad rear camera, a 12MP front camera, and a 5000mAh battery with 45W fast charging. Powered by the Snapdragon 8 Gen 3 processor, it\'s perfect for gaming, photography, and productivity.', 1, '2026-06-19 06:51:09', '2026-07-07 05:54:29', NULL),
(6, 3, 'Apple iPhone 17 Pro Max', 'apple-iphone-17-pro-max', 159900.00, '1783423402.webp', 'Apple iPhone 17Pro Max features 8GB RAM and 256GB Storage, powered by the A17 Pro chip for exceptional performance. It includes a 6.7-inch Super Retina XDR OLED display, a 48MP + 12MP + 12MP triple camera system, a 12MP front camera, and a 4441mAh battery with fast charging. Built with a premium titanium design and USB-C connectivity.', 1, '2026-06-19 06:51:50', '2026-07-10 01:20:14', NULL),
(7, 3, 'OnePlus 15 5G', 'oneplus-15-5g', 64999.00, '1783423332.webp', 'OnePlus 15G is equipped with 12GB RAM and 256GB Storage for flagship performance. It offers a 6.82-inch AMOLED 120Hz display, a 50MP + 64MP + 48MP Hasselblad camera setup, a 32MP selfie camera, and a 5400mAh battery with 100W SUPERVOOC fast charging. Ideal for gaming, photography, and everyday use.', 1, '2026-06-19 07:25:37', '2026-07-10 01:20:11', NULL),
(8, 3, 'iQOO 15 5G', 'iqoo-15-5g', 52999.00, '1783423286.webp', 'iQOO 15 5G comes with 12GB RAM and 256GB Storage, powered by the Snapdragon 8 Gen 3 processor. It features a 6.78-inch AMOLED 144Hz display, a 50MP + 64MP + 50MP triple rear camera, a 16MP front camera, and a 5000mAh battery with 120W FlashCharge technology for ultra-fast charging and top-tier gaming performance.', 1, '2026-06-22 00:08:30', '2026-07-07 05:51:26', NULL),
(9, 2, 'Samsung 55\"', 'samsung-55-inch', 54999.00, '1783423233.webp', 'Samsung 55-inch Crystal UHD 4K Smart TV features a stunning 55-inch 4K UHD LED display with HDR10+ support for lifelike picture quality. Powered by Tizen OS, it includes Netflix, Prime Video, YouTube, Disney+ Hotstar, and voice assistant support. Enjoy immersive 20W Dolby Digital sound, Wi-Fi, Bluetooth, HDMI, USB connectivity, and a sleek bezel-less design.', 1, '2026-06-22 00:58:38', '2026-07-07 05:50:33', NULL),
(10, 2, 'LG 43\"', 'lg-43-inch', 37999.00, '1783423164.webp', 'LG 43-inch UHD AI ThinQ Smart TV features a 43-inch 4K Ultra HD display with HDR10 and AI Picture technology. Powered by webOS, it offers Netflix, YouTube, Prime Video, Disney+ Hotstar, AI ThinQ voice control, 20W AI Sound, Wi-Fi, Bluetooth, HDMI, USB connectivity, and a slim modern design.', 1, '2026-06-22 00:59:10', '2026-07-10 04:20:59', NULL),
(11, 2, 'Sony Bravia 65\"', 'sony-bravia-65', 94999.00, '1783423117.webp', 'Sony Bravia 65-inch 4K Google TV comes with a premium 65-inch 4K HDR LED display powered by the 4K HDR Processor X1. Running on Google TV, it supports Google Assistant, Chromecast built-in, Netflix, YouTube, Prime Video, Dolby Vision, Dolby Atmos, 20W speakers, Wi-Fi, Bluetooth, HDMI, USB ports, and a premium bezel-less design.', 1, '2026-06-22 00:59:46', '2026-07-10 01:20:09', NULL),
(12, 2, 'TCL 50\"', 'tcl-50-inch', 32999.00, '1783598282.webp', 'TCL 50-inch 4K Android Smart TV features a 50-inch Ultra HD LED display with HDR10 support. Powered by Android TV, it includes Google Assistant, Chromecast built-in, Netflix, Prime Video, YouTube, Dolby Audio 24W speakers, Wi-Fi, Bluetooth, HDMI, USB connectivity, and a stylish bezel-less design.', 1, '2026-06-22 01:00:18', '2026-07-14 05:50:25', NULL);

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
('BPGqwKv7hTsRCpb3Wj6RAN2KDv7IcvABUL5wmKlP', 37, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiamVNbWxsQWtXcHJhSnEwNWdRaGVMOER5Ukc4eEp3TUE0V0lndTNaMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzc7fQ==', 1784121709),
('Du04Wv2DwtemlwGuMvRqEoooH5lXZPtSZujvj6WR', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV0dEc3ljY2VjZlN3UEk4bFhOeFFXbUNzcWZlTWRiWDB4SWdqRTFrRyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9wcm9kdWN0IjtzOjU6InJvdXRlIjtOO31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM2O30=', 1784121723);

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
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `number`, `email`, `password`, `role`, `created_at`, `updated_at`, `status`, `deleted_at`) VALUES
(24, 'Dhruvi', '9876542563', 'dhruvi@gmail.com', '$2y$12$4PneWBTLXBBJJthFWd5x9OHXQwCYDJZK0n01lbs09Te6TufM3Zo6y', 'user', '2026-07-03 01:11:33', '2026-07-10 04:03:31', 'Active', NULL),
(27, 'Bhumi', '8325325124', 'bhumi@gmail.com', '$2y$12$9jKQq7Rb6pXHkTUqjtXY0eGuvJpnGsDBcv521SOKB3x1Zr5lMdetG', 'user', '2026-07-06 04:06:27', '2026-07-15 07:46:58', 'Inactive', NULL),
(36, 'Sahil', '7622920559', 'sahil@gmail.com', '$2y$12$LHR.irWTuCUmTqTDQ9Oo/usiYTdRVSksXF1mkW8oTrkujyEFfRsrW', 'admin', '2026-07-09 01:56:11', '2026-07-09 07:54:29', 'Active', NULL),
(37, 'Sahil', '6359950829', 'sahilpatel55500@gmail.com', '$2y$12$4rqCPmaqmaD37nWuF/SZ8OS3vnaMv4Yxbw6DEQZ.xIic9BemINz1G', 'user', '2026-07-10 01:58:02', '2026-07-14 01:24:16', 'Active', NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(108, 37, 8, '2026-07-15 06:21:16', '2026-07-15 06:21:16'),
(109, 37, 9, '2026-07-15 07:45:20', '2026-07-15 07:45:20');

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
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `coupon_users`
--
ALTER TABLE `coupon_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_users_coupon_id_foreign` (`coupon_id`),
  ADD KEY `coupon_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `data_email_unique` (`email`),
  ADD UNIQUE KEY `data_number_unique` (`number`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

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
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`);

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
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_to_cart`
--
ALTER TABLE `add_to_cart`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `coupon_users`
--
ALTER TABLE `coupon_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `Maintenance_Mode`
--
ALTER TABLE `Maintenance_Mode`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `user_location_mapping`
--
ALTER TABLE `user_location_mapping`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `add_to_cart`
--
ALTER TABLE `add_to_cart`
  ADD CONSTRAINT `add_to_cart_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_users`
--
ALTER TABLE `coupon_users`
  ADD CONSTRAINT `coupon_users_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
