-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2021 at 05:21 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `diagnosis`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_activation_attempts`
--

CREATE TABLE `auth_activation_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_activation_attempts`
--

INSERT INTO `auth_activation_attempts` (`id`, `ip_address`, `user_agent`, `token`, `created_at`) VALUES
(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', 'a8f9ad52965795457f8640396d524adf', '2021-03-26 06:59:44'),
(2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '4e4c048a5e5583b4d64be8d8d78bc92e', '2021-03-26 08:50:13'),
(3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36 Edg/91.0.864.59', 'b532bb0125fbac9e6e36e5342861f751', '2021-06-28 09:56:14');

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups`
--

CREATE TABLE `auth_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_groups`
--

INSERT INTO `auth_groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Site Administrator'),
(2, 'user', 'Regular User');

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_permissions`
--

CREATE TABLE `auth_groups_permissions` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_groups_permissions`
--

INSERT INTO `auth_groups_permissions` (`group_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`group_id`, `user_id`) VALUES
(1, 4),
(2, 3),
(2, 9),
(2, 10),
(2, 12),
(2, 13),
(2, 14),
(2, 15);

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `email`, `user_id`, `date`, `success`) VALUES
(1, '::1', 'yasir', NULL, '2021-03-26 06:46:21', 0),
(2, '::1', 'localmenoreh@gmail.com', 3, '2021-03-26 06:59:52', 1),
(3, '::1', 'yasir', NULL, '2021-03-26 08:30:51', 0),
(4, '::1', 'localmenoreh@gmail.com', 3, '2021-03-26 08:35:49', 1),
(5, '::1', 'localmenoreh@gmail.com', 3, '2021-03-26 08:36:22', 1),
(6, '::1', 'yasir123983@gmail.com', 4, '2021-03-26 08:50:26', 1),
(7, '::1', 'yasir123983@gmail.com', 4, '2021-03-27 02:39:35', 1),
(8, '::1', 'localmenoreh@gmail.com', 3, '2021-03-27 06:56:22', 1),
(9, '::1', 'yasir123983@gmail.com', 4, '2021-03-27 10:52:23', 1),
(10, '::1', 'yasir123983@gmail.com', 4, '2021-03-27 11:34:49', 1),
(11, '::1', 'yasir123983@gmail.com', 4, '2021-03-27 19:44:25', 1),
(12, '::1', 'yasir123983@gmail.com', 4, '2021-03-28 02:12:54', 1),
(13, '::1', 'yasir123983@gmail.com', 4, '2021-03-28 21:29:33', 1),
(14, '::1', 'yasir123983@gmail.com', 4, '2021-03-29 22:52:53', 1),
(15, '::1', 'yasir123983@gmail.com', 4, '2021-03-30 02:56:03', 1),
(16, '::1', 'yasir123983@gmail.com', 4, '2021-03-30 21:00:12', 1),
(17, '::1', 'yasir123983@gmail.com', 4, '2021-03-31 06:55:47', 1),
(18, '::1', 'yasir123983@gmail.com', 4, '2021-03-31 21:23:46', 1),
(19, '::1', 'yasir123983@gmail.com', 4, '2021-04-02 22:06:21', 1),
(20, '::1', 'yasiryas', NULL, '2021-04-02 23:23:16', 0),
(21, '::1', 'yasir123983@gmail.com', 4, '2021-04-02 23:23:23', 1),
(22, '::1', 'yasiryas', NULL, '2021-04-02 23:36:40', 0),
(23, '::1', 'yasir123983@gmail.com', 4, '2021-04-02 23:36:47', 1),
(24, '::1', 'yasir123983@gmail.com', NULL, '2021-04-02 23:38:17', 0),
(25, '::1', 'yasir123983@gmail.com', 4, '2021-04-02 23:38:24', 1),
(26, '::1', 'yasir123983@gmail.com', 4, '2021-04-02 23:40:21', 1),
(27, '::1', 'yasiryas', NULL, '2021-04-03 00:41:36', 0),
(28, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 00:41:40', 1),
(29, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 00:57:13', 1),
(30, '::1', 'yasiryas', NULL, '2021-04-03 05:22:04', 0),
(31, '::1', 'yasiryas', NULL, '2021-04-03 05:22:14', 0),
(32, '::1', 'yasiryas', NULL, '2021-04-03 05:23:39', 0),
(33, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 05:23:49', 1),
(34, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 05:26:22', 1),
(35, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 05:40:03', 1),
(36, '::1', 'yasiryas', NULL, '2021-04-03 05:43:40', 0),
(37, '::1', 'yasiryas', NULL, '2021-04-03 05:43:47', 0),
(38, '::1', 'yasiryas', NULL, '2021-04-03 05:43:54', 0),
(39, '::1', 'yasiryas', NULL, '2021-04-03 05:44:02', 0),
(40, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 05:44:17', 1),
(41, '::1', 'yasiryas', NULL, '2021-04-03 06:39:37', 0),
(42, '::1', 'yasiryas', NULL, '2021-04-03 06:39:59', 0),
(43, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 06:40:07', 1),
(44, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 06:59:33', 1),
(45, '::1', 'yasiryas', NULL, '2021-04-03 07:27:22', 0),
(46, '::1', 'yasiryas', NULL, '2021-04-03 07:27:30', 0),
(47, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 07:27:38', 1),
(48, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 07:34:42', 1),
(49, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 07:36:49', 1),
(50, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 07:53:40', 1),
(51, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 07:59:07', 1),
(52, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 07:59:57', 1),
(53, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 08:01:56', 1),
(54, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 08:02:04', 1),
(55, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 08:04:11', 1),
(56, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 08:08:31', 1),
(57, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 08:11:52', 1),
(58, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 08:27:16', 1),
(59, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 08:29:13', 1),
(60, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 08:31:12', 1),
(61, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 23:17:20', 1),
(62, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 23:18:49', 1),
(63, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 23:20:55', 1),
(64, '::1', 'yasir123983@gmail.com', 4, '2021-04-03 23:26:57', 1),
(65, '::1', 'yasir123983@gmail.com', 4, '2021-04-04 09:54:59', 1),
(66, '::1', 'yasir123983@gmail.com', 4, '2021-04-04 10:52:00', 1),
(67, '::1', 'yasir123983@gmail.com', 4, '2021-04-04 12:45:37', 1),
(68, '::1', 'masyasir123@gmail.com', 5, '2021-04-04 13:02:42', 1),
(69, '::1', 'yasir123983@gmail.com', 4, '2021-04-04 13:05:12', 1),
(70, '::1', 'yasir123983@gmail.com', 4, '2021-04-04 13:06:14', 1),
(71, '::1', 'yasir123983@gmail.com', 4, '2021-04-04 13:12:15', 1),
(72, '::1', 'yasir123983@gmail.com', 4, '2021-04-04 21:36:22', 1),
(73, '::1', 'yasir123983@gmail.com', 4, '2021-04-05 02:39:02', 1),
(74, '::1', 'yasir123983@gmail.com', 4, '2021-04-05 05:36:29', 1),
(75, '::1', 'yasir123983@gmail.com', 4, '2021-04-05 09:25:25', 1),
(76, '::1', 'yasir123983@gmail.com', 4, '2021-04-05 17:04:52', 1),
(77, '::1', 'yasir123983@gmail.com', 4, '2021-04-05 22:50:39', 1),
(78, '::1', 'yasir123983@gmail.com', 4, '2021-04-16 08:07:10', 1),
(79, '::1', 'yasir123983@gmail.com', 4, '2021-04-28 21:19:26', 1),
(80, '::1', 'yasir123983@gmail.com', 4, '2021-04-28 21:20:03', 1),
(81, '::1', 'yasir123983@gmail.com', 4, '2021-04-28 21:21:24', 1),
(82, '::1', 'yasir123983@gmail.com', 4, '2021-04-28 21:21:36', 1),
(83, '::1', 'yasir', NULL, '2021-05-07 10:58:02', 0),
(84, '::1', 'yasir123983@gmail.com', 4, '2021-05-07 10:58:15', 1),
(85, '::1', 'yasir123983@gmail.com', 4, '2021-05-09 08:58:48', 1),
(86, '::1', 'yasir123983@gmail.com', 4, '2021-05-27 18:09:31', 1),
(87, '::1', 'yasir123983@gmail.com', 4, '2021-05-27 18:11:16', 1),
(88, '::1', 'yasir123983@gmail.com', 4, '2021-05-29 01:04:08', 1),
(89, '::1', 'yasir123983@gmail.com', 4, '2021-05-29 07:30:56', 1),
(90, '::1', 'yasir123983@gmail.com', 4, '2021-05-29 23:36:05', 1),
(91, '::1', 'yasir123983@gmail.com', 4, '2021-05-30 09:00:05', 1),
(92, '::1', 'yasir123983@gmail.com', 4, '2021-05-30 09:08:47', 1),
(93, '::1', 'yasir123983@gmail.com', 4, '2021-06-01 10:05:19', 1),
(94, '::1', 'yasir123983@gmail.com', 4, '2021-06-05 23:31:11', 1),
(95, '::1', 'yasir123983@gmail.com', 4, '2021-06-06 01:46:09', 1),
(96, '::1', 'yasir123983@gmail.com', 4, '2021-06-06 07:25:29', 1),
(97, '::1', 'yasir123983@gmail.com', 4, '2021-06-06 20:31:53', 1),
(98, '::1', 'yasir123983@gmail.com', 4, '2021-06-07 04:12:28', 1),
(99, '::1', 'yasir123983@gmail.com', 4, '2021-06-07 06:37:57', 1),
(100, '::1', 'yasir123983@gmail.com', 4, '2021-06-07 09:24:13', 1),
(101, '::1', 'yasir123983@gmail.com', 4, '2021-06-07 17:47:43', 1),
(102, '::1', 'yasir123983@gmail.com', 4, '2021-06-07 19:51:00', 1),
(103, '::1', 'yasir123983@gmail.com', 4, '2021-06-08 09:29:39', 1),
(104, '::1', 'yasir123983@gmail.com', 4, '2021-06-08 19:50:15', 1),
(105, '::1', 'yasir123983@gmail.com', 4, '2021-06-09 09:26:51', 1),
(106, '::1', 'yasir123983@gmail.com', 4, '2021-06-09 09:30:14', 1),
(107, '::1', 'yasir123983@gmail.com', 4, '2021-06-25 11:24:36', 1),
(108, '::1', 'yasir123983@gmail.com', 4, '2021-06-25 11:27:45', 1),
(109, '::1', 'yasir123983@gmail.com', 4, '2021-06-25 11:29:59', 1),
(110, '::1', 'yasir123983@gmail.com', 4, '2021-06-25 11:44:56', 1),
(111, '::1', 'yasir123983@gmail.com', 4, '2021-06-25 21:29:09', 1),
(112, '::1', 'yasir123983@gmail.com', 4, '2021-06-25 21:29:15', 1),
(113, '::1', 'yasir123983@gmail.com', 4, '2021-06-28 09:22:24', 1),
(114, '::1', 'yasir123983@gmail.com', 4, '2021-06-28 09:23:47', 1),
(115, '::1', 'yasir123983@gmail.com', 4, '2021-06-28 09:27:58', 1),
(116, '::1', 'yasir', NULL, '2021-06-28 09:30:28', 0),
(117, '::1', 'yasir123983@gmail.com', 4, '2021-06-28 09:30:38', 1),
(118, '::1', 'localmenoreh@gmail.com', NULL, '2021-06-28 09:40:40', 0),
(119, '::1', 'yasir', NULL, '2021-06-28 09:41:06', 0),
(120, '::1', 'yasir', NULL, '2021-06-28 09:41:07', 0),
(121, '::1', 'yasiryas2021@gmail.com', 12, '2021-06-28 09:56:21', 1),
(122, '::1', 'localmenoreh@gmail.com', 3, '2021-06-28 09:58:21', 1),
(123, '::1', 'yasir123983@gmail.com', 4, '2021-06-28 09:58:37', 1),
(124, '::1', 'yasir123983@gmail.com', 4, '2021-06-28 10:06:09', 1),
(125, '::1', 'yasir123983@gmail.com', 4, '2021-06-28 10:13:01', 1),
(126, '::1', 'yasir123983@gmail.com', 4, '2021-06-29 21:41:43', 1),
(127, '::1', 'yasir123983@gmail.com', 4, '2021-06-29 22:00:40', 1),
(128, '::1', 'localmenoreh@gmail.com', NULL, '2021-06-29 22:24:46', 0),
(129, '::1', 'yasiryasdks@gmail.com', NULL, '2021-06-29 22:25:13', 0),
(130, '::1', 'abizgelap2021@gmail.com', NULL, '2021-06-29 22:26:09', 0),
(131, '::1', 'localmenoreh@gmail.com', NULL, '2021-06-29 22:26:28', 0),
(132, '::1', 'localmenoreh@gmail.com', 3, '2021-06-29 22:28:16', 1),
(133, '::1', 'localmenoreh@gmail.com', 3, '2021-06-29 22:28:33', 1),
(134, '::1', 'yasir123983@gmail.com', 4, '2021-06-29 22:34:08', 1),
(135, '::1', 'yasir123983@gmail.com', 4, '2021-06-30 02:17:47', 1),
(136, '::1', 'yasir123983@gmail.com', 4, '2021-06-30 09:36:21', 1),
(137, '::1', 'yasir123983@gmail.com', 4, '2021-06-30 21:13:41', 1),
(138, '::1', 'yasir123983@gmail.com', 4, '2021-07-03 06:47:26', 1),
(139, '::1', 'yasir123983@gmail.com', 4, '2021-07-03 09:22:23', 1),
(140, '::1', 'yasir123983@gmail.com', 4, '2021-07-03 23:11:55', 1),
(141, '::1', 'yasir123983@gmail.com', 4, '2021-07-04 04:45:27', 1),
(142, '::1', 'yasir123983@gmail.com', 4, '2021-07-04 11:55:50', 1),
(143, '::1', 'yasir123983@gmail.com', 4, '2021-07-04 21:06:31', 1),
(144, '::1', 'yasir123983@gmail.com', 4, '2021-07-05 06:43:16', 1),
(145, '::1', 'yasir123983@gmail.com', 4, '2021-07-05 17:22:24', 1),
(146, '::1', 'yasir123983@gmail.com', 4, '2021-07-05 20:12:52', 1),
(147, '::1', 'yasir123983@gmail.com', 4, '2021-07-06 09:04:19', 1),
(148, '::1', 'yasir123983@gmail.com', 4, '2021-07-10 10:38:53', 1),
(149, '::1', 'yasir123983@gmail.com', 4, '2021-07-10 23:46:32', 1),
(150, '::1', 'yasir123983@gmail.com', 4, '2021-07-11 01:52:26', 1),
(151, '::1', 'yasir123983@gmail.com', 4, '2021-07-11 10:26:57', 1),
(152, '::1', 'yasir123983@gmail.com', 4, '2021-07-14 22:19:00', 1),
(153, '::1', 'yasir123983@gmail.com', 4, '2021-07-22 22:22:53', 1),
(154, '::1', 'yasir123983@gmail.com', 4, '2021-07-23 21:44:54', 1),
(155, '::1', 'yasir123983@gmail.com', 4, '2021-07-25 09:26:36', 1),
(156, '::1', 'yasir123983@gmail.com', 4, '2021-08-01 20:09:49', 1),
(157, '::1', 'yasir123983@gmail.com', 4, '2021-08-05 21:38:27', 1),
(158, '::1', 'yasir123983@gmail.com', 4, '2021-08-08 06:47:52', 1),
(159, '::1', 'yasir123983@gmail.com', 4, '2021-08-08 10:10:34', 1),
(160, '::1', 'yasir123983@gmail.com', 4, '2021-08-08 23:38:10', 1),
(161, '::1', 'yasir123983@gmail.com', 4, '2021-08-10 22:37:06', 1),
(162, '::1', 'yasir123983@gmail.com', 4, '2021-08-12 08:06:33', 1),
(163, '::1', 'yasir123983@gmail.com', 4, '2021-08-12 21:14:25', 1),
(164, '::1', 'yasir123983@gmail.com', 4, '2021-08-13 09:00:28', 1),
(165, '::1', 'yasir123983@gmail.com', 4, '2021-08-13 09:00:28', 1),
(166, '::1', 'yasir123983@gmail.com', 4, '2021-08-13 11:33:57', 1),
(167, '::1', 'yasir123983@gmail.com', 4, '2021-08-14 04:47:42', 1),
(168, '::1', 'yasir123983@gmail.com', 4, '2021-08-15 09:06:37', 1),
(169, '::1', 'yasir123983@gmail.com', 4, '2021-08-17 07:32:35', 1),
(170, '::1', 'yasir123983@gmail.com', 4, '2021-08-18 10:48:40', 1),
(171, '::1', 'yasir123983@gmail.com', 4, '2021-08-20 06:32:33', 1),
(172, '::1', 'yasir123983@gmail.com', 4, '2021-08-20 06:32:43', 1),
(173, '::1', 'yasir123983@gmail.com', 4, '2021-08-22 09:57:40', 1),
(174, '::1', 'yasir123983@gmail.com', 4, '2021-08-25 01:10:21', 1),
(175, '::1', 'yasir123983@gmail.com', 4, '2021-08-26 07:33:26', 1),
(176, '::1', 'yasir123983@gmail.com', 4, '2021-08-27 07:25:23', 1),
(177, '::1', 'yasir1239833@gmail.com', NULL, '2021-08-27 09:54:34', 0),
(178, '::1', 'yasir123983@gmail.com', 4, '2021-08-27 09:55:24', 1),
(179, '::1', 'yasiryas1', NULL, '2021-08-27 09:55:56', 0),
(180, '::1', 'yasiryasdd', NULL, '2021-08-27 09:56:21', 0),
(181, '::1', 'yasir123983@gmail.com', 4, '2021-08-27 09:57:17', 1),
(182, '::1', 'abizgelap@gmail.com', 9, '2021-08-27 10:00:53', 0),
(183, '::1', 'yasir123983@gmail.com', 4, '2021-08-27 10:01:22', 1),
(184, '::1', 'okkay', 15, '2021-08-30 19:56:27', 0),
(185, '::1', 'yasir123983@gmail.com', 4, '2021-08-30 19:56:38', 1),
(186, '::1', 'yasir123983@gmail.com', 4, '2021-09-02 01:25:03', 1),
(187, '::1', 'yasir123983@gmail.com', 4, '2021-09-02 04:10:01', 1),
(188, '::1', 'yasir123983@gmail.com', 4, '2021-09-11 12:14:33', 1),
(189, '::1', 'yasir123983@gmail.com', 4, '2021-09-12 17:46:39', 1),
(190, '::1', 'yasir123983@gmail.com', 4, '2021-09-21 06:51:14', 1),
(191, '::1', 'yasir123983@gmail.com', 4, '2021-09-21 20:42:33', 1),
(192, '::1', 'yasir123983@gmail.com', 4, '2021-09-22 08:00:03', 1),
(193, '::1', 'okkay', 15, '2021-09-22 08:05:21', 0),
(194, '::1', 'masyasir123@gmail.com', NULL, '2021-09-22 08:05:42', 0),
(195, '::1', 'yasir', NULL, '2021-09-22 08:06:18', 0),
(196, '::1', 'localmenoreh@gmail.com', NULL, '2021-09-22 08:07:33', 0),
(197, '::1', 'yasir', NULL, '2021-09-22 08:07:45', 0),
(198, '::1', 'yasir', NULL, '2021-09-22 08:07:58', 0),
(199, '::1', 'localmenoreh@gmail.com', 3, '2021-09-22 08:14:47', 1),
(200, '::1', 'localmenoreh@gmail.com', 3, '2021-09-22 08:23:16', 1),
(201, '::1', 'yasir123983@gmail.com', 4, '2021-09-22 08:24:33', 1),
(202, '::1', 'yasir123983@gmail.com', 4, '2021-09-22 08:26:22', 1),
(203, '::1', 'yasir123983@gmail.com', 4, '2021-09-22 08:31:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions`
--

CREATE TABLE `auth_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_permissions`
--

INSERT INTO `auth_permissions` (`id`, `name`, `description`) VALUES
(1, 'manage-user', 'Manage All User'),
(2, 'manage-profile', 'Manage user\'s profile');

-- --------------------------------------------------------

--
-- Table structure for table `auth_reset_attempts`
--

CREATE TABLE `auth_reset_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_reset_attempts`
--

INSERT INTO `auth_reset_attempts` (`id`, `email`, `ip_address`, `user_agent`, `token`, `created_at`) VALUES
(1, 'localmenoreh@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '136143225205a5d54f2df6249d3fe549', '2021-03-26 07:31:45'),
(2, 'localmenoreh@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '06438b49cd9b50fc655a69ab7f4bfa0e', '2021-03-26 08:35:42'),
(3, 'localmenoreh@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '7be8794c31cc0f0108e12635132fdda3', '2021-03-27 06:56:00'),
(4, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', 'a94fbb45d4c5f501c3d15bf5e23b54e5', '2021-04-02 23:22:21'),
(5, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', 'a94fbb45d4c5f501c3d15bf5e23b54e5', '2021-04-02 23:22:38'),
(6, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', 'a94fbb45d4c5f501c3d15bf5e23b54e5', '2021-04-02 23:22:56'),
(7, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', 'ffe83239ad42eeece8abfc0154b358b4', '2021-04-02 23:26:03'),
(8, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', 'dbaecd9529b7ca2ade2a487e44d64080', '2021-04-02 23:39:09'),
(9, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '17f01e014a952b262f31ad84aac5840e', '2021-04-02 23:43:10'),
(10, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '17f01e014a952b262f31ad84aac5840e', '2021-04-03 00:42:50'),
(11, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '17f01e014a952b262f31ad84aac5840e', '2021-04-03 00:43:09'),
(12, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '17f01e014a952b262f31ad84aac5840e', '2021-04-03 00:43:40'),
(13, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '17f01e014a952b262f31ad84aac5840e', '2021-04-03 00:44:49'),
(14, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '17f01e014a952b262f31ad84aac5840e', '2021-04-03 00:45:21'),
(15, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '17f01e014a952b262f31ad84aac5840e', '2021-04-03 00:45:39'),
(16, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '7fba285ac9dc7f16eb96979c285a8822', '2021-04-03 00:47:59'),
(17, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '7fba285ac9dc7f16eb96979c285a8822', '2021-04-03 00:49:00'),
(18, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '7fba285ac9dc7f16eb96979c285a8822', '2021-04-03 00:53:27'),
(19, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '2eed12489f24c335263865d4f307f2a7', '2021-04-03 00:55:09'),
(20, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.63', '36db964561329152158777e5b385c4b5', '2021-04-03 00:56:34'),
(21, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'b5f9e011e1a8a6703d17479bd854170b', '2021-04-03 05:23:11'),
(22, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '263ba63fce99d3618fea7efbfb589fd3', '2021-04-03 05:25:56'),
(23, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '03b5ddf653c5a3f73f486ba495833926', '2021-04-03 05:29:13'),
(24, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '89eec5bc91755137e92803e65f37b900', '2021-04-03 05:39:50'),
(25, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '52a0eaea96428bb79a144d032d7d7b4d', '2021-04-03 05:42:37'),
(26, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'eab89f8abb26ccd49723edc4bcb228cf', '2021-04-03 05:45:13'),
(27, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'be66dc21f28671823721d8632bee7a1e', '2021-04-03 05:51:21'),
(28, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '682a36dfbbf85e4616c7f06fd0b4e85d', '2021-04-03 05:53:46'),
(29, 'localmenoreh@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '47a404e3f8e20d9a395bcedc6848655f', '2021-04-03 06:41:32'),
(30, 'localmenoreh@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '47a404e3f8e20d9a395bcedc6848655f', '2021-04-03 06:41:48'),
(31, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '47a404e3f8e20d9a395bcedc6848655f', '2021-04-03 06:42:10'),
(32, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '03d1e9cd1f6c9b44b3f9083c0db37c48', '2021-04-03 06:46:45'),
(33, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '03d1e9cd1f6c9b44b3f9083c0db37c48', '2021-04-03 06:47:00'),
(34, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '03d1e9cd1f6c9b44b3f9083c0db37c48', '2021-04-03 06:47:33'),
(35, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'c0373472595567de208e97a911ead142', '2021-04-03 06:55:11'),
(36, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'c0373472595567de208e97a911ead142', '2021-04-03 06:56:13'),
(37, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'c0373472595567de208e97a911ead142', '2021-04-03 06:57:56'),
(38, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'f5fdd28126f20e03a374e050f0712699', '2021-04-03 06:59:22'),
(39, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'dc1af565872b6e1f062cf77fcbbf55b7', '2021-04-03 07:22:37'),
(40, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'dc1af565872b6e1f062cf77fcbbf55b7', '2021-04-03 07:23:24'),
(41, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'dc1af565872b6e1f062cf77fcbbf55b7', '2021-04-03 07:23:51'),
(42, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '87a05063b768fb1f871280d633aaecff', '2021-04-03 07:26:03'),
(43, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'cf493dd45f0f6d82110f2ed02795f501', '2021-04-03 07:29:13'),
(44, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'fafd0f499f495821aaf6226c857bd394', '2021-04-03 07:36:40'),
(45, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '4b0af12d76edea3c06a45950d617f680', '2021-04-03 07:53:31'),
(46, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '05d682c8fbaf69ed7641c5ec32bffbe4', '2021-04-03 07:58:26'),
(47, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '1f0c89e8b559bf3e91ddcffb03336477', '2021-04-03 07:59:49'),
(48, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'f9f8f95b54bd5244b72bebf155734220', '2021-04-03 08:01:45'),
(49, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '46141b21c51a16e6d4d2283530d0bb59', '2021-04-03 08:04:00'),
(50, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'fd5251ab5edb396fa0b3e6a1dfdb5a2a', '2021-04-03 08:08:21'),
(51, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '5775e146f0bd77173f732fba7afdb2dd', '2021-04-03 08:11:37'),
(52, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'e70e404ba568202f23b51d001f5c855b', '2021-04-03 08:24:43'),
(53, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'e70e404ba568202f23b51d001f5c855b', '2021-04-03 08:24:44'),
(54, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'e70e404ba568202f23b51d001f5c855b', '2021-04-03 08:25:07'),
(55, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'e70e404ba568202f23b51d001f5c855b', '2021-04-03 08:25:51'),
(56, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'e70e404ba568202f23b51d001f5c855b', '2021-04-03 08:26:17'),
(57, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'cef2ec58dbca1c5dbd08a5d7d15c159e', '2021-04-03 08:27:07'),
(58, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '3bd7020fbf08dfb04b8a288a945cb5ac', '2021-04-03 08:29:04'),
(59, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '759439c501210e74e66f43b7bc9b77c5', '2021-04-03 08:31:05'),
(60, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '7f5d67b9c2b4c71e166dcaecdb00fe38', '2021-04-03 23:18:23'),
(61, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', '7f5d67b9c2b4c71e166dcaecdb00fe38', '2021-04-03 23:18:37'),
(62, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'b3141d02132615dde1a30c04eec1014e', '2021-04-03 23:20:47'),
(63, 'yasir123983@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36 Edg/89.0.774.68', 'bc4db2b1d3cfb1bb6f5f32a3e27e5e28', '2021-04-03 23:26:48'),
(64, 'localmenoreh@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36 Edg/91.0.864.59', '2ae8ba393ba72bd3fae13a9d3087e15c', '2021-06-28 09:57:58'),
(65, 'localmenoreh@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36 Edg/91.0.864.59', '2ae8ba393ba72bd3fae13a9d3087e15c', '2021-06-28 09:58:11'),
(66, 'localmenoreh@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36 Edg/91.0.864.59', 'fda0dd6f992b6d6833965e480a9518cc', '2021-06-29 22:28:04'),
(67, 'localmenoreh@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Safari/537.36', 'e4ab5ff13ac5afbe33bcde431c129443', '2021-09-22 08:14:35');

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_users_permissions`
--

CREATE TABLE `auth_users_permissions` (
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `data_gejala`
--

CREATE TABLE `data_gejala` (
  `id_gejala` int(11) NOT NULL,
  `kategori` varchar(64) NOT NULL,
  `gejala` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_gejala`
--

INSERT INTO `data_gejala` (`id_gejala`, `kategori`, `gejala`) VALUES
(18, 'Kulit', 'Normal'),
(19, 'Kulit ', 'Membengkak'),
(20, 'Kotoran ', 'Sedikit'),
(21, 'Kotoran', 'Encer'),
(22, 'Kotoran', 'Normal'),
(23, 'Perut', 'Kosong'),
(24, 'Perut', 'Berisi');

-- --------------------------------------------------------

--
-- Table structure for table `data_penyakit`
--

CREATE TABLE `data_penyakit` (
  `id_penyakit` int(11) NOT NULL,
  `penyakit` varchar(255) NOT NULL,
  `obat` varchar(255) NOT NULL,
  `solusi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_penyakit`
--

INSERT INTO `data_penyakit` (`id_penyakit`, `penyakit`, `obat`, `solusi`) VALUES
(4, 'Sembelit', 'Rebung', 'Tambah makanan berserat'),
(5, 'Diare', 'Rebung', 'Dikasih makanan yang bernutrisi'),
(6, 'Kembung', 'Rebung', 'Makanan yang diberikan harus kering'),
(7, 'Scabies', 'Wormmectin', 'Disuntik dengan obat dan diberi salep'),
(8, 'Sehat', 'Vitamin', 'Diberikan vitamin agar menambah imun ');

-- --------------------------------------------------------

--
-- Table structure for table `data_sample`
--

CREATE TABLE `data_sample` (
  `id_sample` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `id_penyakit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `data_sample`
--

INSERT INTO `data_sample` (`id_sample`, `id_gejala`, `id_penyakit`) VALUES
(8, 19, 7),
(9, 22, 7),
(10, 24, 7),
(11, 18, 4),
(12, 20, 4),
(14, 18, 5),
(15, 21, 5),
(16, 23, 5),
(17, 18, 8),
(18, 22, 8),
(19, 24, 8),
(20, 18, 4),
(21, 20, 4),
(22, 24, 4),
(23, 19, 7),
(24, 22, 7),
(25, 24, 7),
(26, 18, 5),
(27, 21, 5),
(28, 24, 5),
(29, 18, 6),
(30, 22, 6),
(31, 23, 6),
(32, 18, 5),
(33, 21, 5),
(34, 24, 5),
(35, 18, 4),
(36, 20, 4),
(37, 24, 4),
(38, 18, 6),
(39, 22, 6),
(40, 23, 6),
(41, 18, 8),
(42, 22, 8),
(43, 24, 8),
(44, 19, 7),
(45, 22, 7),
(46, 24, 7),
(47, 18, 4),
(48, 20, 4),
(49, 24, 4),
(50, 18, 5),
(51, 21, 5),
(52, 23, 5),
(53, 18, 6),
(54, 22, 6),
(55, 23, 6),
(56, 18, 4),
(57, 20, 4),
(58, 24, 4),
(59, 18, 8),
(60, 22, 8),
(61, 24, 8),
(62, 18, 6),
(63, 22, 6),
(64, 23, 6),
(65, 18, 5),
(66, 21, 5),
(67, 23, 5),
(68, 24, 4);

-- --------------------------------------------------------

--
-- Table structure for table `decision_tree`
--

CREATE TABLE `decision_tree` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `akar` varchar(255) NOT NULL,
  `keputusan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `entropy`
--

CREATE TABLE `entropy` (
  `id` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `entropy` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `entropy`
--

INSERT INTO `entropy` (`id`, `id_gejala`, `entropy`) VALUES
(1, 21, 0),
(2, 22, 0),
(3, 22, 0),
(4, 22, 0),
(5, 20, 0),
(6, 19, 0),
(7, 18, 0),
(8, 18, 0),
(9, 18, 0),
(10, 18, 0),
(11, 24, 0),
(12, 24, 0),
(13, 24, 0),
(14, 24, 0),
(15, 23, 0),
(16, 23, 0),
(17, 21, 0),
(18, 22, 0.52877123795494),
(19, 22, 1.0498609162048),
(20, 22, 1.5709505944547),
(21, 20, 0),
(22, 19, 0),
(23, 18, 0),
(24, 18, 0),
(25, 18, 0),
(26, 18, 0),
(27, 24, 0),
(28, 24, 0),
(29, 24, 0),
(30, 24, 0),
(31, 23, 0),
(32, 23, 0),
(33, 21, 0),
(34, 22, 0),
(35, 22, 0),
(36, 22, 0),
(37, 20, 0),
(38, 19, 0),
(39, 18, 0),
(40, 18, 0),
(41, 18, 0),
(42, 18, 0),
(43, 24, 0),
(44, 24, 0),
(45, 24, 0),
(46, 24, 0),
(47, 23, 0),
(48, 23, 0),
(49, 21, 0),
(50, 22, 0),
(51, 22, 0),
(52, 22, 0),
(53, 20, 0),
(54, 19, 0),
(55, 18, 0),
(56, 18, 0),
(57, 18, 0),
(58, 18, 0),
(59, 24, 0),
(60, 24, 0),
(61, 24, 0),
(62, 24, 0),
(63, 23, 0),
(64, 23, 0),
(65, 21, 0),
(66, 22, 0),
(67, 22, 0),
(68, 22, 0),
(69, 20, 0),
(70, 19, 0),
(71, 18, 0.51927492540088),
(72, 18, 1.0104426527539),
(73, 18, 1.4520603599061),
(74, 18, 1.971335285307),
(75, 24, 0),
(76, 24, 0),
(77, 24, 0),
(78, 24, 0),
(79, 23, 0),
(80, 23, 0),
(81, 21, 0),
(82, 22, 0),
(83, 22, 0),
(84, 22, 0),
(85, 20, 0),
(86, 19, 0),
(87, 18, 0),
(88, 18, 0),
(89, 18, 0),
(90, 18, 0),
(91, 24, 0.4154522643294),
(92, 24, 0.90363931450323),
(93, 24, 1.3918263646771),
(94, 24, 1.9220231428516),
(95, 23, 0),
(96, 23, 0),
(97, 21, 0),
(98, 22, 0),
(99, 22, 0),
(100, 22, 0),
(101, 20, 0),
(102, 19, 0),
(103, 18, 0),
(104, 18, 0),
(105, 18, 0),
(106, 18, 0),
(107, 24, 0),
(108, 24, 0),
(109, 24, 0),
(110, 24, 0),
(111, 23, 0.52388246628705),
(112, 23, 0.98522813603425);

-- --------------------------------------------------------

--
-- Table structure for table `gain`
--

CREATE TABLE `gain` (
  `id` int(11) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `gain` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gain`
--

INSERT INTO `gain` (`id`, `kategori`, `gain`) VALUES
(1, 'Kotoran', 1.5),
(2, 'Kulit ', 0.60984030471643),
(3, 'Perut', 0.69133040676184);

-- --------------------------------------------------------

--
-- Table structure for table `gain_ratio`
--

CREATE TABLE `gain_ratio` (
  `id` int(11) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `gain_ratio` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gain_ratio`
--

INSERT INTO `gain_ratio` (`id`, `kategori`, `gain_ratio`) VALUES
(1, 'Kotoran', 1),
(2, 'Kulit ', 1),
(3, 'Perut', 0.74012851931214);

-- --------------------------------------------------------

--
-- Table structure for table `hasil`
--

CREATE TABLE `hasil` (
  `id_hasil` int(11) NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_penyakit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2017-11-20-223112', 'Myth\\Auth\\Database\\Migrations\\CreateAuthTables', 'default', 'Myth\\Auth', 1616755764, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mining_entropy`
--

CREATE TABLE `mining_entropy` (
  `id` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `id_penyakit` int(11) NOT NULL,
  `total` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mining_entropy`
--

INSERT INTO `mining_entropy` (`id`, `id_gejala`, `id_penyakit`, `total`) VALUES
(1, 21, 5, 5),
(2, 22, 6, 4),
(3, 22, 7, 3),
(4, 22, 8, 3),
(5, 20, 4, 5),
(6, 19, 7, 3),
(7, 18, 5, 5),
(8, 18, 6, 4),
(9, 18, 8, 3),
(10, 18, 4, 5),
(11, 24, 5, 2),
(12, 24, 7, 3),
(13, 24, 8, 3),
(14, 24, 4, 5),
(15, 23, 5, 3),
(16, 23, 6, 4);

-- --------------------------------------------------------

--
-- Table structure for table `mining_gain`
--

CREATE TABLE `mining_gain` (
  `id` int(11) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `gain` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mining_gain`
--

INSERT INTO `mining_gain` (`id`, `kategori`, `gain`) VALUES
(1, 'Kotoran', 0),
(2, 'Kotoran', 0.78547529722735),
(3, 'Kotoran ', 0),
(4, 'Kulit ', 0),
(5, 'Kulit', 1.6756349925109),
(6, 'Perut', 1.2493150428535),
(7, 'Perut', 0.34482984761199);

-- --------------------------------------------------------

--
-- Table structure for table `mining_kasus`
--

CREATE TABLE `mining_kasus` (
  `id` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `total` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mining_kasus`
--

INSERT INTO `mining_kasus` (`id`, `id_gejala`, `total`) VALUES
(1, 21, 5),
(2, 22, 10),
(3, 20, 5),
(4, 19, 3),
(5, 18, 17),
(6, 24, 13),
(7, 23, 7);

-- --------------------------------------------------------

--
-- Table structure for table `mining_sample`
--

CREATE TABLE `mining_sample` (
  `id_sample` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `id_penyakit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mining_sample`
--

INSERT INTO `mining_sample` (`id_sample`, `id_gejala`, `id_penyakit`) VALUES
(8, 19, 7),
(9, 22, 7),
(10, 24, 7),
(11, 18, 4),
(12, 20, 4),
(14, 18, 5),
(15, 21, 5),
(16, 23, 5),
(17, 18, 8),
(18, 22, 8),
(19, 24, 8),
(20, 18, 4),
(21, 20, 4),
(22, 24, 4),
(23, 19, 7),
(24, 22, 7),
(25, 24, 7),
(26, 18, 5),
(27, 21, 5),
(28, 24, 5),
(29, 18, 6),
(30, 22, 6),
(31, 23, 6),
(32, 18, 5),
(33, 21, 5),
(34, 24, 5),
(35, 18, 4),
(36, 20, 4),
(37, 24, 4),
(38, 18, 6),
(39, 22, 6),
(40, 23, 6),
(41, 18, 8),
(42, 22, 8),
(43, 24, 8),
(44, 19, 7),
(45, 22, 7),
(46, 24, 7),
(47, 18, 4),
(48, 20, 4),
(49, 24, 4),
(50, 18, 5),
(51, 21, 5),
(52, 23, 5),
(53, 18, 6),
(54, 22, 6),
(55, 23, 6),
(56, 18, 4),
(57, 20, 4),
(58, 24, 4),
(59, 18, 8),
(60, 22, 8),
(61, 24, 8),
(62, 18, 6),
(63, 22, 6),
(64, 23, 6),
(65, 18, 5),
(66, 21, 5),
(67, 23, 5),
(68, 24, 4);

-- --------------------------------------------------------

--
-- Table structure for table `mining_splitinfo`
--

CREATE TABLE `mining_splitinfo` (
  `id` int(11) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `splitinfo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mining_splitinfo`
--

INSERT INTO `mining_splitinfo` (`id`, `kategori`, `splitinfo`) VALUES
(1, 'Kotoran', 0.5),
(2, 'Kotoran', 0.5),
(3, 'Kotoran ', 0.5),
(4, 'Kulit ', 0.41054483912493),
(5, 'Kulit', 0.19929546559147),
(6, 'Perut', 0.40396744488508),
(7, 'Perut', 0.53010061049042);

-- --------------------------------------------------------

--
-- Table structure for table `pemangkasan`
--

CREATE TABLE `pemangkasan` (
  `id` int(11) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `gejala` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) NOT NULL DEFAULT 'default.svg',
  `password_hash` varchar(255) NOT NULL,
  `reset_hash` varchar(255) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `activate_hash` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `fullname`, `user_image`, `password_hash`, `reset_hash`, `reset_at`, `reset_expires`, `activate_hash`, `status`, `status_message`, `active`, `force_pass_reset`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'localmenoreh@gmail.com', 'yasir', 'Muhammad Yasir', 'default.svg', '$2y$10$ppc734GWeQ1LWIsICKbTo.ECttHfcNquCOroCrLts.T3QDPtwqbv.', NULL, '2021-09-22 08:14:36', NULL, NULL, NULL, NULL, 1, 0, '2021-03-26 06:58:59', '2021-09-22 08:22:56', NULL),
(4, 'yasir123983@gmail.com', 'yasiryas', 'Muhammad Yasir', 'default.svg', '$2y$10$D7WoSVSs8ilbNIdkhe.kX.zqSiUprs5qBCZJVmaZLO/uMGOlD6z8y', '48085379c77eedc9eb42a6b96dcf5bff', '2021-04-03 23:26:48', '2021-06-28 10:47:22', NULL, NULL, NULL, 1, 0, '2021-03-26 08:49:25', '2021-06-28 09:47:22', NULL),
(9, 'abizgelap@gmail.com', 'abizgelap', NULL, 'default.svg', '$2y$10$K4pHK2Ma4UaXKv6LClvvb.svYgi/jGP0R43yVCHGUg8tpxet3tbVK', NULL, NULL, NULL, '6fc82e7393ecb91c759dccf4cb80ce98', NULL, NULL, 0, 0, '2021-04-04 13:11:31', '2021-04-04 13:11:31', NULL),
(10, 'yorawaton@gmail.com', 'yoo', NULL, 'default.svg', '$2y$10$eooUIG8JDx4f5FosAiSyzuwCsx75v8ayC9uPK4OrX9hNRoWIGIws6', '8441a98d374732c7e81bdf0c36e446a8', NULL, '2021-06-28 10:46:36', 'efd32d3dd1a32fc3e8c89d115e1ddd12', NULL, NULL, 0, 0, '2021-04-04 13:11:58', '2021-06-28 09:46:36', NULL),
(12, 'yasiryas2021@gmail.com', 'yasiryas1', NULL, 'default.svg', '$2y$10$99q8MMn5MTA3xBG6qvtpKek1CB1zJMBhRUbVwlw76llqULdOzi3lS', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2021-06-28 09:55:47', '2021-06-28 09:56:14', NULL),
(13, 'trihastono@upy.ac.id', 'xxx', NULL, 'default.svg', '$2y$10$qxJJVtlFyXXx4tGLduRow.dCx4KCk9VQa9.sNgaJ5CNc9jT9TSr0.', NULL, NULL, NULL, '58e5fe319d1b69497e0a5aa326b7ab8a', NULL, NULL, 0, 0, '2021-08-27 09:44:01', '2021-08-27 09:44:01', NULL),
(14, 'trihastono.13@gmail.com', 'paktri', NULL, 'default.svg', '$2y$10$5seR9/jOz2Knmwd9TTOCsucihWiAcLYCTszGFSia5apyRzmF5boZO', NULL, NULL, NULL, '65d1b7da94a6fb4b92ce89e3dea2f867', NULL, NULL, 0, 0, '2021-08-27 09:48:08', '2021-08-27 09:48:08', NULL),
(15, 'smpmuh1kalibawang@gmail.com', 'okkay', NULL, 'default.svg', '$2y$10$p/alcfMJ4GhYrtUP8TiBFO.7ZMdST6O1BXdWtWtdE3WoxmqsSqE6a', NULL, NULL, NULL, 'c89e3827e9b859812379c5bd3694c1bf', NULL, NULL, 0, 0, '2021-08-27 17:17:18', '2021-08-27 17:17:18', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups`
--
ALTER TABLE `auth_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD KEY `auth_groups_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `group_id_permission_id` (`group_id`,`permission_id`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`),
  ADD KEY `group_id_user_id` (`group_id`,`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_tokens_user_id_foreign` (`user_id`),
  ADD KEY `selector` (`selector`);

--
-- Indexes for table `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD KEY `auth_users_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `user_id_permission_id` (`user_id`,`permission_id`);

--
-- Indexes for table `data_gejala`
--
ALTER TABLE `data_gejala`
  ADD PRIMARY KEY (`id_gejala`);

--
-- Indexes for table `data_penyakit`
--
ALTER TABLE `data_penyakit`
  ADD PRIMARY KEY (`id_penyakit`);

--
-- Indexes for table `data_sample`
--
ALTER TABLE `data_sample`
  ADD PRIMARY KEY (`id_sample`),
  ADD KEY `id_gejala` (`id_gejala`),
  ADD KEY `id_penyakit` (`id_penyakit`);

--
-- Indexes for table `decision_tree`
--
ALTER TABLE `decision_tree`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent`),
  ADD KEY `keputusan` (`keputusan`);

--
-- Indexes for table `entropy`
--
ALTER TABLE `entropy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_gejala` (`id_gejala`);

--
-- Indexes for table `gain`
--
ALTER TABLE `gain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gain_ratio`
--
ALTER TABLE `gain_ratio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_penyakit` (`id_penyakit`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mining_entropy`
--
ALTER TABLE `mining_entropy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_gejala` (`id_gejala`),
  ADD KEY `id_penyakit` (`id_penyakit`);

--
-- Indexes for table `mining_gain`
--
ALTER TABLE `mining_gain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mining_kasus`
--
ALTER TABLE `mining_kasus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_gejala` (`id_gejala`);

--
-- Indexes for table `mining_sample`
--
ALTER TABLE `mining_sample`
  ADD PRIMARY KEY (`id_sample`),
  ADD KEY `id_gejala` (`id_gejala`),
  ADD KEY `id_penyakit` (`id_penyakit`);

--
-- Indexes for table `mining_splitinfo`
--
ALTER TABLE `mining_splitinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemangkasan`
--
ALTER TABLE `pemangkasan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `auth_groups`
--
ALTER TABLE `auth_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_gejala`
--
ALTER TABLE `data_gejala`
  MODIFY `id_gejala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `data_penyakit`
--
ALTER TABLE `data_penyakit`
  MODIFY `id_penyakit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `data_sample`
--
ALTER TABLE `data_sample`
  MODIFY `id_sample` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `decision_tree`
--
ALTER TABLE `decision_tree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `entropy`
--
ALTER TABLE `entropy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `gain`
--
ALTER TABLE `gain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gain_ratio`
--
ALTER TABLE `gain_ratio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mining_entropy`
--
ALTER TABLE `mining_entropy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `mining_gain`
--
ALTER TABLE `mining_gain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mining_kasus`
--
ALTER TABLE `mining_kasus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mining_sample`
--
ALTER TABLE `mining_sample`
  MODIFY `id_sample` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `mining_splitinfo`
--
ALTER TABLE `mining_splitinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pemangkasan`
--
ALTER TABLE `pemangkasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD CONSTRAINT `auth_groups_permissions_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD CONSTRAINT `auth_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `data_sample`
--
ALTER TABLE `data_sample`
  ADD CONSTRAINT `data_sample_ibfk_1` FOREIGN KEY (`id_gejala`) REFERENCES `data_gejala` (`id_gejala`),
  ADD CONSTRAINT `data_sample_ibfk_2` FOREIGN KEY (`id_penyakit`) REFERENCES `data_penyakit` (`id_penyakit`);

--
-- Constraints for table `decision_tree`
--
ALTER TABLE `decision_tree`
  ADD CONSTRAINT `decision_tree_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `data_gejala` (`id_gejala`),
  ADD CONSTRAINT `decision_tree_ibfk_2` FOREIGN KEY (`keputusan`) REFERENCES `data_penyakit` (`id_penyakit`);

--
-- Constraints for table `entropy`
--
ALTER TABLE `entropy`
  ADD CONSTRAINT `entropy_ibfk_1` FOREIGN KEY (`id_gejala`) REFERENCES `data_gejala` (`id_gejala`);

--
-- Constraints for table `hasil`
--
ALTER TABLE `hasil`
  ADD CONSTRAINT `hasil_ibfk_1` FOREIGN KEY (`id_penyakit`) REFERENCES `data_penyakit` (`id_penyakit`),
  ADD CONSTRAINT `hasil_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `mining_entropy`
--
ALTER TABLE `mining_entropy`
  ADD CONSTRAINT `mining_entropy_ibfk_1` FOREIGN KEY (`id_gejala`) REFERENCES `data_gejala` (`id_gejala`),
  ADD CONSTRAINT `mining_entropy_ibfk_2` FOREIGN KEY (`id_penyakit`) REFERENCES `data_penyakit` (`id_penyakit`);

--
-- Constraints for table `mining_kasus`
--
ALTER TABLE `mining_kasus`
  ADD CONSTRAINT `mining_kasus_ibfk_1` FOREIGN KEY (`id_gejala`) REFERENCES `data_gejala` (`id_gejala`);

--
-- Constraints for table `mining_sample`
--
ALTER TABLE `mining_sample`
  ADD CONSTRAINT `mining_sample_ibfk_1` FOREIGN KEY (`id_penyakit`) REFERENCES `data_penyakit` (`id_penyakit`),
  ADD CONSTRAINT `mining_sample_ibfk_2` FOREIGN KEY (`id_gejala`) REFERENCES `data_gejala` (`id_gejala`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
