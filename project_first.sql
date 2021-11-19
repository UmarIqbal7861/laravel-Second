-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2021 at 11:08 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_first`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `user_id`, `post_id`, `comment`, `file`, `created_at`, `updated_at`) VALUES
(3, 7, 3, 'hello2', 'C:\\xampp\\tmp\\phpCD72.tmp', NULL, NULL),
(4, 7, 4, 'hello2', 'C:\\xampp\\tmp\\phpE5CD.tmp', NULL, NULL),
(5, 7, 5, 'hello2', 'C:\\xampp\\tmp\\phpFA7F.tmp', NULL, NULL),
(7, 13, 20, 'hello2', 'comment/Kmqhi2OZxX0X3jrOXP3XSKYjTpARHzZkOlyfYl0k.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `f_id` bigint(20) UNSIGNED NOT NULL,
  `user1` bigint(20) UNSIGNED NOT NULL,
  `user2` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`f_id`, `user1`, `user2`, `created_at`, `updated_at`) VALUES
(2, 6, 7, NULL, NULL),
(3, 6, 8, NULL, NULL),
(4, 6, 9, NULL, NULL),
(5, 7, 6, NULL, NULL),
(6, 7, 8, NULL, NULL),
(7, 7, 9, NULL, NULL),
(8, 6, 9, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_11_08_095906_create_posts', 1),
(6, '2021_11_08_100603_create_comments', 1),
(7, '2021_11_08_101056_create_friends', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `p_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`p_id`, `user_id`, `file`, `access`, `created_at`, `updated_at`) VALUES
(3, 6, 'post/1dq4gra1Yq6BZbeEn8wb2UQsCz9qGWTMyr94NMaX.jpg', 'public', NULL, NULL),
(4, 6, 'post/COcA1faWPMf1t33wDoQEPdSpNDILhGVEh72uB6wr.jpg', 'public', NULL, NULL),
(5, 6, 'post/iuwISMDPWo0DBaHYTq34ZPwOh5E9Tp6LV8E4JL9m.jpg', 'public', NULL, NULL),
(6, 6, 'post/jZUdjM6yBXsvZKb5jcfn704onApxWg09aK2eviTw.jpg', 'public', NULL, NULL),
(7, 6, 'post/3bqhpA9hPSvPRsDrsU3NwBiucSxq6Bl6Hq18xCVj.jpg', 'public', NULL, NULL),
(8, 6, 'post/XRQ5ONXfDaz3MXPh9TWapRQi1feb1tvhEt9kU3V4.jpg', 'public', NULL, NULL),
(9, 6, 'post/57ErwxltL3Y98r4PEmS5psDimCBx7Il1vn52vv33.jpg', 'public', NULL, NULL),
(10, 6, 'post/Uy55bL8GUuTTXqPZjG5aw7Po8jrn81ED4RDtw07b.jpg', 'private', NULL, NULL),
(11, 6, 'post/vW1nWIua1ZRZXBOhKMBa68b29fDdeqrY0Na4HkVL.jpg', 'private', NULL, NULL),
(12, 6, 'post/rIJUSqiCgA6FBVdGM4HKxYfWsZ7vbf2i4UpEG80I.jpg', 'private', NULL, NULL),
(13, 6, 'post/ffvp1xCVjiCHpnIcEV9cM2IL4u8KcOit97RoOhaH.jpg', 'private', NULL, NULL),
(14, 6, 'post/06hQLkvimzV32OLmxBpjJoSXPeUtASjBzeHcGnde.jpg', 'private', NULL, NULL),
(15, 6, 'post/rGjQuvsTm7gzaDc1Z4e0mw336PaACY9eA4b4MMWH.jpg', 'private', NULL, NULL),
(16, 6, 'post/HQdrTYWxMiWL4dMXlI24oPAwCJyMRjMzjg9ZKarI.jpg', 'private', NULL, NULL),
(20, 13, 'post/l7RMVeG8nSAbxE27c1weBerv6lNvf4Od6aPbnkDH.jpg', 'private', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `token` int(11) NOT NULL,
  `profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `name`, `email`, `email_verified_at`, `password`, `gender`, `status`, `token`, `profile`, `remember_token`, `created_at`, `updated_at`) VALUES
(6, 'usama', 'u4042@gmail.com', '2021-11-12 04:29:41', '$2y$10$fhVavup55mJEJs3zH4nxTOMob1hk13OVoSmGsocCkNpdFV0GTvEBG', 'Male', 0, 2976, 'Profile_pic/oYu0AbI1D6zbcdrzGlDrGez3gr6AANOPsnq5cBG9.jpg', NULL, '2021-11-12 04:27:37', '2021-11-12 04:29:42'),
(7, 'usama', 'um4042@gmail.com', '2021-11-12 04:35:51', '$2y$10$Ad7dqip2W8qRTRaVqLwA/eS14ZN1gXr.WubWgOF4oOzIaXUWPXzmy', 'Male', 0, 797, 'Profile_pic/0uhxy0IxrrHttoYq2SFgR6rIF3vbj52HZWGXSvhe.jpg', NULL, '2021-11-12 04:30:04', '2021-11-12 04:35:51'),
(8, 'usama', 'uma4042@gmail.com', '2021-11-12 04:36:36', '$2y$10$ePBRGcv.FBZsEWzsKKA7aeK6I8mJFKiyCxIAZ.A9qPWgh3ouFESJ2', 'Male', 0, 109, 'Profile_pic/AnlT5VSnrpRKy8TRVtkgB5K6dBz7RUfS9qHCk8qj.jpg', NULL, '2021-11-12 04:30:17', '2021-11-12 04:36:36'),
(9, 'usama', 'umar4042@gmail.com', '2021-11-12 04:36:45', '$2y$10$4RQC0eN0WbmgO8hRIeLvEOfzDNltjd/Ol3RjZp2MEP5uSxn7ynqcW', 'Male', 0, 828, 'Profile_pic/X8qOf9o68dpMtbDasUgFlGopbDC4Ufw7sXiRChOq.jpg', NULL, '2021-11-12 04:30:29', '2021-11-12 04:36:45'),
(10, 'usama', 'umari4042@gmail.com', NULL, '$2y$10$5QZY6EDzPaaa1UHxq6oy3uw1gJ4cmHzebq52cuAw7JQxvKzKTq5N2', 'Male', 0, 446, 'Profile_pic/BPQ2j63k9AwXuGBuGBH1YZGny1MQI4IhdNiKLWbp.jpg', NULL, '2021-11-12 04:30:40', '2021-11-12 04:30:40'),
(11, 'usama', 'ubil4042@gmail.com', NULL, '$2y$10$hCmAzYHoTuKvmt6Dg6vJ3eNZ.xA97pi0pNyJ7ibEAUu94mHOj0TCC', 'Male', 0, 621, 'Profile_pic/0NgMmatxQ3HtGyqSvHH4l7KAZveZKJjRBZEPsLWZ.jpg', NULL, '2021-11-12 05:00:40', '2021-11-12 05:00:40'),
(12, 'umar', 'u40@gmail.com', NULL, '$2y$10$do33FmcJAXMiWPaJ7M4HUeHOtGwSjDAF3PkiCDe1ShbXL4yPUylSu', 'Male', 0, 194, 'Profile_pic/f6yihj3FzWHCv3GwwK7P9QhjVLq0XSi4ZUVkvMrW.jpg', NULL, '2021-11-12 06:47:50', '2021-11-12 06:47:50'),
(13, 'umar', 'u4014258@gmail.com', '2021-11-12 07:28:29', '$2y$10$8fMkbMieYXEB5.hq7xyUcu6iltBa.pZjv1GhxjiJeTAe/O8PlVGfm', 'Male', 0, 8413, 'Profile_pic/Rnj4O3olQmaIy3aaYqIwgsA5v7aQUPX0jgSCkCGu.jpg', NULL, '2021-11-12 07:28:05', '2021-11-12 07:28:29'),
(14, 'umar', 'hamza@gmail.com', '2021-11-14 09:02:08', '$2y$10$C.pitT.rRUhCHHdBbakTjeNYK6HptxJTzyqfVe7egXiCN.tZBUGY2', 'Male', 0, 424, 'Profile_pic/o5EyFa6KwvFSEg6p4sPSDjDxKevGmDXR5cueJpvN.jpg', NULL, '2021-11-14 09:00:20', '2021-11-14 09:02:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_post_id_foreign` (`post_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `friends_user1_foreign` (`user1`),
  ADD KEY `friends_user2_foreign` (`user2`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `posts_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `f_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `p_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`p_id`),
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`u_id`);

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_user1_foreign` FOREIGN KEY (`user1`) REFERENCES `users` (`u_id`),
  ADD CONSTRAINT `friends_user2_foreign` FOREIGN KEY (`user2`) REFERENCES `users` (`u_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`u_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
