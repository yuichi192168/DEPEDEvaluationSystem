-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Mar 24, 2026 at 02:00 AM
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
-- Database: `deped_evaluation_v4`
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

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `name`, `position_applied_id`, `position_group`, `archive_status`, `archived_at`, `archive_reason`, `created_at`, `updated_at`) VALUES
(59, 'Aiah Arceta', 962, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-02-06 13:12:54', '2026-02-06 13:12:54'),
(60, 'Michelle Ann A. Bornasal', 1050, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-02-08 00:15:56', '2026-02-08 00:15:56'),
(61, 'Michelle Ann A. Bornasal TEST', 962, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-02-08 00:27:12', '2026-02-08 00:27:12'),
(62, 'Michelle Ann A. Bornasal TEST 2', 875, 'TEACHING', 'active', NULL, NULL, '2026-02-08 00:36:15', '2026-02-08 00:36:15'),
(63, 'Michelle Ann A. Bornasal TEST 3', 833, 'TEACHING', 'active', NULL, NULL, '2026-02-08 00:36:49', '2026-02-08 00:36:49'),
(64, 'Michelle Ann A. Bornasal TEST 4', 962, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-02-08 00:44:37', '2026-02-08 00:44:37'),
(65, 'Michelle Ann A. Bornasal TEST 5', 833, 'HIGHER TEACHING', 'active', NULL, NULL, '2026-02-08 00:49:02', '2026-02-08 00:49:02'),
(66, 'Michelle Ann A. Bornasal TEST 6', 834, 'RELATED TEACHING', 'active', NULL, NULL, '2026-02-08 00:49:35', '2026-02-08 00:49:35'),
(67, 'Michelle Ann A. Bornasal TEST 7', 827, 'SCHOOL ADMINISTRATION', 'active', NULL, NULL, '2026-02-08 00:49:58', '2026-02-08 00:49:58'),
(68, 'Michelle Ann A. Bornasal TEST 8', 866, 'HIGHER TEACHING', 'active', NULL, NULL, '2026-02-08 00:50:21', '2026-02-08 00:50:21'),
(69, 'Michelle Ann A. Bornasal TEST 9', 962, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-02-08 00:51:48', '2026-02-08 00:51:48'),
(70, 'Michelle Ann A. Bornasal TEST 10', 832, 'HIGHER TEACHING', 'active', NULL, NULL, '2026-02-08 00:53:32', '2026-02-08 00:53:32'),
(71, 'Michelle Ann A. Bornasal TEST 11', 831, 'HIGHER TEACHING', 'active', NULL, NULL, '2026-02-08 00:54:07', '2026-02-08 00:54:07'),
(72, 'Michelle Ann A. Bornasal TEST 12', 830, 'HIGHER TEACHING', 'active', NULL, NULL, '2026-02-08 00:54:35', '2026-02-08 00:54:35'),
(73, 'Michelle Ann A. Bornasal TEST 13', 829, 'HIGHER TEACHING', 'active', NULL, NULL, '2026-02-08 00:55:01', '2026-02-08 00:55:01'),
(74, 'Michelle Ann A. Bornasal TEST 14', 823, 'HIGHER TEACHING', 'active', NULL, NULL, '2026-02-08 00:55:26', '2026-02-08 00:55:26'),
(75, 'Michelle Ann A. Bornasal TEST 15', 1061, 'HIGHER TEACHING', 'active', NULL, NULL, '2026-02-08 00:55:47', '2026-02-08 00:55:47'),
(76, 'Michelle Ann A. Bornasal TEST 16', 866, 'HIGHER TEACHING', 'active', NULL, NULL, '2026-02-08 00:56:08', '2026-02-08 00:56:08'),
(77, 'Michelle Ann A. Bornasal TEST 17', 833, 'HIGHER TEACHING', 'active', NULL, NULL, '2026-02-08 00:58:50', '2026-02-08 01:01:16'),
(78, 'Michelle Ann A. Bornasal TEST 18', 962, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-02-09 01:10:22', '2026-02-09 01:10:22'),
(79, 'Michelle Ann A. Bornasal TEST 19', 962, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-02-09 01:12:30', '2026-02-09 01:12:30'),
(80, 'Michelle Ann A. Bornasal TEST 20', 962, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-02-09 01:14:34', '2026-02-09 01:14:34'),
(81, 'Michelle Ann A. Bornasal TEST 22', 920, 'NON-TEACHING LEVEL I', 'active', NULL, NULL, '2026-03-05 00:06:05', '2026-03-05 00:06:05'),
(82, 'Michelle Ann A. Bornasal TEST 23', 855, 'NON-TEACHING LEVEL I', 'active', NULL, NULL, '2026-03-07 02:57:22', '2026-03-07 02:57:22'),
(83, 'Michelle Ann A. Bornasal TEST 24', 818, 'SCHOOL ADMINISTRATION', 'active', NULL, NULL, '2026-03-07 02:58:01', '2026-03-07 02:58:01'),
(84, 'Michelle Ann A. Bornasal TEST 25', 818, 'SCHOOL ADMINISTRATION', 'active', NULL, NULL, '2026-03-07 03:07:38', '2026-03-07 03:07:38'),
(85, 'Michelle Ann A. Bornasal TEST 26', 962, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-03-07 03:18:14', '2026-03-07 03:18:14'),
(86, 'Michelle Ann A. Bornasal TEST 27', 818, 'SCHOOL ADMINISTRATION', 'active', NULL, NULL, '2026-03-07 08:08:10', '2026-03-07 08:08:10'),
(87, 'Michelle Ann A. Bornasal TEST 28', 818, 'SCHOOL ADMINISTRATION', 'active', NULL, NULL, '2026-03-07 08:10:39', '2026-03-07 08:10:39'),
(88, 'Michelle Ann A. Bornasal TEST 29', 927, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-03-07 08:24:06', '2026-03-07 08:24:06'),
(89, 'Michelle Ann A. Bornasal TEST 30', 1027, 'NON-TEACHING LEVEL II', 'active', NULL, NULL, '2026-03-07 08:30:00', '2026-03-07 08:30:00'),
(90, 'Michelle Ann A. Bornasal TEST 230', 962, 'NON-TEACHING LEVEL I', 'active', NULL, NULL, '2026-03-18 03:01:37', '2026-03-18 03:01:37'),
(91, 'Michelle Ann A. Bornasal TEST 222', 820, 'SCHOOL ADMINISTRATION', 'active', NULL, NULL, '2026-03-18 03:05:01', '2026-03-18 03:05:01');

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

--
-- Dumping data for table `applicant_qualifications`
--

INSERT INTO `applicant_qualifications` (`id`, `applicant_id`, `education_degree`, `education_masters_units`, `education_doctoral_units`, `training_hours`, `experience_months`, `performance_rating`, `outstanding_accomplishments`, `application_of_education_level`, `application_of_ld_level`, `potential_level`, `created_at`, `updated_at`) VALUES
(49, 59, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-06 13:12:54', '2026-02-06 13:12:54'),
(50, 60, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:15:56', '2026-02-08 00:15:56'),
(51, 61, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:27:12', '2026-02-08 00:27:12'),
(52, 62, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:36:15', '2026-02-08 00:36:15'),
(53, 63, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:36:49', '2026-02-08 00:36:49'),
(54, 64, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:44:37', '2026-02-08 00:44:37'),
(55, 65, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:49:02', '2026-02-08 00:49:02'),
(56, 66, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:49:35', '2026-02-08 00:49:35'),
(57, 67, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:49:58', '2026-02-08 00:49:58'),
(58, 68, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:50:21', '2026-02-08 00:50:21'),
(59, 69, 'Master', 9, 0, 92.00, 93.00, 4.00, 1, 8, 0, 0, '2026-02-08 00:51:48', '2026-02-08 00:51:48'),
(60, 70, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:53:32', '2026-02-08 00:53:32'),
(61, 71, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:54:07', '2026-02-08 00:54:07'),
(62, 72, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:54:35', '2026-02-08 00:54:35'),
(63, 73, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:55:01', '2026-02-08 00:55:01'),
(64, 74, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:55:26', '2026-02-08 00:55:26'),
(65, 75, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:55:47', '2026-02-08 00:55:47'),
(66, 76, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:56:08', '2026-02-08 00:56:08'),
(67, 77, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-08 00:58:50', '2026-02-08 00:58:50'),
(68, 78, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-09 01:10:22', '2026-02-09 01:10:22'),
(69, 79, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-09 01:12:30', '2026-02-09 01:12:30'),
(70, 80, 'Master', 9, 0, 92.00, 93.00, 3.00, 1, 8, 0, 0, '2026-02-09 01:14:34', '2026-02-09 01:14:34'),
(71, 81, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 1, 8, 0, 0, '2026-03-05 00:06:05', '2026-03-05 00:06:05'),
(72, 82, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 1, 8, 0, 0, '2026-03-07 02:57:22', '2026-03-07 02:57:22'),
(73, 83, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 1, 8, 0, 0, '2026-03-07 02:58:01', '2026-03-07 02:58:01'),
(74, 84, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 1, 8, 0, 0, '2026-03-07 03:07:38', '2026-03-07 03:07:38'),
(75, 85, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 3, 8, 1, 2, '2026-03-07 03:18:14', '2026-03-07 03:18:14'),
(76, 86, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 3, 8, 1, 2, '2026-03-07 08:08:10', '2026-03-07 08:08:10'),
(77, 87, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 3, 8, 1, 2, '2026-03-07 08:10:39', '2026-03-07 08:10:39'),
(78, 88, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 3, 8, 1, 2, '2026-03-07 08:24:06', '2026-03-07 08:24:06'),
(79, 89, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 3, 8, 1, 2, '2026-03-07 08:30:00', '2026-03-07 08:30:00'),
(80, 90, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 3, 8, 1, 2, '2026-03-18 03:01:37', '2026-03-18 03:01:37'),
(81, 91, 'Bachelor', 0, 0, 124.00, 99.00, 3.00, 3, 8, 1, 2, '2026-03-18 03:05:01', '2026-03-18 03:05:01');

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

--
-- Dumping data for table `archived_applicants_audit`
--

INSERT INTO `archived_applicants_audit` (`id`, `applicant_id`, `applicant_name`, `action`, `reason`, `archived_by`, `archived_at`, `notes`) VALUES
(29, 77, 'Michelle Ann A. Bornasal TEST 17', 'archived', 'backup', 'Admin', '2026-02-08 01:01:06', NULL),
(30, 77, 'Michelle Ann A. Bornasal TEST 17', 'restored', NULL, 'Admin', '2026-02-08 01:01:16', NULL);

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
(100, 'draft_saved', 'draft', '35', NULL, '0qealvr4b5ut0j0h6q6aqaq0gp', '::1', '{\"source\":\"save_draft\",\"payload_size\":1242}', '2026-02-05 09:52:09'),
(101, 'draft_saved', 'draft', '36', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"source\":\"save_draft\",\"payload_size\":1221}', '2026-02-06 07:15:41'),
(102, 'draft_saved', 'draft', '37', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"source\":\"save_draft\",\"payload_size\":1236}', '2026-02-06 07:18:08'),
(103, 'draft_saved', 'draft', '38', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"source\":\"save_draft\",\"payload_size\":1236}', '2026-02-06 07:18:25'),
(104, 'draft_saved', 'draft', '39', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"source\":\"save_draft\",\"payload_size\":1379}', '2026-02-06 07:35:10'),
(105, 'draft_saved', 'draft', '40', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"source\":\"save_draft\",\"payload_size\":1367}', '2026-02-06 07:35:17'),
(106, 'draft_saved', 'draft', '41', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"source\":\"save_draft\",\"payload_size\":1386}', '2026-02-06 07:38:21'),
(107, 'draft_saved', 'draft', '42', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"source\":\"save_draft\",\"payload_size\":1386}', '2026-02-06 07:38:42'),
(108, 'draft_loaded', 'draft', '42', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":42}', '2026-02-06 13:02:55'),
(109, 'draft_loaded', 'draft', '38', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":38}', '2026-02-06 13:03:00'),
(110, 'draft_loaded', 'draft', '26', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":26}', '2026-02-06 13:03:06'),
(111, 'draft_loaded', 'draft', '34', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":34}', '2026-02-06 13:03:13'),
(112, 'draft_loaded', 'draft', '21', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":21}', '2026-02-06 13:03:21'),
(113, 'draft_saved', 'draft', '43', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"source\":\"save_draft\",\"payload_size\":1382}', '2026-02-06 13:12:54'),
(114, 'draft_loaded', 'draft', '12', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":12}', '2026-02-06 13:39:51'),
(115, 'draft_loaded', 'draft', '11', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":11}', '2026-02-06 13:40:06'),
(116, 'draft_loaded', 'draft', '13', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":13}', '2026-02-06 13:40:32'),
(117, 'draft_loaded', 'draft', '14', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":14}', '2026-02-06 13:40:51'),
(118, 'draft_loaded', 'draft', '15', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":15}', '2026-02-06 13:40:58'),
(119, 'draft_loaded', 'draft', '16', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":16}', '2026-02-06 13:41:13'),
(120, 'draft_loaded', 'draft', '17', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":17}', '2026-02-06 13:41:22'),
(121, 'draft_loaded', 'draft', '18', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":18}', '2026-02-06 13:41:28'),
(122, 'draft_loaded', 'draft', '19', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":19}', '2026-02-06 13:41:34'),
(123, 'draft_loaded', 'draft', '20', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":20}', '2026-02-06 13:41:44'),
(124, 'draft_loaded', 'draft', '21', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":21}', '2026-02-06 13:41:56'),
(125, 'draft_loaded', 'draft', '22', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":22}', '2026-02-06 13:42:02'),
(126, 'draft_loaded', 'draft', '23', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":23}', '2026-02-06 13:42:12'),
(127, 'draft_loaded', 'draft', '24', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":24}', '2026-02-06 13:42:21'),
(128, 'draft_loaded', 'draft', '25', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":25}', '2026-02-06 13:42:34'),
(129, 'draft_loaded', 'draft', '42', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":42}', '2026-02-06 13:43:27'),
(130, 'draft_loaded', 'draft', '40', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":40}', '2026-02-06 13:43:36'),
(131, 'draft_loaded', 'draft', '39', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":39}', '2026-02-06 13:43:40'),
(132, 'draft_loaded', 'draft', '38', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":38}', '2026-02-06 13:43:43'),
(133, 'draft_loaded', 'draft', '37', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":37}', '2026-02-06 13:43:54'),
(134, 'draft_loaded', 'draft', '36', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":36}', '2026-02-06 13:44:00'),
(135, 'draft_loaded', 'draft', '35', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":35}', '2026-02-06 13:44:05'),
(136, 'draft_loaded', 'draft', '34', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":34}', '2026-02-06 13:44:11'),
(137, 'draft_loaded', 'draft', '33', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":33}', '2026-02-06 13:44:17'),
(138, 'draft_loaded', 'draft', '32', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":32}', '2026-02-06 13:44:22'),
(139, 'draft_loaded', 'draft', '31', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":31}', '2026-02-06 13:44:28'),
(140, 'draft_loaded', 'draft', '30', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":30}', '2026-02-06 13:44:34'),
(141, 'draft_loaded', 'draft', '29', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":29}', '2026-02-06 13:44:39'),
(142, 'draft_loaded', 'draft', '28', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":28}', '2026-02-06 13:44:45'),
(143, 'draft_loaded', 'draft', '27', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":27}', '2026-02-06 13:44:51'),
(144, 'draft_loaded', 'draft', '26', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":26}', '2026-02-06 13:44:57'),
(145, 'draft_loaded', 'draft', '25', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":25}', '2026-02-06 13:45:04'),
(146, 'draft_loaded', 'draft', '24', NULL, '7p67g4m8nejcqa478tn4i8rgqn', '::1', '{\"loaded_from_draft_id\":24}', '2026-02-06 13:45:17'),
(147, 'draft_saved', 'draft', '44', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1243}', '2026-02-08 00:15:56'),
(148, 'draft_saved', 'draft', '45', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1260}', '2026-02-08 00:27:12'),
(149, 'draft_saved', 'draft', '46', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1271}', '2026-02-08 00:36:15'),
(150, 'draft_saved', 'draft', '47', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1256}', '2026-02-08 00:36:49'),
(151, 'draft_saved', 'draft', '48', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1264}', '2026-02-08 00:44:37'),
(152, 'draft_saved', 'draft', '49', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1256}', '2026-02-08 00:49:01'),
(153, 'draft_saved', 'draft', '50', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1270}', '2026-02-08 00:49:35'),
(154, 'draft_saved', 'draft', '51', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1275}', '2026-02-08 00:49:58'),
(155, 'draft_saved', 'draft', '52', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1266}', '2026-02-08 00:50:21'),
(156, 'draft_saved', 'draft', '53', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1264}', '2026-02-08 00:51:48'),
(157, 'draft_saved', 'draft', '54', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1261}', '2026-02-08 00:53:32'),
(158, 'draft_saved', 'draft', '55', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1264}', '2026-02-08 00:54:07'),
(159, 'draft_saved', 'draft', '56', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1262}', '2026-02-08 00:54:35'),
(160, 'draft_saved', 'draft', '57', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1260}', '2026-02-08 00:55:01'),
(161, 'draft_saved', 'draft', '58', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1259}', '2026-02-08 00:55:26'),
(162, 'draft_saved', 'draft', '59', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1250}', '2026-02-08 00:55:47'),
(163, 'draft_saved', 'draft', '60', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1268}', '2026-02-08 00:56:08'),
(164, 'draft_saved', 'draft', '61', NULL, 'lq1q8are4td0f3hn4ihkpjutmr', '::1', '{\"source\":\"save_draft\",\"payload_size\":1258}', '2026-02-08 00:58:50'),
(165, 'draft_loaded', 'draft', '61', NULL, 'bq13tvc95vak3fc88ibepp62jb', '::1', '{\"loaded_from_draft_id\":61}', '2026-02-09 01:09:39'),
(166, 'draft_saved', 'draft', '62', NULL, 'bq13tvc95vak3fc88ibepp62jb', '::1', '{\"source\":\"save_draft\",\"payload_size\":1266}', '2026-02-09 01:10:22'),
(167, 'draft_saved', 'draft', '63', NULL, 'bq13tvc95vak3fc88ibepp62jb', '::1', '{\"source\":\"save_draft\",\"payload_size\":1266}', '2026-02-09 01:12:30'),
(168, 'draft_loaded', 'draft', '63', NULL, 'bq13tvc95vak3fc88ibepp62jb', '::1', '{\"loaded_from_draft_id\":63}', '2026-02-09 01:14:25'),
(169, 'draft_saved', 'draft', '64', NULL, 'bq13tvc95vak3fc88ibepp62jb', '::1', '{\"source\":\"save_draft\",\"payload_size\":1266}', '2026-02-09 01:14:34'),
(170, 'draft_loaded', 'draft', '64', NULL, 'lro77rla73cp7n8gv8tqeo80t1', '::1', '{\"loaded_from_draft_id\":64}', '2026-02-18 00:20:38'),
(171, 'draft_saved', 'draft', '65', NULL, '8j5jjl1pu2p223fqg71kd44ghg', '::1', '{\"source\":\"save_draft\",\"payload_size\":1255}', '2026-03-04 08:07:54'),
(172, 'draft_saved', 'draft', '66', NULL, 'emq4hge7censl1qufma76rngp8', '::1', '{\"source\":\"save_draft\",\"payload_size\":1256}', '2026-03-05 00:05:56'),
(173, 'draft_saved', 'draft', '67', NULL, 'emq4hge7censl1qufma76rngp8', '::1', '{\"source\":\"save_draft\",\"payload_size\":1248}', '2026-03-05 00:06:05'),
(174, 'draft_saved', 'draft', '68', NULL, 'jjoiafog5l16hgi3c5m6b2ajql', '::1', '{\"source\":\"save_draft\",\"payload_size\":1294}', '2026-03-07 02:57:15'),
(175, 'draft_saved', 'draft', '69', NULL, 'jjoiafog5l16hgi3c5m6b2ajql', '::1', '{\"source\":\"save_draft\",\"payload_size\":1268}', '2026-03-07 02:57:22'),
(176, 'draft_loaded', 'draft', '69', NULL, 'jjoiafog5l16hgi3c5m6b2ajql', '::1', '{\"loaded_from_draft_id\":69}', '2026-03-07 02:57:33'),
(177, 'draft_saved', 'draft', '70', NULL, 'jjoiafog5l16hgi3c5m6b2ajql', '::1', '{\"source\":\"save_draft\",\"payload_size\":1269}', '2026-03-07 02:58:01'),
(178, 'draft_saved', 'draft', '71', NULL, 'jjoiafog5l16hgi3c5m6b2ajql', '::1', '{\"source\":\"save_draft\",\"payload_size\":1269}', '2026-03-07 03:07:38'),
(179, 'draft_saved', 'draft', '72', NULL, 'jjoiafog5l16hgi3c5m6b2ajql', '::1', '{\"source\":\"save_draft\",\"payload_size\":1268}', '2026-03-07 03:18:14'),
(180, 'draft_loaded', 'draft', '72', NULL, 'jjoiafog5l16hgi3c5m6b2ajql', '::1', '{\"loaded_from_draft_id\":72}', '2026-03-07 03:19:10'),
(181, 'draft_saved', 'draft', '73', NULL, 'jjoiafog5l16hgi3c5m6b2ajql', '::1', '{\"source\":\"save_draft\",\"payload_size\":1268}', '2026-03-07 03:19:50'),
(182, 'draft_saved', 'draft', '74', NULL, 'jjoiafog5l16hgi3c5m6b2ajql', '::1', '{\"source\":\"save_draft\",\"payload_size\":1261}', '2026-03-07 07:44:03'),
(183, 'draft_saved', 'draft', '75', NULL, '9ujvvge8o93cf929t9ebdu02ft', '::1', '{\"source\":\"save_draft\",\"payload_size\":1269}', '2026-03-07 08:08:10'),
(184, 'draft_saved', 'draft', '76', NULL, '9ujvvge8o93cf929t9ebdu02ft', '::1', '{\"source\":\"save_draft\",\"payload_size\":1269}', '2026-03-07 08:10:39'),
(185, 'draft_saved', 'draft', '77', NULL, '9ujvvge8o93cf929t9ebdu02ft', '::1', '{\"source\":\"save_draft\",\"payload_size\":1261}', '2026-03-07 08:24:06'),
(186, 'draft_saved', 'draft', '78', NULL, '9ujvvge8o93cf929t9ebdu02ft', '::1', '{\"source\":\"save_draft\",\"payload_size\":1242}', '2026-03-07 08:30:00'),
(187, 'draft_saved', 'draft', '79', NULL, 'vtbrv4b1p3rlurv0stp0t5vetq', '::1', '{\"source\":\"save_draft\",\"payload_size\":1259}', '2026-03-18 03:01:37'),
(188, 'draft_saved', 'draft', '80', NULL, 'vtbrv4b1p3rlurv0stp0t5vetq', '::1', '{\"source\":\"save_draft\",\"payload_size\":1281}', '2026-03-18 03:02:17'),
(189, 'draft_saved', 'draft', '81', NULL, 'vtbrv4b1p3rlurv0stp0t5vetq', '::1', '{\"source\":\"save_draft\",\"payload_size\":1226}', '2026-03-18 03:05:01'),
(190, 'draft_saved', 'draft', '82', NULL, 'r9ljmesb3jmshg24p5qlg5m1s6', '::1', '{\"source\":\"save_draft\",\"payload_size\":1193}', '2026-03-23 23:40:45'),
(191, 'draft_loaded', 'draft', '82', NULL, 'r9ljmesb3jmshg24p5qlg5m1s6', '::1', '{\"loaded_from_draft_id\":82}', '2026-03-23 23:40:51');

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

--
-- Dumping data for table `comparative_assessment_results`
--

INSERT INTO `comparative_assessment_results` (`id`, `position_id`, `applicant_id`, `application_code`, `education_score`, `training_score`, `experience_score`, `performance_score`, `outstanding_accomplishments_score`, `application_of_education_score`, `application_of_ld_score`, `potential_score`, `total_score`, `rank`, `remarks`, `background_yes`, `background_no`, `for_appointment`, `for_probation`, `assessment_date`, `created_at`, `updated_at`) VALUES
(44, 962, 59, 'NT-AOII-2026-027', 5.00, 10.00, 15.00, 11.98, 1.00, 8.50, 0.00, 0.00, 51.48, 9, NULL, 0, 0, 0, 0, '2026-02-06', '2026-02-06 13:12:54', '2026-03-18 03:06:59'),
(45, 1050, 60, 'NT-AOII-2026-017', 3.00, 5.00, 20.00, 11.98, 1.00, 10.00, 0.00, 0.00, 50.98, 1, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:15:56', '2026-02-08 00:27:53'),
(46, 962, 61, 'NT-AOII-2026-017 TEST', 5.00, 10.00, 15.00, 11.98, 1.00, 8.50, 0.00, 0.00, 51.48, 4, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:27:12', '2026-03-18 03:06:59'),
(47, 875, 62, 'NT-AOII-2026-017 TEST 2', 0.00, 3.00, 8.00, 11.98, 1.00, 8.50, 0.00, 0.00, 32.48, NULL, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:36:15', '2026-02-08 00:36:15'),
(48, 833, 63, 'NT-AOII-2026-017 TEST 3', 10.00, 10.00, 10.00, 5.99, 1.00, 8.50, 0.00, 0.00, 45.49, NULL, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:36:49', '2026-02-08 00:36:49'),
(49, 962, 64, 'NT-AOII-2026-017 TEST 4', 5.00, 10.00, 15.00, 11.98, 1.00, 8.50, 0.00, 0.00, 51.48, 8, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:44:37', '2026-03-18 03:06:59'),
(50, 833, 65, 'NT-AOII-2026-017 TEST 5', 10.00, 10.00, 10.00, 5.99, 1.00, 8.50, 0.00, 0.00, 45.49, NULL, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:49:02', '2026-02-08 00:49:02'),
(51, 834, 66, 'NT-AOII-2026-017 TEST 6', 0.00, 6.00, 4.00, 14.98, 1.00, 8.50, 0.00, 0.00, 34.48, 1, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:49:35', '2026-03-07 03:08:32'),
(52, 827, 67, 'NT-AOII-2026-017 TEST 7', 0.00, 8.00, 8.00, 14.98, 1.00, 8.50, 0.00, 0.00, 40.48, NULL, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:49:58', '2026-02-08 00:49:58'),
(53, 866, 68, 'NT-AOII-2026-017 TEST 8', 8.00, 8.00, 8.00, 5.99, 1.00, 8.50, 0.00, 0.00, 39.49, 2, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:50:21', '2026-03-05 00:06:32'),
(54, 962, 69, 'NT-AOII-2026-017 TEST 9', 5.00, 10.00, 15.00, 15.98, 1.00, 8.50, 0.00, 0.00, 55.48, 3, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:51:48', '2026-03-18 03:06:59'),
(55, 832, 70, 'NT-AOII-2026-017 TEST 10', 10.00, 8.00, 10.00, 5.99, 1.00, 8.50, 0.00, 0.00, 43.49, NULL, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:53:32', '2026-02-08 00:53:32'),
(56, 831, 71, 'NT-AOII-2026-017 TEST 11', 8.00, 8.00, 10.00, 5.99, 1.00, 8.50, 0.00, 0.00, 41.49, NULL, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:54:07', '2026-02-08 00:54:07'),
(57, 830, 72, 'NT-AOII-2026-017 TEST 12', 8.00, 8.00, 8.00, 5.99, 1.00, 8.50, 0.00, 0.00, 39.49, NULL, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:54:35', '2026-02-08 00:54:35'),
(58, 829, 73, 'NT-AOII-2026-017 TEST 13', 8.00, 8.00, 6.00, 5.99, 1.00, 8.50, 0.00, 0.00, 37.49, 1, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:55:01', '2026-03-07 03:16:24'),
(59, 823, 74, 'NT-AOII-2026-017 TEST 14', 0.00, 6.00, 4.00, 5.99, 1.00, 8.50, 0.00, 0.00, 25.49, NULL, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:55:26', '2026-02-08 00:55:26'),
(60, 1061, 75, 'NT-AOII-2026-017 TEST 15', 10.00, 10.00, 10.00, 5.99, 1.00, 8.50, 0.00, 0.00, 45.49, NULL, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:55:47', '2026-02-08 00:55:47'),
(61, 866, 76, 'NT-AOII-2026-017 TEST 16', 8.00, 8.00, 8.00, 5.99, 1.00, 8.50, 0.00, 0.00, 39.49, 1, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:56:08', '2026-03-05 00:06:32'),
(62, 833, 77, 'NT-AOII-2026-017 TEST 17', 10.00, 10.00, 10.00, 5.99, 1.00, 8.50, 0.00, 0.00, 45.49, NULL, NULL, 0, 0, 0, 0, '2026-02-08', '2026-02-08 00:58:50', '2026-02-08 00:58:50'),
(63, 962, 78, 'NT-AOII-2026-017 TEST 18', 5.00, 10.00, 15.00, 11.98, 1.00, 8.50, 0.00, 0.00, 51.48, 5, NULL, 0, 0, 0, 0, '2026-02-09', '2026-02-09 01:10:22', '2026-03-18 03:06:59'),
(64, 962, 79, 'NT-AOII-2026-017 TEST 19', 5.00, 10.00, 15.00, 11.98, 1.00, 8.50, 0.00, 0.00, 51.48, 6, NULL, 0, 0, 0, 0, '2026-02-09', '2026-02-09 01:12:30', '2026-03-18 03:06:59'),
(65, 962, 80, 'NT-AOII-2026-017 TEST 20', 5.00, 10.00, 15.00, 11.98, 1.00, 8.50, 0.00, 0.00, 51.48, 7, NULL, 0, 0, 0, 0, '2026-02-09', '2026-02-09 01:14:34', '2026-03-18 03:06:59'),
(66, 920, 81, 'NT-AOII-2026-017 TEST 22', 0.00, 5.00, 20.00, 11.98, 1.00, 8.50, 0.00, 0.00, 46.48, NULL, NULL, 0, 0, 0, 0, '2026-03-05', '2026-03-05 00:06:05', '2026-03-05 00:06:05'),
(67, 855, 82, 'NT-AOII-2026-017 TEST 23', 0.00, 5.00, 20.00, 11.98, 1.00, 8.50, 0.00, 0.00, 46.48, NULL, NULL, 0, 0, 0, 0, '2026-03-07', '2026-03-07 02:57:22', '2026-03-07 02:57:22'),
(68, 818, 83, 'NT-AOII-2026-017 TEST 24', 0.00, 10.00, 8.00, 14.98, 1.00, 8.50, 0.00, 0.00, 42.48, 3, NULL, 0, 0, 0, 0, '2026-03-07', '2026-03-07 02:58:01', '2026-03-18 03:02:24'),
(69, 818, 84, 'NT-AOII-2026-017 TEST 25', 0.00, 10.00, 8.00, 14.98, 1.00, 8.50, 0.00, 0.00, 42.48, 4, NULL, 0, 0, 0, 0, '2026-03-07', '2026-03-07 03:07:38', '2026-03-18 03:02:24'),
(70, 962, 85, 'NT-AOII-2026-017 TEST 26', 0.00, 10.00, 15.00, 11.98, 3.00, 8.50, 2.00, 8.00, 58.48, 1, NULL, 0, 0, 0, 0, '2026-03-07', '2026-03-07 03:18:14', '2026-03-07 03:18:33'),
(71, 818, 86, 'NT-AOII-2026-017 TEST 27', 0.00, 10.00, 8.00, 14.98, 3.00, 8.50, 2.00, 6.00, 52.48, 1, NULL, 0, 0, 0, 0, '2026-03-07', '2026-03-07 08:08:10', '2026-03-18 03:02:24'),
(72, 818, 87, 'NT-AOII-2026-017 TEST 28', 0.00, 10.00, 8.00, 14.98, 3.00, 8.50, 2.00, 6.00, 52.48, 2, NULL, 0, 0, 0, 0, '2026-03-07', '2026-03-07 08:10:39', '2026-03-18 03:02:24'),
(73, 927, 88, 'NT-AOII-2026-017 TEST 29', 0.00, 10.00, 15.00, 11.98, 3.00, 8.50, 2.00, 8.00, 58.48, NULL, NULL, 0, 0, 0, 0, '2026-03-07', '2026-03-07 08:24:06', '2026-03-07 08:24:06'),
(74, 1027, 89, 'NT-AOII-2026-017 TEST 30', 0.00, 10.00, 15.00, 11.98, 3.00, 8.50, 2.00, 8.00, 58.48, NULL, NULL, 0, 0, 0, 0, '2026-03-07', '2026-03-07 08:30:00', '2026-03-07 08:30:00'),
(75, 962, 90, 'NT-AOII-2026-017 TEST 230', 0.00, 5.00, 20.00, 11.98, 3.00, 8.50, 2.00, 8.00, 58.48, 2, NULL, 0, 0, 0, 0, '2026-03-18', '2026-03-18 03:01:37', '2026-03-18 03:06:59'),
(76, 820, 91, 'NT-AOII-2026-017 TEST 222', 0.00, 10.00, 6.00, 14.98, 3.00, 8.50, 2.00, 6.00, 50.48, NULL, NULL, 0, 0, 0, 0, '2026-03-18', '2026-03-18 03:05:01', '2026-03-18 03:05:01');

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
(35, '0qealvr4b5ut0j0h6q6aqaq0gp', 'NT-AOII-2026-0006_20260205105209', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Nobara Kugisaki7\",\"application_code\":\"NT-AOII-2026-0006\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09936452735\",\"applicant_education_dropdown\":\"11\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"15\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"29\",\"applicant_experience\":\"171\",\"applicant_performance\":\"5\",\"applicant_outstanding_accomplishments\":\"5\",\"applicant_application_of_education\":\"5\",\"applicant_application_of_ld\":\"5\",\"applicant_potential\":\"5\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-05 09:52:09', '2026-02-05 09:52:09'),
(36, '7p67g4m8nejcqa478tn4i8rgqn', 'NT-AOII-2026-001_20260206081541', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"accountant_ii\",\"position_applied\":\"Accountant II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 16\",\"applicant_name\":\"TEST APPLICANT 001\",\"application_code\":\"NT-AOII-2026-001\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09282346159\",\"applicant_education_dropdown\":\"8\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"6\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"4\",\"applicant_training\":\"28\",\"applicant_experience_dropdown\":\"2\",\"applicant_experience\":\"9\",\"applicant_performance\":\"3\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"5\",\"applicant_application_of_ld\":\"3\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-06 07:15:41', '2026-02-06 07:15:41'),
(37, '7p67g4m8nejcqa478tn4i8rgqn', 'NT-AOII-2026-001_20260206081808', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"TEST APPLICANT 002\",\"application_code\":\"NT-AOII-2026-001\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09282346159\",\"applicant_education_dropdown\":\"8\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"6\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"4\",\"applicant_training\":\"28\",\"applicant_experience_dropdown\":\"2\",\"applicant_experience\":\"9\",\"applicant_performance\":\"3\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"5\",\"applicant_application_of_ld\":\"3\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-06 07:18:08', '2026-02-06 07:18:08'),
(38, '7p67g4m8nejcqa478tn4i8rgqn', 'NT-AOII-2026-002_20260206081825', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"TEST APPLICANT 002\",\"application_code\":\"NT-AOII-2026-002\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09282346159\",\"applicant_education_dropdown\":\"8\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"6\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"4\",\"applicant_training\":\"28\",\"applicant_experience_dropdown\":\"2\",\"applicant_experience\":\"9\",\"applicant_performance\":\"3\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"5\",\"applicant_application_of_ld\":\"3\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-06 07:18:25', '2026-02-06 07:18:25'),
(39, '7p67g4m8nejcqa478tn4i8rgqn', 'NT-AOII-2026-002_20260206083510', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"salary_grade\":\"11\",\"category\":\"\",\"position_group_name\":\"NON-TEACHING LEVEL II\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"TEST APPLICANT 003\",\"application_code\":\"NT-AOII-2026-002\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09282346159\",\"applicant_education_dropdown\":\"8\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"6\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"4\",\"applicant_training\":\"28\",\"applicant_experience_dropdown\":\"2\",\"applicant_experience\":\"9\",\"applicant_training_hours\":\"0\",\"applicant_experience_months\":\"0\",\"applicant_performance\":\"3\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"5\",\"applicant_application_of_ld\":\"3\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-06 07:35:10', '2026-02-06 07:35:10'),
(40, '7p67g4m8nejcqa478tn4i8rgqn', 'NT-AOII-2026-003_20260206083517', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"salary_grade\":\"0\",\"category\":\"\",\"position_group_name\":\"NON-TEACHING LEVEL II\",\"position_group\":\"\",\"position_key\":\"custom\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"TEST APPLICANT 003\",\"application_code\":\"NT-AOII-2026-003\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09282346159\",\"applicant_education_dropdown\":\"8\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"6\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"4\",\"applicant_training\":\"28\",\"applicant_experience_dropdown\":\"2\",\"applicant_experience\":\"9\",\"applicant_training_hours\":\"0\",\"applicant_experience_months\":\"0\",\"applicant_performance\":\"3\",\"applicant_outstanding_accomplishments\":\"2\",\"applicant_application_of_education\":\"5\",\"applicant_application_of_ld\":\"3\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-06 07:35:17', '2026-02-06 07:35:17');
INSERT INTO `drafts` (`id`, `session_id`, `application_code`, `data`, `created_at`, `updated_at`) VALUES
(41, '7p67g4m8nejcqa478tn4i8rgqn', 'NT-AOII-2026-004_20260206083821', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"salary_grade\":\"11\",\"category\":\"\",\"position_group_name\":\"NON-TEACHING LEVEL II\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"TEST APPLICANT 004\",\"application_code\":\"NT-AOII-2026-004\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09282346159\",\"applicant_education_dropdown\":\"8\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"6\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"4\",\"applicant_training\":\"28\",\"applicant_experience_dropdown\":\"2\",\"applicant_experience\":\"9\",\"applicant_training_hours\":\"0\",\"applicant_experience_months\":\"0\",\"applicant_performance\":\"3.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-06 07:38:21', '2026-02-06 07:38:21'),
(42, '7p67g4m8nejcqa478tn4i8rgqn', 'NT-AOII-2026-004_20260206083842', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"salary_grade\":\"11\",\"category\":\"\",\"position_group_name\":\"NON-TEACHING LEVEL II\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"TEST APPLICANT 004\",\"application_code\":\"NT-AOII-2026-004\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09282346159\",\"applicant_education_dropdown\":\"8\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"6\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"4\",\"applicant_training\":\"28\",\"applicant_experience_dropdown\":\"2\",\"applicant_experience\":\"9\",\"applicant_training_hours\":\"0\",\"applicant_experience_months\":\"0\",\"applicant_performance\":\"3.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-06 07:38:42', '2026-02-06 07:38:42'),
(43, '7p67g4m8nejcqa478tn4i8rgqn', 'NT-AOII-2026-027_20260206141254', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"salary_grade\":\"11\",\"category\":\"\",\"position_group_name\":\"NON-TEACHING LEVEL II\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Aiah Arceta\",\"application_code\":\"NT-AOII-2026-027\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09946270270\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_training_hours\":\"0\",\"applicant_experience_months\":\"0\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-06 13:12:54', '2026-02-06 13:12:54'),
(44, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017_20260208011556', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_aide_ii\",\"position_applied\":\"Administrative Aide II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 2\",\"applicant_name\":\"Michelle Ann A. Bornasal\",\"application_code\":\"NT-AOII-2026-017\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:15:56', '2026-02-08 00:15:56'),
(45, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST_20260208012712', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST\",\"application_code\":\"NT-AOII-2026-017 TEST\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:27:12', '2026-02-08 00:27:12'),
(46, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 2_20260208013615', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"4\",\"position_key\":\"ict_officer_iii\",\"position_applied\":\"Information Technology Officer III\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL I \\/ Salary Grade 24\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 2\",\"application_code\":\"NT-AOII-2026-017 TEST 2\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"60\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:36:15', '2026-02-08 00:36:15'),
(47, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 3_20260208013649', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"head_teacher_i\",\"position_applied\":\"Head Teacher I\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 14\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 3\",\"application_code\":\"NT-AOII-2026-017 TEST 3\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"8\",\"baseline_experience\":\"12\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:36:49', '2026-02-08 00:36:49'),
(48, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 4_20260208014437', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 4\",\"application_code\":\"NT-AOII-2026-017 TEST 4\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:44:37', '2026-02-08 00:44:37'),
(49, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 5_20260208014901', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"head_teacher_i\",\"position_applied\":\"Head Teacher I\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 14\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 5\",\"application_code\":\"NT-AOII-2026-017 TEST 5\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"8\",\"baseline_experience\":\"12\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:49:01', '2026-02-08 00:49:01'),
(50, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 6_20260208014935', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"3\",\"position_key\":\"chief_eps\",\"position_applied\":\"Chief Education Program Specialist\",\"job_group_sg_level\":\"Group RELATED TEACHING POSITION \\/ Salary Grade 24\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 6\",\"application_code\":\"NT-AOII-2026-017 TEST 6\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"60\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:49:35', '2026-02-08 00:49:35'),
(51, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 7_20260208014958', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"2\",\"position_key\":\"ass_principal_i\",\"position_applied\":\"Assistant School Principal I\",\"job_group_sg_level\":\"Group SCHOOL ADMINISTRATION POSITION \\/ Salary Grade 18\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 7\",\"application_code\":\"NT-AOII-2026-017 TEST 7\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"24\",\"baseline_experience\":\"36\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:49:58', '2026-02-08 00:49:58'),
(52, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 8_20260208015021', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"master_teacher_iii\",\"position_applied\":\"Master Teacher III\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 16\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 8\",\"application_code\":\"NT-AOII-2026-017 TEST 8\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"18\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"24\",\"baseline_experience\":\"36\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:50:21', '2026-02-08 00:50:21'),
(53, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 9_20260208015148', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 9\",\"application_code\":\"NT-AOII-2026-017 TEST 9\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"3.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:51:48', '2026-02-08 00:51:48'),
(54, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 10_20260208015332', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"head_teacher_ii\",\"position_applied\":\"Head Teacher II\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 15\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 10\",\"application_code\":\"NT-AOII-2026-017 TEST 10\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:53:32', '2026-02-08 00:53:32'),
(55, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 11_20260208015407', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"head_teacher_iii\",\"position_applied\":\"Head Teacher III\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 16\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 11\",\"application_code\":\"NT-AOII-2026-017 TEST 11\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"18\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:54:07', '2026-02-08 00:54:07'),
(56, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 12_20260208015435', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"head_teacher_iv\",\"position_applied\":\"Head Teacher IV\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 17\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 12\",\"application_code\":\"NT-AOII-2026-017 TEST 12\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"18\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"24\",\"baseline_experience\":\"36\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:54:35', '2026-02-08 00:54:35'),
(57, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 13_20260208015501', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"head_teacher_v\",\"position_applied\":\"Head Teacher V\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 18\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 13\",\"application_code\":\"NT-AOII-2026-017 TEST 13\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"18\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"24\",\"baseline_experience\":\"48\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:55:01', '2026-02-08 00:55:01'),
(58, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 14_20260208015526', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"head_teacher_vi\",\"position_applied\":\"Head Teacher VI\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 19\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 14\",\"application_code\":\"NT-AOII-2026-017 TEST 14\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"32\",\"baseline_experience\":\"60\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:55:26', '2026-02-08 00:55:26'),
(59, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 15_20260208015547', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"teacher_ii\",\"position_applied\":\"Teacher II\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 12\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 15\",\"application_code\":\"NT-AOII-2026-017 TEST 15\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"8\",\"baseline_experience\":\"12\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:55:47', '2026-02-08 00:55:47'),
(60, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 16_20260208015608', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"master_teacher_iii\",\"position_applied\":\"Master Teacher III\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 16\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 16\",\"application_code\":\"NT-AOII-2026-017 TEST 16\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"18\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"24\",\"baseline_experience\":\"36\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:56:08', '2026-02-08 00:56:08'),
(61, 'lq1q8are4td0f3hn4ihkpjutmr', 'NT-AOII-2026-017 TEST 17_20260208015850', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"1\",\"position_key\":\"head_teacher_i\",\"position_applied\":\"Head Teacher I\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 14\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 17\",\"application_code\":\"NT-AOII-2026-017 TEST 17\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"8\",\"baseline_experience\":\"12\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-08 00:58:50', '2026-02-08 00:58:50'),
(62, 'bq13tvc95vak3fc88ibepp62jb', 'NT-AOII-2026-017 TEST 18_20260209021021', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 18\",\"application_code\":\"NT-AOII-2026-017 TEST 18\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-09 01:10:21', '2026-02-09 01:10:21'),
(63, 'bq13tvc95vak3fc88ibepp62jb', 'NT-AOII-2026-017 TEST 19_20260209021230', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 19\",\"application_code\":\"NT-AOII-2026-017 TEST 19\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-09 01:12:30', '2026-02-09 01:12:30'),
(64, 'bq13tvc95vak3fc88ibepp62jb', 'NT-AOII-2026-017 TEST 20_20260209021434', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 20\",\"application_code\":\"NT-AOII-2026-017 TEST 20\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-02-09 01:14:34', '2026-02-09 01:14:34'),
(65, '8j5jjl1pu2p223fqg71kd44ghg', 'NT-AOII-2026-017 TEST 20_20260304090754', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"\",\"position_key\":\"custom\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 20\",\"application_code\":\"NT-AOII-2026-017 TEST 20\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"9\",\"applicant_education_degree\":\"Master\",\"applicant_education_masters_units\":\"9\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"12\",\"applicant_training\":\"92\",\"applicant_experience_dropdown\":\"16\",\"applicant_experience\":\"93\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-03-04 08:07:54', '2026-03-04 08:07:54'),
(66, 'emq4hge7censl1qufma76rngp8', 'NT-AOII-2026-017 TEST 20_20260305010556', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"accountant_ii\",\"position_applied\":\"Accountant II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 16\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 22\",\"application_code\":\"NT-AOII-2026-017 TEST 20\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-03-05 00:05:56', '2026-03-05 00:05:56'),
(67, 'emq4hge7censl1qufma76rngp8', 'NT-AOII-2026-017 TEST 22_20260305010605', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"\",\"position_key\":\"custom\",\"position_applied\":\"Accountant II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 16\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 22\",\"application_code\":\"NT-AOII-2026-017 TEST 22\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"html\"}', '2026-03-05 00:06:05', '2026-03-05 00:06:05'),
(68, 'jjoiafog5l16hgi3c5m6b2ajql', 'NT-AOII-2026-017 TEST 22_20260307035715', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"3\",\"position_key\":\"teacher_credentials_evaluator_i\",\"position_applied\":\"Teacher Credentials Evaluator I\",\"job_group_sg_level\":\"Group RELATED TEACHING POSITION \\/ Salary Grade 13\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 23\",\"application_code\":\"NT-AOII-2026-017 TEST 22\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"8\",\"baseline_experience\":\"12\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 02:57:15', '2026-03-07 02:57:15'),
(69, 'jjoiafog5l16hgi3c5m6b2ajql', 'NT-AOII-2026-017 TEST 23_20260307035722', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"\",\"position_key\":\"custom\",\"position_applied\":\"Teacher Credentials Evaluator I\",\"job_group_sg_level\":\"Group RELATED TEACHING POSITION \\/ Salary Grade 13\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 23\",\"application_code\":\"NT-AOII-2026-017 TEST 23\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"8\",\"baseline_experience\":\"12\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 02:57:22', '2026-03-07 02:57:22'),
(70, 'jjoiafog5l16hgi3c5m6b2ajql', 'NT-AOII-2026-017 TEST 24_20260307035801', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"2\",\"position_key\":\"principal_iii\",\"position_applied\":\"School Principal III\",\"job_group_sg_level\":\"Group SCHOOL ADMINISTRATION POSITION \\/ Salary Grade 21\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 24\",\"application_code\":\"NT-AOII-2026-017 TEST 24\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"48\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 02:58:01', '2026-03-07 02:58:01'),
(71, 'jjoiafog5l16hgi3c5m6b2ajql', 'NT-AOII-2026-017 TEST 25_20260307040738', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"2\",\"position_key\":\"principal_iii\",\"position_applied\":\"School Principal III\",\"job_group_sg_level\":\"Group SCHOOL ADMINISTRATION POSITION \\/ Salary Grade 21\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 25\",\"application_code\":\"NT-AOII-2026-017 TEST 25\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"1\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"0\",\"applicant_potential\":\"0\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"48\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 03:07:38', '2026-03-07 03:07:38'),
(72, 'jjoiafog5l16hgi3c5m6b2ajql', 'NT-AOII-2026-017 TEST 26_20260307041814', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 26\",\"application_code\":\"NT-AOII-2026-017 TEST 26\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 03:18:14', '2026-03-07 03:18:14');
INSERT INTO `drafts` (`id`, `session_id`, `application_code`, `data`, `created_at`, `updated_at`) VALUES
(73, 'jjoiafog5l16hgi3c5m6b2ajql', 'NT-AOII-2026-017 TEST 27_20260307041950', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"admin_officer_ii\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 27\",\"application_code\":\"NT-AOII-2026-017 TEST 27\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 03:19:50', '2026-03-07 03:19:50'),
(74, 'jjoiafog5l16hgi3c5m6b2ajql', 'NT-AOII-2026-017 TEST 26_20260307084403', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"\",\"position_key\":\"custom\",\"position_applied\":\"School Principal III\",\"job_group_sg_level\":\"Group SCHOOL ADMINISTRATION POSITION \\/ Salary Grade 21\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 26\",\"application_code\":\"NT-AOII-2026-017 TEST 26\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"48\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 07:44:03', '2026-03-07 07:44:03'),
(75, '9ujvvge8o93cf929t9ebdu02ft', 'NT-AOII-2026-017 TEST 27_20260307090810', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"2\",\"position_key\":\"principal_iii\",\"position_applied\":\"School Principal III\",\"job_group_sg_level\":\"Group SCHOOL ADMINISTRATION POSITION \\/ Salary Grade 21\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 27\",\"application_code\":\"NT-AOII-2026-017 TEST 27\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"48\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 08:08:10', '2026-03-07 08:08:10'),
(76, '9ujvvge8o93cf929t9ebdu02ft', 'NT-AOII-2026-017 TEST 28_20260307091039', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"2\",\"position_key\":\"principal_iii\",\"position_applied\":\"School Principal III\",\"job_group_sg_level\":\"Group SCHOOL ADMINISTRATION POSITION \\/ Salary Grade 21\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 28\",\"application_code\":\"NT-AOII-2026-017 TEST 28\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"48\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 08:10:39', '2026-03-07 08:10:39'),
(77, '9ujvvge8o93cf929t9ebdu02ft', 'NT-AOII-2026-017 TEST 29_20260307092406', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"agriculturist_ii\",\"position_applied\":\"Agriculturist II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 15\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 29\",\"application_code\":\"NT-AOII-2026-017 TEST 29\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"16\",\"baseline_experience\":\"24\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 08:24:06', '2026-03-07 08:24:06'),
(78, '9ujvvge8o93cf929t9ebdu02ft', 'NT-AOII-2026-017 TEST 30_20260307093000', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"5\",\"position_key\":\"clerk_ii\",\"position_applied\":\"Clerk II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 4\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 30\",\"application_code\":\"NT-AOII-2026-017 TEST 30\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-07 08:30:00', '2026-03-07 08:30:00'),
(79, 'vtbrv4b1p3rlurv0stp0t5vetq', 'NT-AOII-2026-017 TEST 230_20260318040137', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"\",\"position_key\":\"custom\",\"position_applied\":\"Administrative Officer II\",\"job_group_sg_level\":\"Group NON-TEACHING LEVEL II \\/ Salary Grade 11\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 230\",\"application_code\":\"NT-AOII-2026-017 TEST 230\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"0\",\"baseline_experience\":\"0\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-18 03:01:37', '2026-03-18 03:01:37'),
(80, 'vtbrv4b1p3rlurv0stp0t5vetq', 'NT-AOII-2026-017 TEST 231_20260318040217', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"2\",\"position_key\":\"ass_principal_i\",\"position_applied\":\"Assistant School Principal I\",\"job_group_sg_level\":\"Group SCHOOL ADMINISTRATION POSITION \\/ Salary Grade 18\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 231\",\"application_code\":\"NT-AOII-2026-017 TEST 231\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"24\",\"baseline_experience\":\"36\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"hrmpsb_chair\":\"RANDY D. PUNZALAN, CESO VI\",\"output_format\":\"pdf\"}', '2026-03-18 03:02:17', '2026-03-18 03:02:17'),
(81, 'vtbrv4b1p3rlurv0stp0t5vetq', 'NT-AOII-2026-017 TEST 222_20260318040501', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"2\",\"position_key\":\"principal_ii\",\"position_applied\":\"School Principal II\",\"job_group_sg_level\":\"Group SCHOOL ADMINISTRATION POSITION \\/ Salary Grade 20\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 222\",\"application_code\":\"NT-AOII-2026-017 TEST 222\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Master\",\"baseline_education_masters_units\":\"0\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"40\",\"baseline_experience\":\"60\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\",\"output_format\":\"html\"}', '2026-03-18 03:05:01', '2026-03-18 03:05:01'),
(82, 'r9ljmesb3jmshg24p5qlg5m1s6', 'NT-AOII-2026-017 TEST 222_20260324004045', '{\"save_to_database\":\"1\",\"save_to_car\":\"1\",\"position_group\":\"\",\"position_key\":\"custom\",\"position_applied\":\"Master Teacher III\",\"job_group_sg_level\":\"Group HIGHER TEACHING POSITIONS \\/ Salary Grade 16\",\"applicant_name\":\"Michelle Ann A. Bornasal TEST 222\",\"application_code\":\"NT-AOII-2026-017 TEST 222\",\"schools_division_office\":\"City Schools Division of Cabuyao\",\"contact_number\":\"09104617060\",\"applicant_education_dropdown\":\"6\",\"applicant_education_degree\":\"Bachelor\",\"applicant_education_masters_units\":\"0\",\"applicant_education_doctoral_units\":\"0\",\"applicant_training_dropdown\":\"16\",\"applicant_training\":\"124\",\"applicant_experience_dropdown\":\"17\",\"applicant_experience\":\"99\",\"applicant_performance\":\"2.995\",\"applicant_outstanding_accomplishments\":\"3\",\"applicant_application_of_education\":\"8.50\",\"applicant_application_of_ld\":\"1\",\"applicant_potential\":\"2\",\"baseline_education_degree\":\"Bachelor\",\"baseline_education_masters_units\":\"18\",\"baseline_education_doctoral_units\":\"0\",\"baseline_training\":\"24\",\"baseline_experience\":\"36\",\"baseline_performance\":\"0\",\"baseline_outstanding_accomplishments\":\"0\",\"baseline_application_of_education\":\"0\",\"baseline_application_of_ld\":\"0\",\"baseline_potential\":\"0\"}', '2026-03-23 23:40:45', '2026-03-23 23:40:45');

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

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `applicant_id`, `position_id`, `position_group`, `total_score`, `evaluation_date`, `evaluator_name`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(36, 59, 962, 'NON-TEACHING LEVEL II', 51.48, '2026-02-06', NULL, 'pending', 'Evaluation created from form', '2026-02-06 13:12:54', '2026-02-06 13:12:54'),
(37, 60, 1050, 'NON-TEACHING LEVEL I', 50.98, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:15:56', '2026-02-08 00:15:56'),
(38, 61, 962, 'NON-TEACHING LEVEL I', 51.48, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:27:12', '2026-02-08 00:27:12'),
(39, 62, 875, 'NON-TEACHING LEVEL I', 32.48, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:36:15', '2026-02-08 00:36:15'),
(40, 63, 833, 'NON-TEACHING LEVEL I', 45.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:36:49', '2026-02-08 00:36:49'),
(41, 64, 962, 'NON-TEACHING LEVEL I', 51.48, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:44:37', '2026-02-08 00:44:37'),
(42, 65, 833, 'NON-TEACHING LEVEL I', 45.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:49:02', '2026-02-08 00:49:02'),
(43, 66, 834, 'NON-TEACHING LEVEL I', 34.48, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:49:35', '2026-02-08 00:49:35'),
(44, 67, 827, 'NON-TEACHING LEVEL I', 40.48, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:49:58', '2026-02-08 00:49:58'),
(45, 68, 866, 'NON-TEACHING LEVEL I', 39.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:50:21', '2026-02-08 00:50:21'),
(46, 69, 962, 'NON-TEACHING LEVEL I', 55.48, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:51:48', '2026-02-08 00:51:48'),
(47, 70, 832, 'NON-TEACHING LEVEL I', 43.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:53:32', '2026-02-08 00:53:32'),
(48, 71, 831, 'NON-TEACHING LEVEL I', 41.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:54:07', '2026-02-08 00:54:07'),
(49, 72, 830, 'NON-TEACHING LEVEL I', 39.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:54:35', '2026-02-08 00:54:35'),
(50, 73, 829, 'NON-TEACHING LEVEL I', 37.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:55:01', '2026-02-08 00:55:01'),
(51, 74, 823, 'NON-TEACHING LEVEL I', 25.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:55:26', '2026-02-08 00:55:26'),
(52, 75, 1061, 'NON-TEACHING LEVEL I', 45.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:55:47', '2026-02-08 00:55:47'),
(53, 76, 866, 'NON-TEACHING LEVEL I', 39.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:56:08', '2026-02-08 00:56:08'),
(54, 77, 833, 'NON-TEACHING LEVEL I', 45.49, '2026-02-08', NULL, 'pending', 'Evaluation created from form', '2026-02-08 00:58:50', '2026-02-08 00:58:50'),
(55, 78, 962, 'NON-TEACHING LEVEL I', 51.48, '2026-02-09', NULL, 'pending', 'Evaluation created from form', '2026-02-09 01:10:22', '2026-02-09 01:10:22'),
(56, 79, 962, 'NON-TEACHING LEVEL I', 51.48, '2026-02-09', NULL, 'pending', 'Evaluation created from form', '2026-02-09 01:12:30', '2026-02-09 01:12:30'),
(57, 80, 962, 'NON-TEACHING LEVEL I', 51.48, '2026-02-09', NULL, 'pending', 'Evaluation created from form', '2026-02-09 01:14:34', '2026-02-09 01:14:34'),
(58, 81, 920, 'NON-TEACHING LEVEL I', 46.48, '2026-03-05', NULL, 'pending', 'Evaluation created from form', '2026-03-05 00:06:05', '2026-03-05 00:06:05'),
(59, 82, 855, 'NON-TEACHING LEVEL I', 46.48, '2026-03-07', NULL, 'pending', 'Evaluation created from form', '2026-03-07 02:57:22', '2026-03-07 02:57:22'),
(60, 83, 818, 'NON-TEACHING LEVEL I', 42.48, '2026-03-07', NULL, 'pending', 'Evaluation created from form', '2026-03-07 02:58:01', '2026-03-07 02:58:01'),
(61, 84, 818, 'NON-TEACHING LEVEL I', 42.48, '2026-03-07', NULL, 'pending', 'Evaluation created from form', '2026-03-07 03:07:38', '2026-03-07 03:07:38'),
(62, 85, 962, 'NON-TEACHING LEVEL I', 58.48, '2026-03-07', NULL, 'pending', 'Evaluation created from form', '2026-03-07 03:18:14', '2026-03-07 03:18:14'),
(63, 86, 818, 'NON-TEACHING LEVEL I', 52.48, '2026-03-07', NULL, 'pending', 'Evaluation created from form', '2026-03-07 08:08:10', '2026-03-07 08:08:10'),
(64, 87, 818, 'NON-TEACHING LEVEL I', 52.48, '2026-03-07', NULL, 'pending', 'Evaluation created from form', '2026-03-07 08:10:39', '2026-03-07 08:10:39'),
(65, 88, 927, 'NON-TEACHING LEVEL I', 58.48, '2026-03-07', NULL, 'pending', 'Evaluation created from form', '2026-03-07 08:24:06', '2026-03-07 08:24:06'),
(66, 89, 1027, 'NON-TEACHING LEVEL I', 58.48, '2026-03-07', NULL, 'pending', 'Evaluation created from form', '2026-03-07 08:30:00', '2026-03-07 08:30:00'),
(67, 90, 962, 'NON-TEACHING LEVEL I', 58.48, '2026-03-18', NULL, 'pending', 'Evaluation created from form', '2026-03-18 03:01:37', '2026-03-18 03:01:37'),
(68, 91, 820, 'NON-TEACHING LEVEL I', 50.48, '2026-03-18', NULL, 'pending', 'Evaluation created from form', '2026-03-18 03:05:01', '2026-03-18 03:05:01');

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

--
-- Dumping data for table `evaluation_details`
--

INSERT INTO `evaluation_details` (`id`, `evaluation_id`, `criterion`, `applicant_qualification`, `applicant_level`, `baseline_qualification`, `baseline_level`, `increment`, `weight`, `points`, `final_score`, `created_at`) VALUES
(273, 36, 'Education', 'Master', 21, '0', 6, 15, 5, 0.00, 5.00, '2026-02-06 13:12:54'),
(274, 36, 'Training', '92 hours', 12, '0', 1, 11, 10, 0.00, 10.00, '2026-02-06 13:12:54'),
(275, 36, 'Experience', '93 months', 16, '0', 1, 15, 15, 0.00, 15.00, '2026-02-06 13:12:54'),
(276, 36, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-02-06 13:12:54'),
(277, 36, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-06 13:12:54'),
(278, 36, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-02-06 13:12:54'),
(279, 36, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-06 13:12:54'),
(280, 36, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-02-06 13:12:54'),
(281, 37, 'Education', 'Master', 12, '0', 6, 6, 5, 0.00, 3.00, '2026-02-08 00:15:56'),
(282, 37, 'Training', '92 hours', 12, '0', 1, 11, 5, 0.00, 5.00, '2026-02-08 00:15:56'),
(283, 37, 'Experience', '93 months', 16, '0', 1, 15, 20, 0.00, 20.00, '2026-02-08 00:15:56'),
(284, 37, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-02-08 00:15:56'),
(285, 37, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-08 00:15:56'),
(286, 37, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 10.00, '2026-02-08 00:15:56'),
(287, 37, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-08 00:15:56'),
(288, 37, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-02-08 00:15:56'),
(289, 38, 'Education', 'Master', 21, '0', 6, 15, 5, 0.00, 5.00, '2026-02-08 00:27:12'),
(290, 38, 'Training', '92 hours', 12, '0', 1, 11, 10, 0.00, 10.00, '2026-02-08 00:27:12'),
(291, 38, 'Experience', '93 months', 16, '0', 1, 15, 15, 0.00, 15.00, '2026-02-08 00:27:12'),
(292, 38, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-02-08 00:27:12'),
(293, 38, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-08 00:27:12'),
(294, 38, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-02-08 00:27:12'),
(295, 38, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-08 00:27:12'),
(296, 38, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-02-08 00:27:12'),
(297, 39, 'Education', 'Master', 21, '0', 21, 0, 5, 0.00, 0.00, '2026-02-08 00:36:15'),
(298, 39, 'Training', '92 hours', 12, '40', 6, 6, 5, 0.00, 3.00, '2026-02-08 00:36:15'),
(299, 39, 'Experience', '93 months', 16, '60', 11, 5, 20, 0.00, 8.00, '2026-02-08 00:36:15'),
(300, 39, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-02-08 00:36:15'),
(301, 39, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-08 00:36:15'),
(302, 39, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-02-08 00:36:15'),
(303, 39, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-08 00:36:15'),
(304, 39, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-02-08 00:36:15'),
(305, 40, 'Education', 'Master', 21, '0', 6, 15, 10, 0.00, 10.00, '2026-02-08 00:36:49'),
(306, 40, 'Training', '92 hours', 12, '8', 2, 10, 10, 0.00, 10.00, '2026-02-08 00:36:49'),
(307, 40, 'Experience', '93 months', 16, '12', 3, 13, 10, 0.00, 10.00, '2026-02-08 00:36:49'),
(308, 40, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:36:49'),
(309, 40, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:36:49'),
(310, 40, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:36:49'),
(311, 40, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:36:49'),
(312, 40, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:36:49'),
(313, 41, 'Education', 'Master', 21, '0', 6, 15, 5, 0.00, 5.00, '2026-02-08 00:44:37'),
(314, 41, 'Training', '92 hours', 12, '0', 1, 11, 10, 0.00, 10.00, '2026-02-08 00:44:37'),
(315, 41, 'Experience', '93 months', 16, '0', 1, 15, 15, 0.00, 15.00, '2026-02-08 00:44:37'),
(316, 41, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-02-08 00:44:37'),
(317, 41, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-08 00:44:37'),
(318, 41, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-02-08 00:44:37'),
(319, 41, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-08 00:44:37'),
(320, 41, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-02-08 00:44:37'),
(321, 42, 'Education', 'Master', 21, '0', 6, 15, 10, 0.00, 10.00, '2026-02-08 00:49:02'),
(322, 42, 'Training', '92 hours', 12, '8', 2, 10, 10, 0.00, 10.00, '2026-02-08 00:49:02'),
(323, 42, 'Experience', '93 months', 16, '12', 3, 13, 10, 0.00, 10.00, '2026-02-08 00:49:02'),
(324, 42, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:49:02'),
(325, 42, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:49:02'),
(326, 42, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:49:02'),
(327, 42, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:49:02'),
(328, 42, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:49:02'),
(329, 43, 'Education', 'Master', 21, '0', 21, 0, 10, 0.00, 0.00, '2026-02-08 00:49:35'),
(330, 43, 'Training', '92 hours', 12, '40', 6, 6, 10, 0.00, 6.00, '2026-02-08 00:49:35'),
(331, 43, 'Experience', '93 months', 16, '60', 11, 5, 10, 0.00, 4.00, '2026-02-08 00:49:35'),
(332, 43, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 25, 0.00, 14.98, '2026-02-08 00:49:35'),
(333, 43, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-08 00:49:35'),
(334, 43, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-02-08 00:49:35'),
(335, 43, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-08 00:49:35'),
(336, 43, 'Potential', 'Level 0', 0, '0', 0, 0, 15, 0.00, 0.00, '2026-02-08 00:49:35'),
(337, 44, 'Education', 'Master', 21, '0', 21, 0, 10, 0.00, 0.00, '2026-02-08 00:49:58'),
(338, 44, 'Training', '92 hours', 12, '24', 4, 8, 10, 0.00, 8.00, '2026-02-08 00:49:58'),
(339, 44, 'Experience', '93 months', 16, '36', 7, 9, 10, 0.00, 8.00, '2026-02-08 00:49:58'),
(340, 44, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 25, 0.00, 14.98, '2026-02-08 00:49:58'),
(341, 44, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-08 00:49:58'),
(342, 44, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-02-08 00:49:58'),
(343, 44, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-08 00:49:58'),
(344, 44, 'Potential', 'Level 0', 0, '0', 0, 0, 15, 0.00, 0.00, '2026-02-08 00:49:58'),
(345, 45, 'Education', 'Master', 21, '0', 12, 9, 10, 0.00, 8.00, '2026-02-08 00:50:21'),
(346, 45, 'Training', '92 hours', 12, '24', 4, 8, 10, 0.00, 8.00, '2026-02-08 00:50:21'),
(347, 45, 'Experience', '93 months', 16, '36', 7, 9, 10, 0.00, 8.00, '2026-02-08 00:50:21'),
(348, 45, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:50:21'),
(349, 45, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:50:21'),
(350, 45, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:50:21'),
(351, 45, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:50:21'),
(352, 45, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:50:21'),
(353, 46, 'Education', 'Master', 21, '0', 6, 15, 5, 0.00, 5.00, '2026-02-08 00:51:48'),
(354, 46, 'Training', '92 hours', 12, '0', 1, 11, 10, 0.00, 10.00, '2026-02-08 00:51:48'),
(355, 46, 'Experience', '93 months', 16, '0', 1, 15, 15, 0.00, 15.00, '2026-02-08 00:51:48'),
(356, 46, 'Performance Rating', '3.995/5', 0, '0', 0, 0, 20, 0.00, 15.98, '2026-02-08 00:51:48'),
(357, 46, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-08 00:51:48'),
(358, 46, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-02-08 00:51:48'),
(359, 46, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-08 00:51:48'),
(360, 46, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-02-08 00:51:48'),
(361, 47, 'Education', 'Master', 21, '0', 6, 15, 10, 0.00, 10.00, '2026-02-08 00:53:32'),
(362, 47, 'Training', '92 hours', 12, '16', 3, 9, 10, 0.00, 8.00, '2026-02-08 00:53:32'),
(363, 47, 'Experience', '93 months', 16, '24', 5, 11, 10, 0.00, 10.00, '2026-02-08 00:53:32'),
(364, 47, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:53:32'),
(365, 47, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:53:32'),
(366, 47, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:53:32'),
(367, 47, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:53:32'),
(368, 47, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:53:32'),
(369, 48, 'Education', 'Master', 21, '0', 12, 9, 10, 0.00, 8.00, '2026-02-08 00:54:07'),
(370, 48, 'Training', '92 hours', 12, '16', 3, 9, 10, 0.00, 8.00, '2026-02-08 00:54:07'),
(371, 48, 'Experience', '93 months', 16, '24', 5, 11, 10, 0.00, 10.00, '2026-02-08 00:54:07'),
(372, 48, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:54:07'),
(373, 48, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:54:07'),
(374, 48, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:54:07'),
(375, 48, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:54:07'),
(376, 48, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:54:07'),
(377, 49, 'Education', 'Master', 21, '0', 12, 9, 10, 0.00, 8.00, '2026-02-08 00:54:35'),
(378, 49, 'Training', '92 hours', 12, '24', 4, 8, 10, 0.00, 8.00, '2026-02-08 00:54:35'),
(379, 49, 'Experience', '93 months', 16, '36', 7, 9, 10, 0.00, 8.00, '2026-02-08 00:54:35'),
(380, 49, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:54:35'),
(381, 49, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:54:35'),
(382, 49, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:54:35'),
(383, 49, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:54:35'),
(384, 49, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:54:35'),
(385, 50, 'Education', 'Master', 21, '0', 12, 9, 10, 0.00, 8.00, '2026-02-08 00:55:01'),
(386, 50, 'Training', '92 hours', 12, '24', 4, 8, 10, 0.00, 8.00, '2026-02-08 00:55:01'),
(387, 50, 'Experience', '93 months', 16, '48', 9, 7, 10, 0.00, 6.00, '2026-02-08 00:55:01'),
(388, 50, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:55:01'),
(389, 50, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:55:01'),
(390, 50, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:55:01'),
(391, 50, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:55:01'),
(392, 50, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:55:01'),
(393, 51, 'Education', 'Master', 21, '0', 21, 0, 10, 0.00, 0.00, '2026-02-08 00:55:26'),
(394, 51, 'Training', '92 hours', 12, '32', 5, 7, 10, 0.00, 6.00, '2026-02-08 00:55:26'),
(395, 51, 'Experience', '93 months', 16, '60', 11, 5, 10, 0.00, 4.00, '2026-02-08 00:55:26'),
(396, 51, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:55:26'),
(397, 51, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:55:26'),
(398, 51, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:55:26'),
(399, 51, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:55:26'),
(400, 51, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:55:26'),
(401, 52, 'Education', 'Master', 21, '0', 6, 15, 10, 0.00, 10.00, '2026-02-08 00:55:47'),
(402, 52, 'Training', '92 hours', 12, '8', 2, 10, 10, 0.00, 10.00, '2026-02-08 00:55:47'),
(403, 52, 'Experience', '93 months', 16, '12', 3, 13, 10, 0.00, 10.00, '2026-02-08 00:55:47'),
(404, 52, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:55:47'),
(405, 52, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:55:47'),
(406, 52, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:55:47'),
(407, 52, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:55:47'),
(408, 52, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:55:47'),
(409, 53, 'Education', 'Master', 21, '0', 12, 9, 10, 0.00, 8.00, '2026-02-08 00:56:08'),
(410, 53, 'Training', '92 hours', 12, '24', 4, 8, 10, 0.00, 8.00, '2026-02-08 00:56:08'),
(411, 53, 'Experience', '93 months', 16, '36', 7, 9, 10, 0.00, 8.00, '2026-02-08 00:56:08'),
(412, 53, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:56:08'),
(413, 53, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:56:08'),
(414, 53, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:56:08'),
(415, 53, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:56:08'),
(416, 53, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:56:08'),
(417, 54, 'Education', 'Master', 21, '0', 6, 15, 10, 0.00, 10.00, '2026-02-08 00:58:50'),
(418, 54, 'Training', '92 hours', 12, '8', 2, 10, 10, 0.00, 10.00, '2026-02-08 00:58:50'),
(419, 54, 'Experience', '93 months', 16, '12', 3, 13, 10, 0.00, 10.00, '2026-02-08 00:58:50'),
(420, 54, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 10, 0.00, 5.99, '2026-02-08 00:58:50'),
(421, 54, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 35, 0.00, 1.00, '2026-02-08 00:58:50'),
(422, 54, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 25, 0.00, 8.50, '2026-02-08 00:58:50'),
(423, 54, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:58:50'),
(424, 54, 'Potential', 'Level 0', 0, '0', 0, 0, 0, 0.00, 0.00, '2026-02-08 00:58:50'),
(425, 55, 'Education', 'Master', 21, '0', 6, 15, 5, 0.00, 5.00, '2026-02-09 01:10:22'),
(426, 55, 'Training', '92 hours', 12, '0', 1, 11, 10, 0.00, 10.00, '2026-02-09 01:10:22'),
(427, 55, 'Experience', '93 months', 16, '0', 1, 15, 15, 0.00, 15.00, '2026-02-09 01:10:22'),
(428, 55, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-02-09 01:10:22'),
(429, 55, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-09 01:10:22'),
(430, 55, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-02-09 01:10:22'),
(431, 55, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-09 01:10:22'),
(432, 55, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-02-09 01:10:22'),
(433, 56, 'Education', 'Master', 21, '0', 6, 15, 5, 0.00, 5.00, '2026-02-09 01:12:30'),
(434, 56, 'Training', '92 hours', 12, '0', 1, 11, 10, 0.00, 10.00, '2026-02-09 01:12:30'),
(435, 56, 'Experience', '93 months', 16, '0', 1, 15, 15, 0.00, 15.00, '2026-02-09 01:12:30'),
(436, 56, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-02-09 01:12:30'),
(437, 56, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-09 01:12:30'),
(438, 56, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-02-09 01:12:30'),
(439, 56, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-09 01:12:30'),
(440, 56, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-02-09 01:12:30'),
(441, 57, 'Education', 'Master', 21, '0', 6, 15, 5, 0.00, 5.00, '2026-02-09 01:14:34'),
(442, 57, 'Training', '92 hours', 12, '0', 1, 11, 10, 0.00, 10.00, '2026-02-09 01:14:34'),
(443, 57, 'Experience', '93 months', 16, '0', 1, 15, 15, 0.00, 15.00, '2026-02-09 01:14:34'),
(444, 57, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-02-09 01:14:34'),
(445, 57, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-02-09 01:14:34'),
(446, 57, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-02-09 01:14:34'),
(447, 57, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-02-09 01:14:34'),
(448, 57, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-02-09 01:14:34'),
(449, 58, 'Education', 'Bachelor', 6, '0', 6, 0, 5, 0.00, 0.00, '2026-03-05 00:06:05'),
(450, 58, 'Training', '124 hours', 16, '16', 3, 13, 5, 0.00, 5.00, '2026-03-05 00:06:05'),
(451, 58, 'Experience', '99 months', 17, '24', 5, 12, 20, 0.00, 20.00, '2026-03-05 00:06:05'),
(452, 58, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-03-05 00:06:05'),
(453, 58, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-03-05 00:06:05'),
(454, 58, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-05 00:06:05'),
(455, 58, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-03-05 00:06:05'),
(456, 58, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-03-05 00:06:05'),
(457, 59, 'Education', 'Bachelor', 6, '0', 6, 0, 5, 0.00, 0.00, '2026-03-07 02:57:22'),
(458, 59, 'Training', '124 hours', 16, '8', 2, 14, 5, 0.00, 5.00, '2026-03-07 02:57:22'),
(459, 59, 'Experience', '99 months', 17, '12', 3, 14, 20, 0.00, 20.00, '2026-03-07 02:57:22'),
(460, 59, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-03-07 02:57:22'),
(461, 59, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-03-07 02:57:22'),
(462, 59, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-07 02:57:22'),
(463, 59, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-03-07 02:57:22'),
(464, 59, 'Potential', 'Level 0', 0, '0', 0, 0, 20, 0.00, 0.00, '2026-03-07 02:57:22'),
(465, 60, 'Education', 'Bachelor', 6, '0', 21, 0, 10, 0.00, 0.00, '2026-03-07 02:58:01'),
(466, 60, 'Training', '124 hours', 16, '40', 6, 10, 10, 0.00, 10.00, '2026-03-07 02:58:01'),
(467, 60, 'Experience', '99 months', 17, '48', 9, 8, 10, 0.00, 8.00, '2026-03-07 02:58:01'),
(468, 60, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 25, 0.00, 14.98, '2026-03-07 02:58:01'),
(469, 60, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-03-07 02:58:01'),
(470, 60, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-07 02:58:01'),
(471, 60, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-03-07 02:58:01'),
(472, 60, 'Potential', 'Level 0', 0, '0', 0, 0, 15, 0.00, 0.00, '2026-03-07 02:58:01'),
(473, 61, 'Education', 'Bachelor', 6, '0', 21, 0, 10, 0.00, 0.00, '2026-03-07 03:07:38'),
(474, 61, 'Training', '124 hours', 16, '40', 6, 10, 10, 0.00, 10.00, '2026-03-07 03:07:38'),
(475, 61, 'Experience', '99 months', 17, '48', 9, 8, 10, 0.00, 8.00, '2026-03-07 03:07:38'),
(476, 61, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 25, 0.00, 14.98, '2026-03-07 03:07:38'),
(477, 61, 'Outstanding Accomplishments', '1', 0, '0', 0, 0, 10, 0.00, 1.00, '2026-03-07 03:07:38'),
(478, 61, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-07 03:07:38'),
(479, 61, 'Application of L&D', 'Level 0', 0, '0', 0, 0, 10, 0.00, 0.00, '2026-03-07 03:07:38'),
(480, 61, 'Potential', 'Level 0', 0, '0', 0, 0, 15, 0.00, 0.00, '2026-03-07 03:07:38'),
(481, 62, 'Education', 'Bachelor', 6, '0', 6, 0, 5, 0.00, 0.00, '2026-03-07 03:18:14'),
(482, 62, 'Training', '124 hours', 16, '0', 1, 15, 10, 0.00, 10.00, '2026-03-07 03:18:14'),
(483, 62, 'Experience', '99 months', 17, '0', 1, 16, 15, 0.00, 15.00, '2026-03-07 03:18:14'),
(484, 62, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-03-07 03:18:14'),
(485, 62, 'Outstanding Accomplishments', '3', 0, '0', 0, 0, 10, 0.00, 3.00, '2026-03-07 03:18:14'),
(486, 62, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-07 03:18:14'),
(487, 62, 'Application of L&D', 'Level 1', 0, '0', 0, 0, 10, 0.00, 2.00, '2026-03-07 03:18:14'),
(488, 62, 'Potential', 'Level 2', 0, '0', 0, 0, 20, 0.00, 8.00, '2026-03-07 03:18:14'),
(489, 63, 'Education', 'Bachelor', 6, '0', 21, 0, 10, 0.00, 0.00, '2026-03-07 08:08:10'),
(490, 63, 'Training', '124 hours', 16, '40', 6, 10, 10, 0.00, 10.00, '2026-03-07 08:08:10'),
(491, 63, 'Experience', '99 months', 17, '48', 9, 8, 10, 0.00, 8.00, '2026-03-07 08:08:10'),
(492, 63, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 25, 0.00, 14.98, '2026-03-07 08:08:10'),
(493, 63, 'Outstanding Accomplishments', '3', 0, '0', 0, 0, 10, 0.00, 3.00, '2026-03-07 08:08:10'),
(494, 63, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-07 08:08:10'),
(495, 63, 'Application of L&D', 'Level 1', 0, '0', 0, 0, 10, 0.00, 2.00, '2026-03-07 08:08:10'),
(496, 63, 'Potential', 'Level 2', 0, '0', 0, 0, 15, 0.00, 6.00, '2026-03-07 08:08:10'),
(497, 64, 'Education', 'Bachelor', 6, '0', 21, 0, 10, 0.00, 0.00, '2026-03-07 08:10:39'),
(498, 64, 'Training', '124 hours', 16, '40', 6, 10, 10, 0.00, 10.00, '2026-03-07 08:10:39'),
(499, 64, 'Experience', '99 months', 17, '48', 9, 8, 10, 0.00, 8.00, '2026-03-07 08:10:39'),
(500, 64, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 25, 0.00, 14.98, '2026-03-07 08:10:39'),
(501, 64, 'Outstanding Accomplishments', '3', 0, '0', 0, 0, 10, 0.00, 3.00, '2026-03-07 08:10:39'),
(502, 64, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-07 08:10:39'),
(503, 64, 'Application of L&D', 'Level 1', 0, '0', 0, 0, 10, 0.00, 2.00, '2026-03-07 08:10:39'),
(504, 64, 'Potential', 'Level 2', 0, '0', 0, 0, 15, 0.00, 6.00, '2026-03-07 08:10:39'),
(505, 65, 'Education', 'Bachelor', 6, '0', 6, 0, 5, 0.00, 0.00, '2026-03-07 08:24:06'),
(506, 65, 'Training', '124 hours', 16, '16', 3, 13, 10, 0.00, 10.00, '2026-03-07 08:24:06'),
(507, 65, 'Experience', '99 months', 17, '24', 5, 12, 15, 0.00, 15.00, '2026-03-07 08:24:06'),
(508, 65, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-03-07 08:24:06'),
(509, 65, 'Outstanding Accomplishments', '3', 0, '0', 0, 0, 10, 0.00, 3.00, '2026-03-07 08:24:06'),
(510, 65, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-07 08:24:06'),
(511, 65, 'Application of L&D', 'Level 1', 0, '0', 0, 0, 10, 0.00, 2.00, '2026-03-07 08:24:06'),
(512, 65, 'Potential', 'Level 2', 0, '0', 0, 0, 20, 0.00, 8.00, '2026-03-07 08:24:06'),
(513, 66, 'Education', 'Bachelor', 6, '0', 6, 0, 5, 0.00, 0.00, '2026-03-07 08:30:00'),
(514, 66, 'Training', '124 hours', 16, '0', 1, 15, 10, 0.00, 10.00, '2026-03-07 08:30:00'),
(515, 66, 'Experience', '99 months', 17, '0', 1, 16, 15, 0.00, 15.00, '2026-03-07 08:30:00'),
(516, 66, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-03-07 08:30:00'),
(517, 66, 'Outstanding Accomplishments', '3', 0, '0', 0, 0, 10, 0.00, 3.00, '2026-03-07 08:30:00'),
(518, 66, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-07 08:30:00'),
(519, 66, 'Application of L&D', 'Level 1', 0, '0', 0, 0, 10, 0.00, 2.00, '2026-03-07 08:30:00'),
(520, 66, 'Potential', 'Level 2', 0, '0', 0, 0, 20, 0.00, 8.00, '2026-03-07 08:30:00'),
(521, 67, 'Education', 'Bachelor', 6, '0', 6, 0, 5, 0.00, 0.00, '2026-03-18 03:01:37'),
(522, 67, 'Training', '124 hours', 16, '0', 1, 15, 5, 0.00, 5.00, '2026-03-18 03:01:37'),
(523, 67, 'Experience', '99 months', 17, '0', 1, 16, 20, 0.00, 20.00, '2026-03-18 03:01:37'),
(524, 67, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 20, 0.00, 11.98, '2026-03-18 03:01:37'),
(525, 67, 'Outstanding Accomplishments', '3', 0, '0', 0, 0, 10, 0.00, 3.00, '2026-03-18 03:01:37'),
(526, 67, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-18 03:01:37'),
(527, 67, 'Application of L&D', 'Level 1', 0, '0', 0, 0, 10, 0.00, 2.00, '2026-03-18 03:01:37'),
(528, 67, 'Potential', 'Level 2', 0, '0', 0, 0, 20, 0.00, 8.00, '2026-03-18 03:01:37'),
(529, 68, 'Education', 'Bachelor', 6, '0', 21, 0, 10, 0.00, 0.00, '2026-03-18 03:05:01'),
(530, 68, 'Training', '124 hours', 16, '40', 6, 10, 10, 0.00, 10.00, '2026-03-18 03:05:01'),
(531, 68, 'Experience', '99 months', 17, '60', 11, 6, 10, 0.00, 6.00, '2026-03-18 03:05:01'),
(532, 68, 'Performance Rating', '2.995/5', 0, '0', 0, 0, 25, 0.00, 14.98, '2026-03-18 03:05:01'),
(533, 68, 'Outstanding Accomplishments', '3', 0, '0', 0, 0, 10, 0.00, 3.00, '2026-03-18 03:05:01'),
(534, 68, 'Application of Education', 'Level 8.5', 0, '0', 0, 0, 10, 0.00, 8.50, '2026-03-18 03:05:01'),
(535, 68, 'Application of L&D', 'Level 1', 0, '0', 0, 0, 10, 0.00, 2.00, '2026-03-18 03:05:01'),
(536, 68, 'Potential', 'Level 2', 0, '0', 0, 0, 15, 0.00, 6.00, '2026-03-18 03:05:01');

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
(55, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-05 08:22:16'),
(56, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-06 07:39:02'),
(57, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-08 00:29:01'),
(58, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-09 01:10:51'),
(59, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-02-11 06:33:52'),
(60, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-02 10:05:51'),
(61, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-03-02 10:22:51'),
(62, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-02 10:25:24'),
(63, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-03-02 11:01:22'),
(64, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-02 11:18:53'),
(65, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-03-02 11:41:37'),
(66, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-02 11:46:41'),
(67, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-03 01:30:48'),
(68, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-03-03 01:38:10'),
(69, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-03 02:59:41'),
(70, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-04 08:19:04'),
(71, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-07 02:44:08'),
(72, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-03-07 08:06:11'),
(73, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-07 08:06:18'),
(74, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-10 01:39:53'),
(75, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-11 05:20:00'),
(76, 1, 'admin', 'admin', 'failed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Invalid password', '2026-03-18 00:52:16'),
(77, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-18 00:52:21'),
(78, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-18 03:14:31'),
(79, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-03-18 03:15:27'),
(80, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-19 04:36:09'),
(81, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-19 05:42:02'),
(82, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-03-20 01:08:38'),
(83, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Logout', '2026-03-20 01:09:05'),
(84, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-20 01:09:13'),
(85, 1, 'admin', 'admin', 'success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'Login successful', '2026-03-23 23:29:12');

-- --------------------------------------------------------

--
-- Table structure for table `performance_evaluations`
--

CREATE TABLE `performance_evaluations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `current_position` varchar(255) NOT NULL,
  `position_applied` varchar(255) NOT NULL,
  `station` varchar(255) DEFAULT NULL,
  `item_number` varchar(100) NOT NULL,
  `result` enum('PASSED','FAILED','N/A') NOT NULL DEFAULT 'N/A',
  `performance_payload` longtext DEFAULT NULL,
  `created_by_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `performance_evaluations`
--

INSERT INTO `performance_evaluations` (`id`, `name`, `current_position`, `position_applied`, `station`, `item_number`, `result`, `performance_payload`, `created_by_user_id`, `created_at`, `updated_at`) VALUES
(1, 'Lilibeth Tabajac 1', 'Teacher IV', 'Teacher VII', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience Master Arts In Education Major in Filipino\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":5,\"ncoi_vs\":3,\"coi_o\":16,\"ncoi_o\":13,\"total_vs\":8,\"total_o\":29,\"ppst_counts\":{\"coi_vs\":5,\"coi_o\":16,\"ncoi_vs\":3,\"ncoi_o\":13,\"total_vs\":8,\"total_o\":29},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_vs_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_vs_16\":1,\"ppst_vs_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_vs_23\":1,\"ppst_vs_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_vs_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_vs_32\":1,\"ppst_vs_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-02 11:19:54', '2026-03-02 11:19:54'),
(2, 'Lilibeth Tabajac 1', 'Teacher IV', 'Teacher VII', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2018', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience Master Arts In Education Major in Filipino\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":5,\"ncoi_vs\":3,\"coi_o\":16,\"ncoi_o\":13,\"total_vs\":8,\"total_o\":29,\"ppst_counts\":{\"coi_vs\":5,\"coi_o\":16,\"ncoi_vs\":3,\"ncoi_o\":13,\"total_vs\":8,\"total_o\":29},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_vs_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_vs_16\":1,\"ppst_vs_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_vs_23\":1,\"ppst_vs_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_vs_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_vs_32\":1,\"ppst_vs_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-02 11:21:42', '2026-03-02 11:21:42'),
(4, 'Lilibeth Tabajac 3 test for unauthorized', 'Teacher IV', 'Teacher VII', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2018', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience Master Arts In Education Major in Filipino\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":5,\"ncoi_vs\":3,\"coi_o\":16,\"ncoi_o\":13,\"total_vs\":8,\"total_o\":29,\"ppst_counts\":{\"coi_vs\":5,\"coi_o\":16,\"ncoi_vs\":3,\"ncoi_o\":13,\"total_vs\":8,\"total_o\":29},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_vs_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_vs_16\":1,\"ppst_vs_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_vs_23\":1,\"ppst_vs_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_vs_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_vs_32\":1,\"ppst_vs_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-02 11:28:24', '2026-03-02 11:28:24'),
(12, 'Lilibeth Tabajac 4 test for unauthorized', 'Teacher II', 'Teacher III', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2018', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience Master Arts In Education Major in Filipino\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":5,\"ncoi_vs\":3,\"coi_o\":16,\"ncoi_o\":13,\"total_vs\":8,\"total_o\":29,\"ppst_counts\":{\"coi_vs\":5,\"coi_o\":16,\"ncoi_vs\":3,\"ncoi_o\":13,\"total_vs\":8,\"total_o\":29},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_vs_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_vs_16\":1,\"ppst_vs_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_vs_23\":1,\"ppst_vs_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_vs_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_vs_32\":1,\"ppst_vs_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-02 11:32:34', '2026-03-02 11:32:34'),
(19, 'Lilibeth Tabajac test 10', 'Teacher II', 'Teacher V', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2018', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience Master Arts In Education Major in Filipino\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":6,\"ncoi_vs\":12,\"coi_o\":15,\"ncoi_o\":4,\"total_vs\":18,\"total_o\":19,\"ppst_counts\":{\"coi_vs\":6,\"coi_o\":15,\"ncoi_vs\":12,\"ncoi_o\":4,\"total_vs\":18,\"total_o\":19},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_vs_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_vs_16\":1,\"ppst_vs_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_vs_23\":1,\"ppst_vs_24\":1,\"ppst_vs_25\":1,\"ppst_vs_26\":1,\"ppst_vs_27\":1,\"ppst_vs_28\":1,\"ppst_vs_29\":1,\"ppst_vs_30\":1,\"ppst_vs_31\":1,\"ppst_vs_32\":1,\"ppst_vs_33\":1,\"ppst_vs_34\":1,\"ppst_vs_35\":1,\"ppst_vs_36\":1,\"ppst_vs_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-02 11:49:23', '2026-03-02 11:49:23'),
(20, 'Lilibeth Tabajac test 11', 'Teacher II', 'Teacher V', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2018', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience Master Arts In Education Major in Filipino\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":6,\"ncoi_vs\":12,\"coi_o\":15,\"ncoi_o\":4,\"total_vs\":18,\"total_o\":19,\"ppst_counts\":{\"coi_vs\":6,\"coi_o\":15,\"ncoi_vs\":12,\"ncoi_o\":4,\"total_vs\":18,\"total_o\":19},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_vs_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_vs_16\":1,\"ppst_vs_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_vs_23\":1,\"ppst_vs_24\":1,\"ppst_vs_25\":1,\"ppst_vs_26\":1,\"ppst_vs_27\":1,\"ppst_vs_28\":1,\"ppst_vs_29\":1,\"ppst_vs_30\":1,\"ppst_vs_31\":1,\"ppst_vs_32\":1,\"ppst_vs_33\":1,\"ppst_vs_34\":1,\"ppst_vs_35\":1,\"ppst_vs_36\":1,\"ppst_vs_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-02 11:49:40', '2026-03-02 11:49:40'),
(21, 'Lilibeth Tabajac test 12', 'Teacher II', 'Teacher V', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2018', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience Master Arts In Education Major in Filipino\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":6,\"ncoi_vs\":12,\"coi_o\":15,\"ncoi_o\":4,\"total_vs\":18,\"total_o\":19,\"ppst_counts\":{\"coi_vs\":6,\"coi_o\":15,\"ncoi_vs\":12,\"ncoi_o\":4,\"total_vs\":18,\"total_o\":19},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_vs_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_vs_16\":1,\"ppst_vs_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_vs_23\":1,\"ppst_vs_24\":1,\"ppst_vs_25\":1,\"ppst_vs_26\":1,\"ppst_vs_27\":1,\"ppst_vs_28\":1,\"ppst_vs_29\":1,\"ppst_vs_30\":1,\"ppst_vs_31\":1,\"ppst_vs_32\":1,\"ppst_vs_33\":1,\"ppst_vs_34\":1,\"ppst_vs_35\":1,\"ppst_vs_36\":1,\"ppst_vs_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-02 11:49:44', '2026-03-02 11:49:44'),
(22, 'Lilibeth Tabajac test 13', 'Teacher II', 'Teacher V', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2018', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience Master Arts In Education Major in Filipino\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":6,\"ncoi_vs\":12,\"coi_o\":15,\"ncoi_o\":4,\"total_vs\":18,\"total_o\":19,\"ppst_counts\":{\"coi_vs\":6,\"coi_o\":15,\"ncoi_vs\":12,\"ncoi_o\":4,\"total_vs\":18,\"total_o\":19},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_vs_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_vs_16\":1,\"ppst_vs_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_vs_23\":1,\"ppst_vs_24\":1,\"ppst_vs_25\":1,\"ppst_vs_26\":1,\"ppst_vs_27\":1,\"ppst_vs_28\":1,\"ppst_vs_29\":1,\"ppst_vs_30\":1,\"ppst_vs_31\":1,\"ppst_vs_32\":1,\"ppst_vs_33\":1,\"ppst_vs_34\":1,\"ppst_vs_35\":1,\"ppst_vs_36\":1,\"ppst_vs_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-02 11:49:47', '2026-03-02 11:49:47'),
(23, 'Mikha Lim', 'Teacher III', 'Teacher VI', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2026', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"24 hours\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":8,\"ncoi_vs\":7,\"coi_o\":13,\"ncoi_o\":9,\"total_vs\":15,\"total_o\":22,\"ppst_counts\":{\"coi_vs\":8,\"coi_o\":13,\"ncoi_vs\":7,\"ncoi_o\":9,\"total_vs\":15,\"total_o\":22},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_vs_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_vs_7\":1,\"ppst_vs_8\":1,\"ppst_vs_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_vs_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_vs_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_vs_24\":1,\"ppst_vs_25\":1,\"ppst_vs_26\":1,\"ppst_vs_27\":1,\"ppst_vs_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_vs_34\":1,\"ppst_vs_35\":1,\"ppst_vs_36\":1,\"ppst_vs_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-03 01:32:27', '2026-03-03 01:32:27'),
(24, 'Mikha Lim Test', 'Teacher III', 'Teacher VI', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2026', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"24 hours\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":8,\"ncoi_vs\":5,\"coi_o\":13,\"ncoi_o\":11,\"total_vs\":13,\"total_o\":24,\"ppst_counts\":{\"coi_vs\":8,\"coi_o\":13,\"ncoi_vs\":5,\"ncoi_o\":11,\"total_vs\":13,\"total_o\":24},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_vs_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_vs_7\":1,\"ppst_vs_8\":1,\"ppst_vs_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_vs_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_vs_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_vs_24\":1,\"ppst_vs_25\":1,\"ppst_vs_26\":1,\"ppst_vs_27\":1,\"ppst_vs_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_vs_34\":1,\"ppst_o_35\":1,\"ppst_vs_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-03 03:06:04', '2026-03-03 03:06:04'),
(25, 'Mikha Lim', 'Teacher III', 'Teacher V', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"24 hours\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":0,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":0,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":0},\"ppst_selections\":{\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-04 08:22:09', '2026-03-04 08:22:09'),
(26, 'Aiah Arceta', 'Teacher III', 'Teacher V', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":0,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":0,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":0},\"ppst_selections\":{\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-05 00:11:44', '2026-03-05 00:11:44'),
(27, 'Aiah Arceta 2', 'Teacher III', 'Teacher V', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":0,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":0,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":0},\"ppst_selections\":{\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-05 00:12:27', '2026-03-05 00:12:27'),
(28, 'Aiah Arceta 3', 'Teacher III', 'Teacher V', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":0,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":0,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":0},\"ppst_selections\":{\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-05 00:13:40', '2026-03-05 00:13:40'),
(30, 'Aiah Arceta 4', 'Teacher IV', 'Teacher VI', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"24 hours\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":1,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":1,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-07 04:23:31', '2026-03-07 04:23:31'),
(31, 'Aiah Arceta 5', 'Teacher IV', 'Teacher VI', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"24 hours\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":1,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":1,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-07 04:27:17', '2026-03-07 04:27:17'),
(32, 'Aiah Arceta 6', 'Teacher IV', 'Teacher VI', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"24 hours\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":1,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":1,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-07 04:28:01', '2026-03-07 04:28:01'),
(33, 'Aiah Arceta 7', 'Teacher IV', 'Teacher VI', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"24 hours\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":1,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":1,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-07 04:28:39', '2026-03-07 04:28:39'),
(34, 'Aiah Arceta 8', 'Teacher IV', 'Teacher VI', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"24 hours\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":1,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":1,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-07 04:29:16', '2026-03-07 04:29:16'),
(35, 'Aiah Arceta 9', 'Teacher IV', 'Teacher VI', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"24 hours\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":1,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":1,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-07 04:34:13', '2026-03-07 04:34:13'),
(36, 'Aiah Arceta 10', 'Teacher IV', 'Teacher VI', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"24 hours\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":1,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":1,\"ncoi_vs\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":1},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-07 04:35:28', '2026-03-07 04:35:28'),
(38, 'Mikha Lim Test 2', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":0,\"ncoi_o\":0,\"total_vs\":0,\"total_o\":0,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-10 01:39:16', '2026-03-10 01:39:16'),
(39, 'Mikha Lim Test 3', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":22,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":38,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":22,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":38},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_o_38\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-10 01:41:20', '2026-03-10 01:41:20'),
(41, 'Mikha Lim Test for Teacher 4', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-11 05:15:05', '2026-03-11 05:15:05'),
(42, 'Mikha Lim Test for Teacher 5', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":22,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":38,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":22,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":38},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_o_38\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-11 05:19:37', '2026-03-11 05:19:37'),
(43, 'Mikha Lim Test for Teacher 6', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-11 05:20:50', '2026-03-11 05:20:50'),
(44, 'Mikha Lim Test for Teacher Geoffrey', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":21,\"ncoi_vs\":16,\"coi_o\":0,\"ncoi_o\":0,\"total_vs\":37,\"total_o\":0,\"ppst_counts\":{\"coi_vs\":21,\"coi_o\":0,\"ncoi_vs\":16,\"ncoi_o\":0,\"total_vs\":37,\"total_o\":0},\"ppst_selections\":{\"ppst_vs_1\":1,\"ppst_vs_2\":1,\"ppst_vs_3\":1,\"ppst_vs_4\":1,\"ppst_vs_5\":1,\"ppst_vs_6\":1,\"ppst_vs_7\":1,\"ppst_vs_8\":1,\"ppst_vs_9\":1,\"ppst_vs_10\":1,\"ppst_vs_11\":1,\"ppst_vs_12\":1,\"ppst_vs_13\":1,\"ppst_vs_14\":1,\"ppst_vs_15\":1,\"ppst_vs_16\":1,\"ppst_vs_17\":1,\"ppst_vs_18\":1,\"ppst_vs_19\":1,\"ppst_vs_20\":1,\"ppst_vs_21\":1,\"ppst_vs_22\":1,\"ppst_vs_23\":1,\"ppst_vs_24\":1,\"ppst_vs_25\":1,\"ppst_vs_26\":1,\"ppst_vs_27\":1,\"ppst_vs_28\":1,\"ppst_vs_29\":1,\"ppst_vs_30\":1,\"ppst_vs_31\":1,\"ppst_vs_32\":1,\"ppst_vs_33\":1,\"ppst_vs_34\":1,\"ppst_vs_35\":1,\"ppst_vs_36\":1,\"ppst_vs_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-11 05:24:13', '2026-03-11 05:24:13'),
(45, 'Mikha Lim Test for Teacher Geoffrey Janus', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-11 05:26:08', '2026-03-11 05:26:08'),
(46, 'Mikha Lim Test for Teacher Geoffrey Janus 2', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', 1, '2026-03-11 05:33:53', '2026-03-11 05:33:53'),
(47, 'Mikha Lim TEST 0001', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:12:52', '2026-03-17 00:12:52'),
(48, 'Mikha Lim TEST 0002', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:14:18', '2026-03-17 00:14:18'),
(49, 'Mikha Lim TEST 0004', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:19:48', '2026-03-17 00:19:48'),
(50, 'Mikha Lim TEST 0006', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":22,\"ncoi_o\":15,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":22,\"ncoi_vs\":0,\"ncoi_o\":15,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_o_38\":1,\"ppst_o_39\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:29:34', '2026-03-17 00:29:34'),
(51, 'Mikha Lim TEST 0007', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:32:12', '2026-03-17 00:32:12'),
(52, 'Mikha Lim TEST 0008', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:32:16', '2026-03-17 00:32:16');
INSERT INTO `performance_evaluations` (`id`, `name`, `current_position`, `position_applied`, `station`, `item_number`, `result`, `performance_payload`, `created_by_user_id`, `created_at`, `updated_at`) VALUES
(53, 'Mikha Lim TEST 0009', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":21,\"ncoi_vs\":16,\"coi_o\":0,\"ncoi_o\":0,\"total_vs\":37,\"total_o\":0,\"ppst_counts\":{\"coi_vs\":21,\"coi_o\":0,\"ncoi_vs\":16,\"ncoi_o\":0,\"total_vs\":37,\"total_o\":0},\"ppst_selections\":{\"ppst_vs_1\":1,\"ppst_vs_2\":1,\"ppst_vs_3\":1,\"ppst_vs_4\":1,\"ppst_vs_5\":1,\"ppst_vs_6\":1,\"ppst_vs_7\":1,\"ppst_vs_8\":1,\"ppst_vs_9\":1,\"ppst_vs_10\":1,\"ppst_vs_11\":1,\"ppst_vs_12\":1,\"ppst_vs_13\":1,\"ppst_vs_14\":1,\"ppst_vs_15\":1,\"ppst_vs_16\":1,\"ppst_vs_17\":1,\"ppst_vs_18\":1,\"ppst_vs_19\":1,\"ppst_vs_20\":1,\"ppst_vs_21\":1,\"ppst_vs_22\":1,\"ppst_vs_23\":1,\"ppst_vs_24\":1,\"ppst_vs_25\":1,\"ppst_vs_26\":1,\"ppst_vs_27\":1,\"ppst_vs_28\":1,\"ppst_vs_29\":1,\"ppst_vs_30\":1,\"ppst_vs_31\":1,\"ppst_vs_32\":1,\"ppst_vs_33\":1,\"ppst_vs_34\":1,\"ppst_vs_35\":1,\"ppst_vs_36\":1,\"ppst_vs_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:36:57', '2026-03-17 00:36:57'),
(54, 'Mikha Lim TEST 0010', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:47:40', '2026-03-17 00:47:40'),
(55, 'Mikha Lim TEST 0011', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:47:53', '2026-03-17 00:47:53'),
(56, 'Mikha Lim TEST 0012', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":22,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":38,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":22,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":38},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_o_38\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:48:31', '2026-03-17 00:48:31'),
(57, 'Mikha Lim TEST 0013', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":23,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":39,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":23,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":39},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_o_38\":1,\"ppst_o_39\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:48:38', '2026-03-17 00:48:38'),
(58, 'Mikha Lim TEST 0014', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'FAILED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:49:00', '2026-03-17 00:49:00'),
(59, 'Mikha Lim TEST 0015', 'Teacher III', 'Teacher IV', 'Mamatid Elementary School', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-17 00:50:09', '2026-03-17 00:50:09'),
(60, 'Mikha Lim Test 000000', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-18 00:23:58', '2026-03-18 00:23:58'),
(61, 'Mikha Lim Test for Teacher III to Teacher IV', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-18 00:50:28', '2026-03-18 00:50:28'),
(62, 'Mikha Lim 123456789', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-18 03:17:11', '2026-03-18 03:17:11'),
(63, 'Mikha Lim 123456789 Test', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-18 03:25:19', '2026-03-18 03:25:19'),
(64, 'Mikha Lim 123456789 Test 2', 'Teacher III', 'Teacher IV', 'Southville 1 Integrated National Highschool', 'OSEC-DECSB-TCH3-270758-2019', 'PASSED', '{\"form_type\":\"form1\",\"sg_salary\":\"13 / 416,796\",\"level\":\"Junior High School\",\"app_education\":\"At least 3 years teaching experience\",\"app_training\":\"School-Based Training of Teachers and School Leaders in the Implementation of MATATAG Curriculum for Filipino\",\"app_experience\":\"At least 3 years teaching experience\",\"app_eligibility\":\"Teacher\",\"app_competency\":\"\",\"qs_remark_education\":\"\",\"qs_remark_training\":\"\",\"qs_remark_experience\":\"\",\"qs_remark_eligibility\":\"\",\"qs_remark_competency\":\"\",\"coi_vs\":0,\"ncoi_vs\":0,\"coi_o\":21,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37,\"ppst_counts\":{\"coi_vs\":0,\"coi_o\":21,\"ncoi_vs\":0,\"ncoi_o\":16,\"total_vs\":0,\"total_o\":37},\"ppst_selections\":{\"ppst_o_1\":1,\"ppst_o_2\":1,\"ppst_o_3\":1,\"ppst_o_4\":1,\"ppst_o_5\":1,\"ppst_o_6\":1,\"ppst_o_7\":1,\"ppst_o_8\":1,\"ppst_o_9\":1,\"ppst_o_10\":1,\"ppst_o_11\":1,\"ppst_o_12\":1,\"ppst_o_13\":1,\"ppst_o_14\":1,\"ppst_o_15\":1,\"ppst_o_16\":1,\"ppst_o_17\":1,\"ppst_o_18\":1,\"ppst_o_19\":1,\"ppst_o_20\":1,\"ppst_o_21\":1,\"ppst_o_22\":1,\"ppst_o_23\":1,\"ppst_o_24\":1,\"ppst_o_25\":1,\"ppst_o_26\":1,\"ppst_o_27\":1,\"ppst_o_28\":1,\"ppst_o_29\":1,\"ppst_o_30\":1,\"ppst_o_31\":1,\"ppst_o_32\":1,\"ppst_o_33\":1,\"ppst_o_34\":1,\"ppst_o_35\":1,\"ppst_o_36\":1,\"ppst_o_37\":1,\"ppst_total_o\":1,\"ppst_total_vs\":1},\"action_date\":\"\",\"region_date\":\"\"}', NULL, '2026-03-18 03:29:29', '2026-03-18 03:29:29');

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
(1060, 'Administrative Officer II', 'NON-TEACHING LEVEL II', '11', NULL, 'Non-Teaching - Administrative Officer Level II', '2026-02-05 07:08:08', '2026-02-05 07:08:08'),
(1061, 'Teacher II', 'HIGHER TEACHING', NULL, NULL, 'Position created from evaluation form', '2026-02-08 00:55:47', '2026-02-08 00:55:47');

-- --------------------------------------------------------

--
-- Table structure for table `reclassification_position_baselines`
--

CREATE TABLE `reclassification_position_baselines` (
  `id` int(11) NOT NULL,
  `position_name` varchar(100) NOT NULL,
  `form_type` enum('form1','form2') NOT NULL,
  `education` text NOT NULL,
  `training` text NOT NULL,
  `experience` text NOT NULL,
  `eligibility` text NOT NULL,
  `competency` text DEFAULT NULL,
  `coi_vs` int(11) NOT NULL DEFAULT 0,
  `ncoi_vs` int(11) NOT NULL DEFAULT 0,
  `coi_o` int(11) NOT NULL DEFAULT 0,
  `ncoi_o` int(11) NOT NULL DEFAULT 0,
  `salary_grade` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reclassification_position_baselines`
--

INSERT INTO `reclassification_position_baselines` (`id`, `position_name`, `form_type`, `education`, `training`, `experience`, `eligibility`, `competency`, `coi_vs`, `ncoi_vs`, `coi_o`, `ncoi_o`, `salary_grade`, `created_at`, `updated_at`) VALUES
(1, 'Teacher II', 'form1', 'Bachelor\'s degree in Education; or Bachelor\'s degree in relevant subject or learning area with at least 18 professional units in Education', '8 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization acquired within the last 5 years', '1 year teaching experience', 'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).', '', 6, 4, 0, 0, 12, '2026-03-02 11:18:18', '2026-03-02 11:18:18'),
(2, 'Teacher III', 'form1', 'Bachelor\'s degree in Education; or Bachelor\'s degree in relevant subject or learning area with at least 18 professional units in Education', '16 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization acquired within the last 5 years', '2 years teaching experience', 'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).', '', 12, 8, 0, 0, 13, '2026-03-02 11:18:18', '2026-03-02 11:18:18'),
(3, 'Teacher IV', 'form1', 'Bachelor\'s degree in Education; or Bachelor\'s degree in relevant subject or learning area with at least 18 professional units in Education', '16 hours of training in Curriculum, Pedagogy, Subject Specialization within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage II', '3 years teaching experience', 'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).', '', 21, 16, 0, 0, 14, '2026-03-02 11:18:18', '2026-03-02 11:18:18'),
(4, 'Teacher V', 'form1', 'Bachelor\'s degree in Education; or Bachelor\'s degree in relevant subject or learning area with at least 18 professional units in Education', '24 hours of training in Curriculum, Pedagogy, Subject Specialization within the last 5 years; or completion of NEAP-requisite professional development program', '3 years teaching experience', 'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).', '', 0, 0, 6, 4, 15, '2026-03-02 11:18:18', '2026-03-02 11:18:18'),
(5, 'Teacher VI', 'form1', 'Bachelor\'s degree in Education; or Bachelor\'s degree in relevant subject or learning area with at least 18 professional units in Education', '24 hours of training in Curriculum, Pedagogy, Subject Specialization, Instructional Supervision within the last 5 years; or completion of NEAP-requisite professional development program', '4 years teaching experience', 'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).', '', 0, 4, 12, 4, 16, '2026-03-02 11:18:18', '2026-03-02 11:18:18'),
(6, 'Teacher VII', 'form1', 'Bachelor\'s degree in Education; or Bachelor\'s degree in relevant subject or learning area with at least 18 professional units in Education', '32 hours of training in Curriculum, Pedagogy, Subject Specialization, Instructional Supervision within the last 5 years; or completion of NEAP-requisite professional development program', '4 years teaching experience', 'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).', '', 0, 6, 18, 6, 17, '2026-03-02 11:18:18', '2026-03-02 11:18:18'),
(7, 'Master Teacher I', 'form1', 'Master\'s degree in Education, or Educational Leadership, or Educational Management, or relevant subject or learning area', '24 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization and 8 hours of training in Instructional Supervision acquired within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage III (Highly Proficient Teacher)', '5 years teaching experience', 'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).', '', 0, 8, 21, 8, 18, '2026-03-02 11:18:18', '2026-03-02 11:18:18'),
(8, 'Master Teacher II', 'form2', 'Master\'s degree in Education, or Educational Leadership, or Educational Management, or relevant subject or learning area', '24 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization and 8 hours of training in Instructional Supervision acquired within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage III (Highly Proficient Teacher)', '5 years teaching experience and 1 year relevant experience in instructional supervision and technical assistance to teachers', 'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).', '', 0, 5, 10, 5, 19, '2026-03-02 11:18:18', '2026-03-02 11:18:18'),
(9, 'Master Teacher III', 'form2', 'Master\'s degree in Education, or Educational Leadership, or Educational Management, or relevant subject or learning area', '24 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization and 8 hours of training in Instructional Supervision acquired within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage IV (Distinguished Teacher)', '5 years teaching experience and 2 years relevant experience in instructional supervision and technical assistance to teachers', 'Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).', '', 0, 8, 21, 8, 20, '2026-03-02 11:18:18', '2026-03-02 11:18:18');

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
(1, 'admin', 'admin@deped.gov.ph', '$2y$10$CzFBTeSBGdgXWRzXV5thbOjFVp9ygJBQS7THdXFaNDxhwZiD7P2.G', 'System Administrator', 'admin', 'active', '2026-03-23 23:29:12', '2026-02-04 05:37:18', '2026-03-23 23:29:12');

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
-- Indexes for table `performance_evaluations`
--
ALTER TABLE `performance_evaluations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_perf_name_item` (`name`,`item_number`),
  ADD KEY `idx_perf_name` (`name`),
  ADD KEY `idx_perf_created_by` (`created_by_user_id`),
  ADD KEY `idx_perf_result` (`result`),
  ADD KEY `idx_perf_created_at` (`created_at`),
  ADD KEY `idx_perf_guest_records` (`created_by_user_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_position_group` (`position_group`);

--
-- Indexes for table `reclassification_position_baselines`
--
ALTER TABLE `reclassification_position_baselines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_position_name` (`position_name`),
  ADD KEY `idx_form_type` (`form_type`),
  ADD KEY `idx_salary_grade` (`salary_grade`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `applicant_qualifications`
--
ALTER TABLE `applicant_qualifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `archived_applicants_audit`
--
ALTER TABLE `archived_applicants_audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `baseline_qualifications`
--
ALTER TABLE `baseline_qualifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comparative_assessment_results`
--
ALTER TABLE `comparative_assessment_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `drafts`
--
ALTER TABLE `drafts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `evaluation_details`
--
ALTER TABLE `evaluation_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=537;

--
-- AUTO_INCREMENT for table `login_audit`
--
ALTER TABLE `login_audit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `performance_evaluations`
--
ALTER TABLE `performance_evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1062;

--
-- AUTO_INCREMENT for table `reclassification_position_baselines`
--
ALTER TABLE `reclassification_position_baselines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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

--
-- Constraints for table `performance_evaluations`
--
ALTER TABLE `performance_evaluations`
  ADD CONSTRAINT `fk_performance_evaluations_user` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
