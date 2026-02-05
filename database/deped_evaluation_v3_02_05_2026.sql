-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 05, 2026 at 12:12 PM
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
-- Database: `deped_evaluation_v3`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position_applied_id` int(11) DEFAULT NULL,
  `position_group` enum('TEACHING','NON-TEACHING LEVEL I','NON-TEACHING LEVEL II','RELATED TEACHING','HIGHER TEACHING','SCHOOL ADMINISTRATION') NOT NULL DEFAULT 'NON-TEACHING LEVEL I',
  `archive_status` enum('active','archived') DEFAULT 'active',
  `archived_at` timestamp NULL DEFAULT NULL,
  `archive_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_qualifications`
--

CREATE TABLE `applicant_qualifications` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `education_degree` varchar(100) DEFAULT NULL,
  `education_masters_units` int(11) DEFAULT 0,
  `education_doctoral_units` int(11) DEFAULT 0,
  `training_hours` decimal(10,2) DEFAULT 0.00,
  `experience_months` decimal(10,2) DEFAULT 0.00,
  `performance_rating` decimal(5,2) DEFAULT 0.00,
  `outstanding_accomplishments` int(11) DEFAULT 0,
  `application_of_education_level` int(11) DEFAULT 0,
  `application_of_ld_level` int(11) DEFAULT 0,
  `potential_level` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archived_applicants_audit`
--

CREATE TABLE `archived_applicants_audit` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `action` enum('archived','restored') NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `archived_by` varchar(255) DEFAULT NULL,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(100) NOT NULL,
  `object_type` varchar(100) DEFAULT NULL,
  `object_id` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `action`, `object_type`, `object_id`, `user_id`, `session_id`, `ip_address`, `meta`, `created_at`) VALUES
(1, 'draft_saved', 'draft', '1', NULL, 'n53o43r48hc123hcv1f69v8g9q', '::1', '{\"source\":\"save_draft\",\"payload_size\":1214}', '2026-02-03 10:48:24'),
(2, 'draft_saved', 'draft', '2', NULL, 'e4494sqbprvefvvot5krr7sfgu', '::1', '{\"source\":\"save_draft\",\"payload_size\":1244}', '2026-02-04 05:35:11'),
(3, 'draft_loaded', 'draft', '1', NULL, 'e4494sqbprvefvvot5krr7sfgu', '::1', '{\"loaded_from_draft_id\":1}', '2026-02-04 05:55:16'),
(4, 'draft_loaded', 'draft', '2', NULL, 'e4494sqbprvefvvot5krr7sfgu', '::1', '{\"loaded_from_draft_id\":2}', '2026-02-04 05:55:21'),
(5, 'draft_updated', 'draft', '2', NULL, 'e4494sqbprvefvvot5krr7sfgu', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-004\"}', '2026-02-04 06:06:56'),
(6, 'draft_updated', 'draft', '2', NULL, 'e4494sqbprvefvvot5krr7sfgu', '::1', '{\"updated_from_session\":true}', '2026-02-04 06:21:52'),
(7, 'draft_saved', 'draft', '3', NULL, 'kh0fujro7l4afvbfi1d506hpqs', '::1', '{\"source\":\"save_draft\",\"payload_size\":1250}', '2026-02-04 06:50:39'),
(8, 'draft_updated', 'draft', '3', NULL, 'kh0fujro7l4afvbfi1d506hpqs', '::1', '{\"updated_from_session\":true}', '2026-02-04 07:14:07'),
(9, 'draft_updated', 'draft', '3', NULL, 'kh0fujro7l4afvbfi1d506hpqs', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-009\"}', '2026-02-04 07:16:32'),
(10, 'draft_updated', 'draft', '2', NULL, 'kh0fujro7l4afvbfi1d506hpqs', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-006\"}', '2026-02-04 07:21:52'),
(11, 'draft_saved', 'draft', '4', NULL, 'o4ha8ih13um1thaq9seak36r7j', '::1', '{\"source\":\"save_draft\",\"payload_size\":1239}', '2026-02-04 07:35:48'),
(12, 'draft_saved', 'draft', '5', NULL, 'ggupvbfml1snghoioi9aq8jo4u', '::1', '{\"source\":\"save_draft\",\"payload_size\":1245}', '2026-02-04 08:19:56'),
(13, 'draft_saved', 'draft', '6', NULL, '6m41i65632a4k12vi8ppc6i301', '::1', '{\"source\":\"save_draft\",\"payload_size\":1240}', '2026-02-04 08:35:31'),
(14, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:28:02'),
(15, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:28:19'),
(16, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:28:55'),
(17, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:31:37'),
(18, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:33:01'),
(19, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:33:23'),
(20, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:34:57'),
(21, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:35:27'),
(22, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:36:26'),
(23, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:37:14'),
(24, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:37:48'),
(25, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:37:57'),
(26, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:40:39'),
(27, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:41:06'),
(28, 'draft_updated', 'draft', '5', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-003\"}', '2026-02-04 10:42:31'),
(29, 'draft_saved', 'draft', '7', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"source\":\"save_draft\",\"payload_size\":1250}', '2026-02-04 10:43:37'),
(30, 'draft_updated', 'draft', '7', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-008\"}', '2026-02-04 10:45:28'),
(31, 'draft_updated', 'draft', '4', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-001\"}', '2026-02-04 10:46:01'),
(32, 'draft_updated', 'draft', '4', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-001\"}', '2026-02-04 10:48:37'),
(33, 'draft_updated', 'draft', '4', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-001\"}', '2026-02-04 10:49:06'),
(34, 'draft_updated', 'draft', '4', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_application_code\":\"SA-SP3-2026-001\"}', '2026-02-04 10:50:24'),
(35, 'draft_updated', 'draft', '7', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_session\":true}', '2026-02-04 10:50:32'),
(36, 'draft_updated', 'draft', '7', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_session\":true}', '2026-02-04 10:54:44'),
(37, 'draft_updated', 'draft', '7', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_session\":true}', '2026-02-04 12:31:34'),
(38, 'draft_updated', 'draft', '7', NULL, '806v6orkfoa3j0afsclti7gvoo', '::1', '{\"updated_from_session\":true}', '2026-02-04 12:56:44'),
(39, 'draft_saved', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1245}', '2026-02-05 00:00:37'),
(40, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:01:48'),
(41, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:02:43'),
(42, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:04:12'),
(43, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:04:58'),
(44, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:06:15'),
(45, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:07:08'),
(46, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:08:52'),
(47, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:09:37'),
(48, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:11:42'),
(49, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:12:32'),
(50, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:14:58'),
(51, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:16:21'),
(52, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_application_code\":\"NTII-CPII-2026-002\"}', '2026-02-05 00:20:35'),
(53, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_application_code\":\"NTII-CPII-2026-002\"}', '2026-02-05 00:32:46'),
(54, 'draft_loaded', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":8}', '2026-02-05 00:33:10'),
(55, 'draft_loaded', 'draft', '2', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":2}', '2026-02-05 00:33:17'),
(56, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:35:11'),
(57, 'draft_loaded', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":8}', '2026-02-05 00:35:23'),
(58, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_application_code\":\"NT-AOII-2026-001\"}', '2026-02-05 00:35:33'),
(59, 'draft_loaded', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":8}', '2026-02-05 00:35:35'),
(60, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_application_code\":\"NT-AOII-2026-001\"}', '2026-02-05 00:36:00'),
(61, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:36:48'),
(62, 'draft_loaded', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":8}', '2026-02-05 00:37:33'),
(63, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:37:51'),
(64, 'draft_updated', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"updated_from_session\":true}', '2026-02-05 00:39:25'),
(65, 'draft_saved', 'draft', '9', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1230}', '2026-02-05 00:41:06'),
(66, 'draft_loaded', 'draft', '9', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":9}', '2026-02-05 00:41:10'),
(67, 'draft_loaded', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":8}', '2026-02-05 00:41:14'),
(68, 'draft_saved', 'draft', '10', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1223}', '2026-02-05 00:41:38'),
(69, 'draft_loaded', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":8}', '2026-02-05 00:41:43'),
(70, 'draft_loaded', 'draft', '9', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":9}', '2026-02-05 00:41:46'),
(71, 'draft_loaded', 'draft', '10', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":10}', '2026-02-05 00:41:48'),
(72, 'draft_saved', 'draft', '11', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1235}', '2026-02-05 00:42:23'),
(73, 'draft_loaded', 'draft', '10', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":10}', '2026-02-05 00:42:26'),
(74, 'draft_loaded', 'draft', '11', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":11}', '2026-02-05 00:42:29'),
(75, 'draft_saved', 'draft', '12', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1239}', '2026-02-05 00:43:20'),
(76, 'draft_saved', 'draft', '13', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1230}', '2026-02-05 00:44:02'),
(77, 'draft_saved', 'draft', '14', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1236}', '2026-02-05 00:44:38'),
(78, 'draft_saved', 'draft', '15', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1231}', '2026-02-05 00:45:25'),
(79, 'draft_saved', 'draft', '16', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1233}', '2026-02-05 00:46:03'),
(80, 'draft_saved', 'draft', '17', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1232}', '2026-02-05 00:47:04'),
(81, 'draft_saved', 'draft', '18', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1230}', '2026-02-05 00:47:35'),
(82, 'draft_saved', 'draft', '19', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1237}', '2026-02-05 00:48:29'),
(83, 'draft_saved', 'draft', '20', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1232}', '2026-02-05 00:49:34'),
(84, 'draft_saved', 'draft', '21', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1229}', '2026-02-05 00:50:08'),
(85, 'draft_saved', 'draft', '22', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1234}', '2026-02-05 00:50:47'),
(86, 'draft_saved', 'draft', '23', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1235}', '2026-02-05 00:51:32'),
(87, 'draft_saved', 'draft', '24', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1236}', '2026-02-05 00:52:19'),
(88, 'draft_saved', 'draft', '25', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1235}', '2026-02-05 00:53:31'),
(89, 'draft_loaded', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":8}', '2026-02-05 02:02:48'),
(90, 'draft_saved', 'draft', '26', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1236}', '2026-02-05 04:57:44'),
(91, 'draft_saved', 'draft', '27', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1242}', '2026-02-05 07:03:24'),
(92, 'draft_loaded', 'draft', '8', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"loaded_from_draft_id\":8}', '2026-02-05 07:06:40'),
(93, 'draft_saved', 'draft', '28', NULL, '95i12kqr5qnfg7vr2hafisvn54', '::1', '{\"source\":\"save_draft\",\"payload_size\":1237}', '2026-02-05 07:19:44'),
(94, 'draft_saved', 'draft', '29', NULL, '0qealvr4b5ut0j0h6q6aqaq0gp', '::1', '{\"source\":\"save_draft\",\"payload_size\":1210}', '2026-02-05 09:42:46'),
(95, 'draft_saved', 'draft', '30', NULL, '0qealvr4b5ut0j0h6q6aqaq0gp', '::1', '{\"source\":\"save_draft\",\"payload_size\":1206}', '2026-02-05 09:42:54'),
(96, 'draft_saved', 'draft', '31', NULL, '0qealvr4b5ut0j0h6q6aqaq0gp', '::1', '{\"source\":\"save_draft\",\"payload_size\":1228}', '2026-02-05 09:43:39'),
(97, 'draft_saved', 'draft', '32', NULL, '0qealvr4b5ut0j0h6q6aqaq0gp', '::1', '{\"source\":\"save_draft\",\"payload_size\":1242}', '2026-02-05 09:44:50'),
(98, 'draft_saved', 'draft', '33', NULL, '0qealvr4b5ut0j0h6q6aqaq0gp', '::1', '{\"source\":\"save_draft\",\"payload_size\":1220}', '2026-02-05 09:45:11'),
(99, 'draft_saved', 'draft', '34', NULL, '0qealvr4b5ut0j0h6q6aqaq0gp', '::1', '{\"source\":\"save_draft\",\"payload_size\":1238}', '2026-02-05 09:51:17'),
(100, 'draft_saved', 'draft', '35', NULL, '0qealvr4b5ut0j0h6q6aqaq0gp', '::1', '{\"source\":\"save_draft\",\"payload_size\":1242}', '2026-02-05 09:52:09');

-- --------------------------------------------------------

--
-- Table structure for table `baseline_qualifications`
--

CREATE TABLE `baseline_qualifications` (
  `id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `education_degree` varchar(100) DEFAULT NULL,
  `education_masters_units` int(11) DEFAULT 0,
  `education_doctoral_units` int(11) DEFAULT 0,
  `training_hours` decimal(10,2) DEFAULT 0.00,
  `experience_months` decimal(10,2) DEFAULT 0.00,
  `performance_rating` decimal(5,2) DEFAULT 0.00,
  `outstanding_accomplishments` int(11) DEFAULT 0,
  `application_of_education_level` int(11) DEFAULT 0,
  `application_of_ld_level` int(11) DEFAULT 0,
  `potential_level` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comparative_assessment_results`
--

CREATE TABLE `comparative_assessment_results` (
  `id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `application_code` varchar(100) DEFAULT NULL,
  `education_score` decimal(10,2) DEFAULT 0.00,
  `training_score` decimal(10,2) DEFAULT 0.00,
  `experience_score` decimal(10,2) DEFAULT 0.00,
  `performance_score` decimal(10,2) DEFAULT 0.00,
  `outstanding_accomplishments_score` decimal(10,2) DEFAULT 0.00,
  `application_of_education_score` decimal(10,2) DEFAULT 0.00,
  `application_of_ld_score` decimal(10,2) DEFAULT 0.00,
  `potential_score` decimal(10,2) DEFAULT 0.00,
  `total_score` decimal(10,2) DEFAULT 0.00,
  `rank` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `background_yes` tinyint(1) DEFAULT 0,
  `background_no` tinyint(1) DEFAULT 0,
  `for_appointment` tinyint(1) DEFAULT 0,
  `for_probation` tinyint(1) DEFAULT 0,
  `assessment_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drafts`
--

CREATE TABLE `drafts` (
  `id` int(11) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `application_code` varchar(128) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drafts`
--

INSERT INTO `drafts` (`id`, `session_id`, `application_code`, `data`, `created_at`, `updated_at`) VALUES
(8, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-003', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Stefany A. Lodronio\",\"application_code\":\"NT-AOII-2026-003\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:00:37', '2026-02-05 00:39:25'),
(9, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-004_20260205014106', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Enjelou E. Barcena\",\"application_code\":\"NT-AOII-2026-004\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09493169328\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:41:06', '2026-02-05 00:41:06'),
(10, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-001_20260205014138', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Victor Briz\",\"application_code\":\"NT-AOII-2026-001\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09669188486\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:41:38', '2026-02-05 00:41:38'),
(11, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-002_20260205014223', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Sofia Rica G. Lapidario\",\"application_code\":\"NT-AOII-2026-002\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09265458674\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:42:23', '2026-02-05 00:42:23'),
(12, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-005_20260205014320', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Hannabella Katriel B. Ebron\",\"application_code\":\"NT-AOII-2026-005\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09939016750\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:43:20', '2026-02-05 00:43:20'),
(13, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-006_20260205014402', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Jasmine B. Caparas\",\"application_code\":\"NT-AOII-2026-006\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09217423206\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:44:02', '2026-02-05 00:44:02'),
(14, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-007_20260205014438', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Kristel Joyce R. Montiel\",\"application_code\":\"NT-AOII-2026-007\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09301260487\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:44:38', '2026-02-05 00:44:38'),
(15, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-008_20260205014525', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Alexandra M. Gabuat\",\"application_code\":\"NT-AOII-2026-008\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09957190670\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:45:25', '2026-02-05 00:45:25'),
(16, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-009_20260205014603', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Ma. Flourissa C. Rima\",\"application_code\":\"NT-AOII-2026-009\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09989511565\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:46:03', '2026-02-05 00:46:03'),
(17, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-010_20260205014704', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Jihan Grace O. Calay\",\"application_code\":\"NT-AOII-2026-010\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09938543935\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:47:04', '2026-02-05 00:47:04'),
(18, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-011_20260205014735', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"John Paul G. Reyes\",\"application_code\":\"NT-AOII-2026-011\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09994378051\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:47:35', '2026-02-05 00:47:35'),
(19, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-012_20260205014829', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Lorraine Caryl B. Cestona\",\"application_code\":\"NT-AOII-2026-012\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09935962102\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:48:29', '2026-02-05 00:48:29'),
(20, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-013_20260205014934', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Mark Louie S. Piscal\",\"application_code\":\"NT-AOII-2026-013\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09519590450\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:49:34', '2026-02-05 00:49:34'),
(21, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-014_20260205015008', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Nympha A. Caceres\",\"application_code\":\"NT-AOII-2026-014\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09946270270\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:50:08', '2026-02-05 00:50:08'),
(22, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-015_20260205015047', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Ellaica Jane D. Petate\",\"application_code\":\"NT-AOII-2026-015\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09368045692\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:50:47', '2026-02-05 00:50:47'),
(23, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-016_20260205015132', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Jhockey Lyn M. Bariring\",\"application_code\":\"NT-AOII-2026-016\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09565516114\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:51:32', '2026-02-05 00:51:32'),
(24, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-017_20260205015219', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Michelle Ann A. Bornasal\",\"application_code\":\"NT-AOII-2026-017\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:52:19', '2026-02-05 00:52:19'),
(25, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-018_20260205015331', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Sarah Jamaica M. Armada\",\"application_code\":\"NT-AOII-2026-018\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09195999979\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 00:53:31', '2026-02-05 00:53:31'),
(26, '95i12kqr5qnfg7vr2hafisvn54', 'RT-CEPS-2026-001_20260205055744', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"3\",\"position_key\":\"chief_eps\",\"position_applied\":\"Chief Education Program Specialist\",\"job_group_sg_level\":\"Group RELATED TEACHING POSITION \\/ Salary Grade 24\",\"applicant_name\":\"Geto Suguru\",\"application_code\":\"RT-CEPS-2026-001\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"60\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 04:57:44', '2026-02-05 04:57:44'),
(27, '95i12kqr5qnfg7vr2hafisvn54', 'RT-CEPS-2026-001_20260205080323', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"2\",\"position_key\":\"ass_principal_i\",\"position_applied\":\"Assistant School Principal I\",\"job_group_sg_level\":\"Group SCHOOL ADMINISTRATION POSITION \\/ Salary Grade 18\",\"applicant_name\":\"Geto Suguru2\",\"application_code\":\"RT-CEPS-2026-001\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"24\",\"baseline_experience\":\"36\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 07:03:23', '2026-02-05 07:03:23'),
(28, '95i12kqr5qnfg7vr2hafisvn54', 'NT-AOII-2026-0000_20260205081944', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Nobara Kugisaki\",\"application_code\":\"NT-AOII-2026-0000\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 07:19:44', '2026-02-05 07:19:44'),
(29, '0qealvr4b5ut0j0h6q6aqaq0gp', 'NT-AOII-2026-0000_20260205104246', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"0\",\"position_key\":\"teacher_i\",\"position_applied\":\"Teacher I\",\"job_group_sg_level\":\"Group TEACHING POSITIONS \\/ Salary Grade 11\",\"applicant_name\":\"Nobara Kugisaki2\",\"application_code\":\"NT-AOII-2026-0000\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 09:42:46', '2026-02-05 09:42:46'),
(30, '0qealvr4b5ut0j0h6q6aqaq0gp', 'NT-AOII-2026-0001_20260205104254', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"\",\"position_key\":\"custom\",\"position_applied\":\"Teacher I\",\"job_group_sg_level\":\"Group TEACHING POSITIONS \\/ Salary Grade 11\",\"applicant_name\":\"Nobara Kugisaki2\",\"application_code\":\"NT-AOII-2026-0001\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 09:42:54', '2026-02-05 09:42:54'),
(31, '0qealvr4b5ut0j0h6q6aqaq0gp', 'NT-AOII-2026-0002_20260205104337', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"head_teacher_i\",\"position_applied\":\"Head Teacher I\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 14\",\"applicant_name\":\"Nobara Kugisaki3\",\"application_code\":\"NT-AOII-2026-0002\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"8\",\"baseline_experience\":\"12\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 09:43:37', '2026-02-05 09:43:37'),
(32, '0qealvr4b5ut0j0h6q6aqaq0gp', 'NT-AOII-2026-0003_20260205104450', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"3\",\"position_key\":\"chief_eps\",\"position_applied\":\"Chief Education Program Specialist\",\"job_group_sg_level\":\"Group RELATED TEACHING POSITION \\/ Salary Grade 24\",\"applicant_name\":\"Nobara Kugisaki4\",\"application_code\":\"NT-AOII-2026-0003\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"60\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 09:44:50', '2026-02-05 09:44:50'),
(33, '0qealvr4b5ut0j0h6q6aqaq0gp', 'NT-AOII-2026-0004_20260205104511', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"4\",\"position_key\":\"accountant_iv\",\"position_applied\":\"Accountant IV\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL I \\/ Salary Grade 22\",\"applicant_name\":\"Nobara Kugisaki5\",\"application_code\":\"NT-AOII-2026-0004\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"60\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 09:45:11', '2026-02-05 09:45:11'),
(34, '0qealvr4b5ut0j0h6q6aqaq0gp', 'NT-AOII-2026-0005_20260205105117', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Nobara Kugisaki6\",\"application_code\":\"NT-AOII-2026-0005\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"2\",\"applicant_application_of_ld\":\"2\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 09:51:17', '2026-02-05 09:51:17'),
(35, '0qealvr4b5ut0j0h6q6aqaq0gp', 'NT-AOII-2026-0006_20260205105209', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Nobara Kugisaki7\",\"application_code\":\"NT-AOII-2026-0006\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"11\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"15\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"29\",\"applicant_experience\":\"171\",\"applicant_performance\":\"5\",\"applicant_outstanding_accomplishments\":\"5\",\"applicant_application_of_education\":\"5\",\"applicant_application_of_ld\":\"5\",\"applicant_potential\":\"5\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 09:52:09', '2026-02-05 09:52:09');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `position_id` int(11) DEFAULT NULL,
  `position_group` enum('TEACHING','NON-TEACHING LEVEL I','NON-TEACHING LEVEL II','RELATED TEACHING','HIGHER TEACHING','SCHOOL ADMINISTRATION') NOT NULL DEFAULT 'NON-TEACHING LEVEL I',
  `total_score` decimal(10,2) DEFAULT 0.00,
  `evaluation_date` date NOT NULL,
  `evaluator_name` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_details`
--

CREATE TABLE `evaluation_details` (
  `id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `criterion` varchar(100) NOT NULL,
  `applicant_qualification` text DEFAULT NULL,
  `applicant_level` int(11) DEFAULT 0,
  `baseline_qualification` text DEFAULT NULL,
  `baseline_level` int(11) DEFAULT 0,
  `increment` int(11) DEFAULT 0,
  `weight` int(11) DEFAULT 0,
  `points` decimal(10,2) DEFAULT 0.00,
  `final_score` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_audit`
--

CREATE TABLE `login_audit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `login_status` enum('success','failed','blocked') DEFAULT 'failed',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_audit`
--

INSERT INTO `login_audit` (`id`, `user_id`, `username`, `email`, `login_status`, `ip_address`, `user_agent`, `reason`, `attempted_at`) VALUES
(1, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 05:39:52'),
(2, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 05:41:35'),
(3, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 05:41:43'),
(4, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 05:41:57'),
(5, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 05:41:57'),
(6, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 05:56:00'),
(7, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 05:58:18'),
(8, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 05:58:18'),
(9, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 05:58:18'),
(10, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 05:58:18'),
(11, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 06:11:12'),
(12, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 06:13:56'),
(13, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 06:14:51'),
(14, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 06:14:51'),
(15, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 06:16:49'),
(16, 1, 'admin', 'admin', 'success', '', '', 'Login successful', '2026-02-04 06:19:28'),
(17, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.108.2 Chrome/142.0.7444.235 Electron/39.2.7 Safari/537.36', 'Login successful', '2026-02-04 06:22:18'),
(18, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.108.2 Chrome/142.0.7444.235 Electron/39.2.7 Safari/537.36', 'Login successful', '2026-02-04 06:22:32'),
(19, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.108.2 Chrome/142.0.7444.235 Electron/39.2.7 Safari/537.36', 'Login successful', '2026-02-04 06:22:32'),
(20, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 06:26:45'),
(21, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 06:27:06'),
(22, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 06:27:12'),
(23, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.108.2 Chrome/142.0.7444.235 Electron/39.2.7 Safari/537.36', 'Login successful', '2026-02-04 06:29:10'),
(24, 1, 'admin', 'admin', 'success', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.108.2 Chrome/142.0.7444.235 Electron/39.2.7 Safari/537.36', 'Login successful', '2026-02-04 06:30:15'),
(25, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 06:30:32'),
(26, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 06:32:17'),
(27, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 06:32:23'),
(28, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 06:32:26'),
(29, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 06:34:42'),
(30, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 06:34:45'),
(31, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-02-04 06:34:49'),
(32, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 06:34:52'),
(33, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 07:32:29'),
(34, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 07:35:00'),
(35, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 07:57:04'),
(36, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 07:57:08'),
(37, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 08:04:12'),
(38, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 08:04:46'),
(39, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 08:04:57'),
(40, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 08:04:59'),
(41, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 08:05:09'),
(42, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Linux; Android 12; Pixel 6 Build/SQ3A.220705.004; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/134.0.0.0 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/407.0.0.0.65;]', 'Login successful', '2026-02-04 08:05:39'),
(43, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 08:11:31'),
(44, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 08:12:57'),
(45, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 08:14:35'),
(46, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 08:15:48'),
(47, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 08:31:37'),
(48, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 08:31:40'),
(49, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-04 09:53:42'),
(50, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 09:53:46'),
(51, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 10:18:38'),
(52, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-04 23:53:37'),
(53, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Login successful', '2026-02-05 04:40:00'),
(54, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-02-05 08:21:03'),
(55, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-05 08:22:16');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `position_name` varchar(255) NOT NULL,
  `position_group` enum('TEACHING','NON-TEACHING LEVEL I','NON-TEACHING LEVEL II','RELATED TEACHING','HIGHER TEACHING','SCHOOL ADMINISTRATION') NOT NULL,
  `salary_grade` varchar(50) DEFAULT NULL,
  `item_number` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `position_name`, `position_group`, `salary_grade`, `item_number`, `description`, `created_at`, `updated_at`) VALUES
(816, 'Teacher I', 'TEACHING', '11', NULL, 'Teaching Position - Basic Teacher Level', '2026-02-05 06:41:18', '2026-02-05 06:41:18'),
(817, 'School Principal IV', 'SCHOOL ADMINISTRATION', '22', NULL, 'School Administration - Principal Level IV', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(818, 'School Principal III', 'SCHOOL ADMINISTRATION', '21', NULL, 'School Administration - Principal Level III', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(819, 'Assistant School Principal III', 'SCHOOL ADMINISTRATION', '20', NULL, 'School Administration - Assistant Principal Level III', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(820, 'School Principal II', 'SCHOOL ADMINISTRATION', '20', NULL, 'School Administration - Principal Level II', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(821, 'Special School Principal II', 'SCHOOL ADMINISTRATION', '20', NULL, 'School Administration - Special Principal Level II', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(822, 'Assistant School Principal II', 'SCHOOL ADMINISTRATION', '19', NULL, 'School Administration - Assistant Principal Level II', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(823, 'Head Teacher VI', 'SCHOOL ADMINISTRATION', '19', NULL, 'School Administration - Head Teacher Level VI', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(824, 'School Principal I', 'SCHOOL ADMINISTRATION', '19', NULL, 'School Administration - Principal Level I', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(825, 'Special School Principal I', 'SCHOOL ADMINISTRATION', '19', NULL, 'School Administration - Special Principal Level I', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(826, 'Principal', 'SCHOOL ADMINISTRATION', '19', NULL, 'School Administration - Principal', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(827, 'Assistant School Principal I', 'SCHOOL ADMINISTRATION', '18', NULL, 'School Administration - Assistant Principal Level I', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(828, 'Assistant Special School Principal', 'SCHOOL ADMINISTRATION', '18', NULL, 'School Administration - Assistant Special Principal', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(829, 'Head Teacher V', 'SCHOOL ADMINISTRATION', '18', NULL, 'School Administration - Head Teacher Level V', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(830, 'Head Teacher IV', 'SCHOOL ADMINISTRATION', '17', NULL, 'School Administration - Head Teacher Level IV', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(831, 'Head Teacher III', 'SCHOOL ADMINISTRATION', '16', NULL, 'School Administration - Head Teacher Level III', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(832, 'Head Teacher II', 'SCHOOL ADMINISTRATION', '15', NULL, 'School Administration - Head Teacher Level II', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(833, 'Head Teacher I', 'SCHOOL ADMINISTRATION', '14', NULL, 'School Administration - Head Teacher Level I', '2026-02-05 06:41:23', '2026-02-05 06:41:23'),
(834, 'Chief Education Program Specialist', 'RELATED TEACHING', '24', NULL, 'Related Teaching - Chief Education Program Specialist', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(835, 'Education Program Supervisor', 'RELATED TEACHING', '22', NULL, 'Related Teaching - Education Program Supervisor', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(836, 'Public Schools District Supervisor', 'RELATED TEACHING', '22', NULL, 'Related Teaching - Public Schools District Supervisor', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(837, 'Supervising Education Program Specialist', 'RELATED TEACHING', '22', NULL, 'Related Teaching - Supervising Education Program Specialist', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(838, 'Senior Education Program Specialist', 'RELATED TEACHING', '19', NULL, 'Related Teaching - Senior Education Program Specialist', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(839, 'Senior Science Research Specialist', 'RELATED TEACHING', '19', NULL, 'Related Teaching - Senior Science Research Specialist', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(840, 'Vocational Instruction Supervisor III', 'RELATED TEACHING', '18', NULL, 'Related Teaching - Vocational Instruction Supervisor III', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(841, 'Vocational Instruction Supervisor II', 'RELATED TEACHING', '17', NULL, 'Related Teaching - Vocational Instruction Supervisor II', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(842, 'Education Program Specialist II', 'RELATED TEACHING', '16', NULL, 'Related Teaching - Education Program Specialist II', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(843, 'Guidance Coordinator III', 'RELATED TEACHING', '16', NULL, 'Related Teaching - Guidance Coordinator III', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(844, 'Science Research Specialist II', 'RELATED TEACHING', '16', NULL, 'Related Teaching - Science Research Specialist II', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(845, 'Science Research Technician IV', 'RELATED TEACHING', '16', NULL, 'Related Teaching - Science Research Technician IV', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(846, 'Vocational Instruction Supervisor I', 'RELATED TEACHING', '16', NULL, 'Related Teaching - Vocational Instruction Supervisor I', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(847, 'Guidance Coordinator II', 'RELATED TEACHING', '15', NULL, 'Related Teaching - Guidance Coordinator II', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(848, 'School Farming Coordinator III', 'RELATED TEACHING', '15', NULL, 'Related Teaching - School Farming Coordinator III', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(849, 'Teacher Credentials Evaluator II', 'RELATED TEACHING', '15', NULL, 'Related Teaching - Teacher Credentials Evaluator II', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(850, 'Guidance Coordinator I', 'RELATED TEACHING', '15', NULL, 'Related Teaching - Guidance Coordinator I', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(851, 'School Farming Coordinator II', 'RELATED TEACHING', '14', NULL, 'Related Teaching - School Farming Coordinator II', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(852, 'Guidance Counselor III', 'RELATED TEACHING', '13', NULL, 'Related Teaching - Guidance Counselor III', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(853, 'School Farming Coordinator I', 'RELATED TEACHING', '13', NULL, 'Related Teaching - School Farming Coordinator I', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(854, 'Science Research Technician III', 'RELATED TEACHING', '13', NULL, 'Related Teaching - Science Research Technician III', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(855, 'Teacher Credentials Evaluator I', 'RELATED TEACHING', '13', NULL, 'Related Teaching - Teacher Credentials Evaluator I', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(856, 'Crafts Education Demonstrator II', 'RELATED TEACHING', '12', NULL, 'Related Teaching - Crafts Education Demonstrator II', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(857, 'Education Program Specialist I', 'RELATED TEACHING', '12', NULL, 'Related Teaching - Education Program Specialist I', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(858, 'Guidance Counselor II', 'RELATED TEACHING', '12', NULL, 'Related Teaching - Guidance Counselor II', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(859, 'Guidance Counselor I', 'RELATED TEACHING', '11', NULL, 'Related Teaching - Guidance Counselor I', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(860, 'Science Research Technician II', 'RELATED TEACHING', '11', NULL, 'Related Teaching - Science Research Technician II', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(861, 'Teaching-Aids Specialist', 'RELATED TEACHING', '11', NULL, 'Related Teaching - Teaching-Aids Specialist', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(862, 'Crafts Education Demonstrator I', 'RELATED TEACHING', '10', NULL, 'Related Teaching - Crafts Education Demonstrator I', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(863, 'Education Research Assistant II', 'RELATED TEACHING', '10', NULL, 'Related Teaching - Education Research Assistant II', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(864, 'School Farm Demonstrator', 'RELATED TEACHING', '10', NULL, 'Related Teaching - School Farm Demonstrator', '2026-02-05 06:41:28', '2026-02-05 06:41:28'),
(865, 'Master Teacher IV', 'HIGHER TEACHING', '18', NULL, 'Higher Teaching - Master Teacher Level IV', '2026-02-05 06:41:42', '2026-02-05 06:41:42'),
(866, 'Master Teacher III', 'HIGHER TEACHING', '17', NULL, 'Higher Teaching - Master Teacher Level III', '2026-02-05 06:41:42', '2026-02-05 06:41:42'),
(867, 'Master Teacher II', 'HIGHER TEACHING', '16', NULL, 'Higher Teaching - Master Teacher Level II', '2026-02-05 06:41:42', '2026-02-05 06:41:42'),
(868, 'Master Teacher I', 'HIGHER TEACHING', '15', NULL, 'Higher Teaching - Master Teacher Level I', '2026-02-05 06:41:42', '2026-02-05 06:41:42'),
(869, 'Attorney V', 'NON-TEACHING LEVEL I', '25', NULL, 'Non-Teaching - Attorney Level V', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(870, 'Chief Accountant', 'NON-TEACHING LEVEL I', '24', NULL, 'Non-Teaching - Chief Accountant', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(871, 'Chief Administrative Officer', 'NON-TEACHING LEVEL I', '24', NULL, 'Non-Teaching - Chief Administrative Officer', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(872, 'Chief Education Supervisor', 'NON-TEACHING LEVEL I', '24', NULL, 'Non-Teaching - Chief Education Supervisor', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(873, 'Chief Health Program Officer', 'NON-TEACHING LEVEL I', '24', NULL, 'Non-Teaching - Chief Health Program Officer', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(874, 'Engineer V', 'NON-TEACHING LEVEL I', '24', NULL, 'Non-Teaching - Engineer Level V', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(875, 'Information Technology Officer III', 'NON-TEACHING LEVEL I', '24', NULL, 'Non-Teaching - Information Technology Officer III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(876, 'Internal Auditor V', 'NON-TEACHING LEVEL I', '24', NULL, 'Non-Teaching - Internal Auditor Level V', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(877, 'Planning Officer V', 'NON-TEACHING LEVEL I', '24', NULL, 'Non-Teaching - Planning Officer Level V', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(878, 'Project Development Officer V', 'NON-TEACHING LEVEL I', '24', NULL, 'Non-Teaching - Project Development Officer Level V', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(879, 'Teacher Camp Superintendent', 'NON-TEACHING LEVEL I', '24', NULL, 'Non-Teaching - Teacher Camp Superintendent', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(880, 'Attorney IV', 'NON-TEACHING LEVEL I', '23', NULL, 'Non-Teaching - Attorney Level IV', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(881, 'Medical Officer IV', 'NON-TEACHING LEVEL I', '23', NULL, 'Non-Teaching - Medical Officer Level IV', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(882, 'Vocational School Administrator II', 'NON-TEACHING LEVEL I', '23', NULL, 'Non-Teaching - Vocational School Administrator II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(883, 'Accountant IV', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Accountant Level IV', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(884, 'Assistant Teacher Camp Superintendent', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Assistant Teacher Camp Superintendent', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(885, 'Department Legislative Liaison Specialist', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Department Legislative Liaison Specialist', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(886, 'Engineer IV', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Engineer Level IV', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(887, 'Information Technology Officer II', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Information Technology Officer II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(888, 'Internal Auditor IV', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Internal Auditor Level IV', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(889, 'Project Development Officer IV', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Project Development Officer Level IV', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(890, 'Project Evaluation Officer IV', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Project Evaluation Officer Level IV', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(891, 'Security Officer IV', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Security Officer Level IV', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(892, 'Supervising Administrative Officer', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Supervising Administrative Officer', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(893, 'Supervising Health Program Officer', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Supervising Health Program Officer', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(894, 'Vocational School Administrator I', 'NON-TEACHING LEVEL I', '22', NULL, 'Non-Teaching - Vocational School Administrator I', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(895, 'Attorney III', 'NON-TEACHING LEVEL I', '21', NULL, 'Non-Teaching - Attorney Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(896, 'Medical Officer III', 'NON-TEACHING LEVEL I', '21', NULL, 'Non-Teaching - Medical Officer Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(897, 'Dentist III', 'NON-TEACHING LEVEL I', '20', NULL, 'Non-Teaching - Dentist Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(898, 'Accountant III', 'NON-TEACHING LEVEL I', '19', NULL, 'Non-Teaching - Accountant Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(899, 'Architect III', 'NON-TEACHING LEVEL I', '19', NULL, 'Non-Teaching - Architect Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(900, 'Engineer III', 'NON-TEACHING LEVEL I', '19', NULL, 'Non-Teaching - Engineer Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(901, 'Information Systems Analyst III', 'NON-TEACHING LEVEL I', '19', NULL, 'Non-Teaching - Information Systems Analyst III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(902, 'Information Technology Officer I', 'NON-TEACHING LEVEL I', '19', NULL, 'Non-Teaching - Information Technology Officer I', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(903, 'Administrative Officer V', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Administrative Officer Level V', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(904, 'Attorney II', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Attorney Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(905, 'Computer Programmer III', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Computer Programmer III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(906, 'Guidance Services Specialist II', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Guidance Services Specialist II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(907, 'Health Education And Promotion Officer III', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Health Education And Promotion Officer III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(908, 'Internal Auditor III', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Internal Auditor Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(909, 'Librarian III', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Librarian Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(910, 'Medical Officer II', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Medical Officer Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(911, 'Nutritionist-Dietitian III', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Nutritionist-Dietitian III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(912, 'Planning Officer III', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Planning Officer Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(913, 'Project Development Officer III', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Project Development Officer Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(914, 'Senior Administrative Assistant V', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Senior Administrative Assistant Level V', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(915, 'Special Investigator III', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Special Investigator Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(916, 'Statistician III', 'NON-TEACHING LEVEL I', '18', NULL, 'Non-Teaching - Statistician Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(917, 'Computer Maintenance Technologist III', 'NON-TEACHING LEVEL I', '17', NULL, 'Non-Teaching - Computer Maintenance Technologist III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(918, 'Dentist II', 'NON-TEACHING LEVEL I', '17', NULL, 'Non-Teaching - Dentist Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(919, 'Information Systems Researcher III', 'NON-TEACHING LEVEL I', '17', NULL, 'Non-Teaching - Information Systems Researcher III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(920, 'Accountant II', 'NON-TEACHING LEVEL I', '16', NULL, 'Non-Teaching - Accountant Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(921, 'Architect II', 'NON-TEACHING LEVEL I', '16', NULL, 'Non-Teaching - Architect Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(922, 'Attorney I', 'NON-TEACHING LEVEL I', '16', NULL, 'Non-Teaching - Attorney Level I', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(923, 'Engineer II', 'NON-TEACHING LEVEL I', '16', NULL, 'Non-Teaching - Engineer Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(924, 'Guidance Services Specialist I', 'NON-TEACHING LEVEL I', '16', NULL, 'Non-Teaching - Guidance Services Specialist I', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(925, 'Information Systems Analyst II', 'NON-TEACHING LEVEL I', '16', NULL, 'Non-Teaching - Information Systems Analyst II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(926, 'Administrative Officer IV', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Administrative Officer Level IV', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(927, 'Agriculturist II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Agriculturist Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(928, 'College Librarian II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - College Librarian Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(929, 'Computer Programming II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Computer Programming II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(930, 'Creative Arts Specialist II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Creative Arts Specialist II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(931, 'Human Resource Management Officer II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Human Resource Management Officer II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(932, 'Internal Auditor II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Internal Auditor Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(933, 'Librarian II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Librarian Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(934, 'Nurse II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Nurse Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(935, 'Nutritionist-Dietitian II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Nutritionist-Dietitian II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(936, 'Planning Officer II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Planning Officer Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(937, 'Project Development Officer II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Project Development Officer Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(938, 'Publication Production Supervisor', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Publication Production Supervisor', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(939, 'Registrar II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Registrar Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(940, 'Security Officer II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Security Officer Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(941, 'Senior Administrative Assistant III', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Senior Administrative Assistant Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(942, 'Special Investigator II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Special Investigator Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(943, 'Statistician II', 'NON-TEACHING LEVEL I', '15', NULL, 'Non-Teaching - Statistician Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(944, 'Administrative Officer III', 'NON-TEACHING LEVEL I', '14', NULL, 'Non-Teaching - Administrative Officer Level III', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(945, 'Cashier II', 'NON-TEACHING LEVEL I', '14', NULL, 'Non-Teaching - Cashier Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(946, 'Dentist I', 'NON-TEACHING LEVEL I', '14', NULL, 'Non-Teaching - Dentist Level I', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(947, 'Health Education and Promotion Officer II', 'NON-TEACHING LEVEL I', '14', NULL, 'Non-Teaching - Health Education and Promotion Officer II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(948, 'Records Officer II', 'NON-TEACHING LEVEL I', '14', NULL, 'Non-Teaching - Records Officer Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(949, 'Senior Administrative Assistant II', 'NON-TEACHING LEVEL I', '14', NULL, 'Non-Teaching - Senior Administrative Assistant Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(950, 'Supply Officer II', 'NON-TEACHING LEVEL I', '14', NULL, 'Non-Teaching - Supply Officer Level II', '2026-02-05 06:41:52', '2026-02-05 06:41:52'),
(951, 'College Librarian I', 'NON-TEACHING LEVEL II', '13', NULL, 'Non-Teaching - College Librarian Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(952, 'School Librarian III', 'NON-TEACHING LEVEL II', '13', NULL, 'Non-Teaching - School Librarian Level III', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(953, 'Senior Administrative Assistant I', 'NON-TEACHING LEVEL II', '13', NULL, 'Non-Teaching - Senior Administrative Assistant Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(954, 'Vocational Placement Coordinator I', 'NON-TEACHING LEVEL II', '13', NULL, 'Non-Teaching - Vocational Placement Coordinator I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(955, 'Accountant I', 'NON-TEACHING LEVEL II', '12', NULL, 'Non-Teaching - Accountant Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(956, 'Administrative Assistant VI', 'NON-TEACHING LEVEL II', '12', NULL, 'Non-Teaching - Administrative Assistant Level VI', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(957, 'Legal Assistant II', 'NON-TEACHING LEVEL II', '12', NULL, 'Non-Teaching - Legal Assistant Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(958, 'School Librarian II', 'NON-TEACHING LEVEL II', '12', NULL, 'Non-Teaching - School Librarian Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(959, 'Accounting Clerk I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Accounting Clerk Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(960, 'Administrative Assistant V', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Administrative Assistant Level V', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(961, 'Administrative Officer I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Administrative Officer Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(962, 'Administrative Officer II', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Administrative Officer Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(963, 'Agriculturist I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Agriculturist Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(964, 'Aquaculturist I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Aquaculturist Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(965, 'Budget Officer I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Budget Officer Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(966, 'Communication Equipment Operator IV', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Communication Equipment Operator Level IV', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(967, 'Computer Maintenance Technologist I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Computer Maintenance Technologist Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(968, 'Creative Arts Specialist I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Creative Arts Specialist Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(969, 'Dormitory Manager II', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Dormitory Manager Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(970, 'Fiscal Examiner I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Fiscal Examiner Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(971, 'Human Resource Management Officer I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Human Resource Management Officer I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(972, 'Internal Auditor I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Internal Auditor Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(973, 'Librarian I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Librarian Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(974, 'Nurse I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Nurse Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(975, 'Nutritionist-Dietitian I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Nutritionist-Dietitian I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(976, 'Planning Officer I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Planning Officer Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(977, 'Project Development Officer I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Project Development Officer Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(978, 'Psychologist I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Psychologist Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(979, 'Registrar I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Registrar Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(980, 'Social Welfare Officer I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Social Welfare Officer Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(981, 'Statistician I', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Statistician Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(982, 'Warehouseman III', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Warehouseman Level III', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(983, 'Cashier I', 'NON-TEACHING LEVEL II', '10', NULL, 'Non-Teaching - Cashier Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(984, 'Cinematographer I', 'NON-TEACHING LEVEL II', '10', NULL, 'Non-Teaching - Cinematographer Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(985, 'Computer File Librarian II', 'NON-TEACHING LEVEL II', '10', NULL, 'Non-Teaching - Computer File Librarian Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(986, 'Legal Assistant I', 'NON-TEACHING LEVEL II', '10', NULL, 'Non-Teaching - Legal Assistant Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(987, 'Supply Officer I', 'NON-TEACHING LEVEL II', '10', NULL, 'Non-Teaching - Supply Officer Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(988, 'Administrative Assistant III', 'NON-TEACHING LEVEL II', '9', NULL, 'Non-Teaching - Administrative Assistant Level III', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(989, 'Communication Equipment Operator III', 'NON-TEACHING LEVEL II', '9', NULL, 'Non-Teaching - Communication Equipment Operator Level III', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(990, 'Dormitory Manager I', 'NON-TEACHING LEVEL II', '9', NULL, 'Non-Teaching - Dormitory Manager Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(991, 'Printing Foreman', 'NON-TEACHING LEVEL II', '9', NULL, 'Non-Teaching - Printing Foreman', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(992, 'Science Research Assistant', 'NON-TEACHING LEVEL II', '9', NULL, 'Non-Teaching - Science Research Assistant', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(993, 'Science Research Technician I', 'NON-TEACHING LEVEL II', '9', NULL, 'Non-Teaching - Science Research Technician Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(994, 'Senior Bookkeeper', 'NON-TEACHING LEVEL II', '9', NULL, 'Non-Teaching - Senior Bookkeeper', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(995, 'Administrative Assistant II', 'NON-TEACHING LEVEL II', '8', NULL, 'Non-Teaching - Administrative Assistant Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(996, 'Aquacultural Technician II', 'NON-TEACHING LEVEL II', '8', NULL, 'Non-Teaching - Aquacultural Technician Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(997, 'Artist-Illustrator II', 'NON-TEACHING LEVEL II', '8', NULL, 'Non-Teaching - Artist-Illustrator Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(998, 'Bookkeeper', 'NON-TEACHING LEVEL II', '8', NULL, 'Non-Teaching - Bookkeeper', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(999, 'Computer File Librarian I', 'NON-TEACHING LEVEL II', '8', NULL, 'Non-Teaching - Computer File Librarian Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1000, 'Disbursing Officer II', 'NON-TEACHING LEVEL II', '8', NULL, 'Non-Teaching - Disbursing Officer Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1001, 'Draftsman II', 'NON-TEACHING LEVEL II', '8', NULL, 'Non-Teaching - Draftsman Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1002, 'Internal Auditing Assistant', 'NON-TEACHING LEVEL II', '8', NULL, 'Non-Teaching - Internal Auditing Assistant', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1003, 'Project Development Assistant', 'NON-TEACHING LEVEL II', '8', NULL, 'Non-Teaching - Project Development Assistant', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1004, 'Security Guard III', 'NON-TEACHING LEVEL II', '8', NULL, 'Non-Teaching - Security Guard Level III', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1005, 'Administrative Assistant I', 'NON-TEACHING LEVEL II', '7', NULL, 'Non-Teaching - Administrative Assistant Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1006, 'Copy Reader', 'NON-TEACHING LEVEL II', '7', NULL, 'Non-Teaching - Copy Reader', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1007, 'Accounting Clerk II', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Accounting Clerk Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1008, 'Administrative Aide VI', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Administrative Aide Level VI', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1009, 'Clerk III', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Clerk Level III', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1010, 'Communication Equipment Operator II', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Communication Equipment Operator Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1011, 'Disbursing Officer I', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Disbursing Officer Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1012, 'Draftsman I', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Draftsman Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1013, 'Electronics And Communication Equipment Technician I', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Electronics And Communication Equipment Technician I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1014, 'Laboratory Technician I', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Laboratory Technician Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1015, 'Mechanic II', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Mechanic Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1016, 'Mechanical Plant Operator II', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Mechanical Plant Operator Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1017, 'Photoengraver II', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Photoengraver Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1018, 'Proofreader II', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Proofreader Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1019, 'Typesetter II', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Typesetter Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1020, 'Utility Foreman', 'NON-TEACHING LEVEL II', '6', NULL, 'Non-Teaching - Utility Foreman', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1021, 'Administrative Aide V', 'NON-TEACHING LEVEL II', '5', NULL, 'Non-Teaching - Administrative Aide Level V', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1022, 'Handicraft Worker II', 'NON-TEACHING LEVEL II', '5', NULL, 'Non-Teaching - Handicraft Worker Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1023, 'Legal Aide', 'NON-TEACHING LEVEL II', '5', NULL, 'Non-Teaching - Legal Aide', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1024, 'Master Fisherman I', 'NON-TEACHING LEVEL II', '5', NULL, 'Non-Teaching - Master Fisherman Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1025, 'Security Guard II', 'NON-TEACHING LEVEL II', '5', NULL, 'Non-Teaching - Security Guard Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1026, 'Administrative Aide IV', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Administrative Aide Level IV', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1027, 'Clerk II', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Clerk Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1028, 'Communication Equipment Operator I', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Communication Equipment Operator Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1029, 'Dental Aide', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Dental Aide', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1030, 'Fiscal Clerk I', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Fiscal Clerk Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1031, 'Heavy Equipment Operator I', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Heavy Equipment Operator Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1032, 'Houseparent I', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Houseparent Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1033, 'Marine Engineman I', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Marine Engineman Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1034, 'Mechanic I', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Mechanic Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1035, 'Mechanical Plant Operator I', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Mechanical Plant Operator Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1036, 'Metal Worker I', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Metal Worker Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1037, 'Nursing Attendant I', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Nursing Attendant Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1038, 'Statistician Aide', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Statistician Aide', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1039, 'Telegram Carrier', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Telegram Carrier', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1040, 'Watchman II', 'NON-TEACHING LEVEL II', '4', NULL, 'Non-Teaching - Watchman Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1041, 'Administrative Aide III', 'NON-TEACHING LEVEL II', '3', NULL, 'Non-Teaching - Administrative Aide Level III', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1042, 'Clerk I', 'NON-TEACHING LEVEL II', '3', NULL, 'Non-Teaching - Clerk Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1043, 'Cook I', 'NON-TEACHING LEVEL II', '3', NULL, 'Non-Teaching - Cook Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1044, 'Coxswain', 'NON-TEACHING LEVEL II', '3', NULL, 'Non-Teaching - Coxswain', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1045, 'Driver I', 'NON-TEACHING LEVEL II', '3', NULL, 'Non-Teaching - Driver Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1046, 'Fisherman', 'NON-TEACHING LEVEL II', '3', NULL, 'Non-Teaching - Fisherman', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1047, 'Handicraft Worker I', 'NON-TEACHING LEVEL II', '3', NULL, 'Non-Teaching - Handicraft Worker Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1048, 'Lineman I', 'NON-TEACHING LEVEL II', '3', NULL, 'Non-Teaching - Lineman Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1049, 'Security Guard I', 'NON-TEACHING LEVEL II', '3', NULL, 'Non-Teaching - Security Guard Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1050, 'Administrative Aide II', 'NON-TEACHING LEVEL II', '2', NULL, 'Non-Teaching - Administrative Aide Level II', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1051, 'Construction And Maintenance Man', 'NON-TEACHING LEVEL II', '2', NULL, 'Non-Teaching - Construction And Maintenance Man', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1052, 'Farm Worker I', 'NON-TEACHING LEVEL II', '2', NULL, 'Non-Teaching - Farm Worker Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1053, 'Guesthouse Caretaker', 'NON-TEACHING LEVEL II', '2', NULL, 'Non-Teaching - Guesthouse Caretaker', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1054, 'Light Equipment Operator', 'NON-TEACHING LEVEL II', '2', NULL, 'Non-Teaching - Light Equipment Operator', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1055, 'Nurse Maid I', 'NON-TEACHING LEVEL II', '2', NULL, 'Non-Teaching - Nurse Maid Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1056, 'Reproduction Machine Operator I', 'NON-TEACHING LEVEL II', '2', NULL, 'Non-Teaching - Reproduction Machine Operator I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1057, 'Watchman I', 'NON-TEACHING LEVEL II', '2', NULL, 'Non-Teaching - Watchman Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1058, 'Administrative Aide I', 'NON-TEACHING LEVEL II', '1', NULL, 'Non-Teaching - Administrative Aide Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1059, 'Utility Worker I', 'NON-TEACHING LEVEL II', '1', NULL, 'Non-Teaching - Utility Worker Level I', '2026-02-05 06:42:23', '2026-02-05 06:42:23'),
(1060, 'Administrative Officer II', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Administrative Officer Level II', '2026-02-05 07:08:08', '2026-02-05 07:08:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff','evaluator') DEFAULT 'staff',
  `status` enum('active','disabled','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@deped.gov.ph', '$2y$10$CzFBTeSBGdgXWRzXV5thbOjFVp9ygJBQS7THdXFaNDxhwZiD7P2.G', 'System Administrator', 'admin', 'active', '2026-02-05 08:22:16', '2026-02-04 05:37:18', '2026-02-05 08:22:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `position_applied_id` (`position_applied_id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_position_group` (`position_group`),
  ADD KEY `idx_archive_status` (`archive_status`);

--
-- Indexes for table `applicant_qualifications`
--
ALTER TABLE `applicant_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_applicant_id` (`applicant_id`);

--
-- Indexes for table `archived_applicants_audit`
--
ALTER TABLE `archived_applicants_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_applicant_id` (`applicant_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_archived_at` (`archived_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_object_type` (`object_type`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `baseline_qualifications`
--
ALTER TABLE `baseline_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_position_id` (`position_id`);

--
-- Indexes for table `comparative_assessment_results`
--
ALTER TABLE `comparative_assessment_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_position_applicant` (`position_id`,`applicant_id`),
  ADD KEY `idx_position_id` (`position_id`),
  ADD KEY `idx_applicant_id` (`applicant_id`),
  ADD KEY `idx_total_score` (`total_score`),
  ADD KEY `idx_rank` (`rank`);

--
-- Indexes for table `drafts`
--
ALTER TABLE `drafts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_application_code` (`application_code`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_applicant_id` (`applicant_id`),
  ADD KEY `idx_position_id` (`position_id`),
  ADD KEY `idx_evaluation_date` (`evaluation_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `evaluation_details`
--
ALTER TABLE `evaluation_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_evaluation_id` (`evaluation_id`),
  ADD KEY `idx_criterion` (`criterion`);

--
-- Indexes for table `login_audit`
--
ALTER TABLE `login_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_login_status` (`login_status`),
  ADD KEY `idx_attempted_at` (`attempted_at`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_position_group` (`position_group`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `applicant_qualifications`
--
ALTER TABLE `applicant_qualifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `archived_applicants_audit`
--
ALTER TABLE `archived_applicants_audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `baseline_qualifications`
--
ALTER TABLE `baseline_qualifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comparative_assessment_results`
--
ALTER TABLE `comparative_assessment_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `drafts`
--
ALTER TABLE `drafts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `evaluation_details`
--
ALTER TABLE `evaluation_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `login_audit`
--
ALTER TABLE `login_audit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1061;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applicants`
--
ALTER TABLE `applicants`
  ADD CONSTRAINT `applicants_ibfk_1` FOREIGN KEY (`position_applied_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `applicant_qualifications`
--
ALTER TABLE `applicant_qualifications`
  ADD CONSTRAINT `applicant_qualifications_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `archived_applicants_audit`
--
ALTER TABLE `archived_applicants_audit`
  ADD CONSTRAINT `archived_applicants_audit_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `baseline_qualifications`
--
ALTER TABLE `baseline_qualifications`
  ADD CONSTRAINT `baseline_qualifications_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comparative_assessment_results`
--
ALTER TABLE `comparative_assessment_results`
  ADD CONSTRAINT `comparative_assessment_results_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comparative_assessment_results_ibfk_2` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `evaluation_details`
--
ALTER TABLE `evaluation_details`
  ADD CONSTRAINT `evaluation_details_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_audit`
--
ALTER TABLE `login_audit`
  ADD CONSTRAINT `login_audit_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
