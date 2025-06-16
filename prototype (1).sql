-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 01:16 AM
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
-- Database: `prototype`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `grade_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `grade_id`, `created_at`, `updated_at`) VALUES
(1, 'Grade 10 A', 1, NULL, NULL),
(2, 'Grade 11 A', 2, NULL, NULL),
(3, 'Grade 11 B', 2, NULL, NULL),
(4, 'Grade 10E', 1, '2025-05-30 21:50:04', '2025-05-30 21:50:04');

-- --------------------------------------------------------

--
-- Table structure for table `class_subject`
--

CREATE TABLE `class_subject` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_subject`
--

INSERT INTO `class_subject` (`id`, `class_id`, `subject_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, NULL),
(2, 1, 2, NULL, NULL, NULL),
(3, 1, 3, NULL, NULL, NULL),
(4, 1, 4, NULL, NULL, NULL),
(5, 1, 5, NULL, NULL, NULL),
(6, 2, 2, NULL, NULL, NULL),
(7, 2, 3, NULL, NULL, NULL),
(8, 2, 4, NULL, NULL, NULL),
(9, 2, 1, NULL, NULL, NULL),
(10, 2, 5, NULL, NULL, NULL),
(11, 3, 3, NULL, NULL, NULL),
(12, 3, 4, NULL, NULL, NULL),
(13, 3, 5, NULL, NULL, NULL),
(14, 3, 2, NULL, NULL, NULL),
(15, 3, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `hod_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `hod_id`, `created_at`, `updated_at`) VALUES
(1, 'Mathematics', 53, '2025-05-11 01:27:49', '2025-05-11 01:28:24'),
(2, 'Sciences', 55, '2025-05-11 01:27:50', '2025-05-11 01:28:24'),
(3, 'Languages', 57, '2025-05-11 01:27:52', '2025-05-11 01:28:25'),
(4, 'Humanities', 60, '2025-05-11 01:27:54', '2025-05-11 01:28:27');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `evaluator_teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED DEFAULT NULL,
  `evaluation_type` enum('student','peer','self') NOT NULL DEFAULT 'student',
  `knowledge_rating` int(11) DEFAULT NULL,
  `teaching_skill` int(11) DEFAULT NULL,
  `communication` int(11) DEFAULT NULL,
  `punctuality` int(11) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `hod_comments` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','reviewed','approved','rejected','closed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `evaluation_cycle_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `student_id`, `evaluator_teacher_id`, `teacher_id`, `subject_id`, `class_id`, `evaluation_type`, `knowledge_rating`, `teaching_skill`, `communication`, `punctuality`, `score`, `comments`, `hod_comments`, `user_id`, `completed`, `status`, `created_at`, `updated_at`, `evaluation_cycle_id`) VALUES
(9, 3, NULL, 2, 2, 2, 'student', 2, 4, 3, 3, NULL, 'gfg', NULL, 5, 1, 'closed', '2024-09-11 01:59:26', '2025-05-26 21:27:12', 1),
(10, 3, NULL, 3, 3, 2, 'student', 5, 4, 4, 3, NULL, 'j', NULL, 5, 1, 'closed', '2024-06-11 01:59:26', '2025-05-26 21:27:12', 1),
(11, 3, NULL, 4, 4, 2, 'student', 3, 3, 2, 3, NULL, NULL, NULL, 5, 1, 'closed', '2024-09-11 01:59:26', '2025-05-26 21:27:12', 1),
(12, 3, NULL, 5, 5, 2, 'student', 2, 4, 3, 3, NULL, NULL, NULL, 5, 1, 'closed', '2024-08-11 01:59:26', '2025-05-26 21:27:12', 1),
(235, NULL, 3, 3, 3, NULL, 'self', 3, 5, 3, 5, 35.00, '{\"performance_planning\":{\"lessons_delivered\":{\"target\":500,\"actual_performance\":\"400\",\"score\":\"9\",\"evidence\":\"po\"},\"exercises_tests\":{\"target\":700,\"actual_performance\":\"500\",\"score\":\"8\",\"evidence\":\"po\"},\"records_maintained\":{\"target\":8,\"actual_performance\":\"7\",\"score\":\"9\",\"evidence\":\"po\"},\"sporting_sessions\":{\"target\":20,\"actual_performance\":\"19\",\"score\":\"9\",\"evidence\":\"po\"}},\"personal_attributes\":{\"technical_skills\":\"excellent\",\"knowledge_of_standards\":\"excellent\",\"creativity_innovation\":\"excellent\",\"ethics\":\"excellent\",\"teamwork\":\"excellent\",\"communication_skills\":\"excellent\",\"interpersonal_relations\":\"excellent\",\"judgement_decision_making\":\"excellent\",\"leadership_management\":\"excellent\"}}', NULL, 58, 1, 'closed', '2025-05-26 12:15:13', '2025-05-26 21:27:12', 1),
(236, NULL, 3, 1, 1, NULL, 'peer', 5, 5, 5, 4, NULL, 'wello wello', NULL, 58, 1, 'closed', '2025-05-26 12:16:02', '2025-05-26 21:27:12', 1),
(237, 3, NULL, 2, 2, 1, 'student', 5, 5, 5, 5, NULL, NULL, NULL, 3, 1, 'approved', '2025-05-26 21:27:12', '2025-05-26 21:32:36', 1),
(238, 3, NULL, 3, 3, 1, 'student', 4, 3, 2, 3, NULL, 'Hesoyam', NULL, 3, 1, 'approved', '2025-05-26 21:27:12', '2025-05-28 11:59:08', 1),
(239, 3, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(240, 3, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(241, 4, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(242, 4, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(243, 4, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(244, 4, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(245, 5, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(246, 5, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(247, 5, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(248, 5, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(249, 6, NULL, 1, 1, 1, 'student', 3, 4, 3, 4, NULL, NULL, NULL, 6, 1, 'approved', '2025-05-26 21:27:12', '2025-05-29 17:14:14', 1),
(250, 6, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(251, 6, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(252, 6, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(253, 7, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(254, 7, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(255, 7, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(256, 7, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(257, 8, NULL, 1, 1, 1, 'student', 3, 4, 5, 5, NULL, NULL, NULL, 8, 1, 'approved', '2025-05-26 21:27:12', '2025-05-29 09:03:44', 1),
(258, 8, NULL, 2, 2, 1, 'student', 4, 5, 2, 5, NULL, NULL, NULL, 8, 1, 'approved', '2025-05-26 21:27:12', '2025-05-29 09:04:02', 1),
(259, 8, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(260, 8, NULL, 5, 5, 1, 'student', 4, 3, 4, 4, NULL, NULL, NULL, 8, 1, 'approved', '2025-05-26 21:27:12', '2025-05-29 09:06:40', 1),
(261, 9, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(262, 9, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(263, 9, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(264, 9, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(265, 10, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(266, 10, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(267, 10, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(268, 10, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(269, 11, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(270, 11, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(271, 11, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(272, 11, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(273, 12, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(274, 12, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(275, 12, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(276, 12, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(277, 13, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(278, 13, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(279, 13, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(280, 13, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(281, 14, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(282, 14, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(283, 14, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(284, 14, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(285, 15, NULL, 1, 1, 1, 'student', 5, 5, 5, 5, NULL, NULL, NULL, 15, 1, 'approved', '2025-05-26 21:27:12', '2025-05-29 09:16:05', 1),
(286, 15, NULL, 2, 2, 1, 'student', 3, 5, 2, 5, NULL, NULL, NULL, 15, 1, 'approved', '2025-05-26 21:27:12', '2025-05-29 09:16:25', 1),
(287, 15, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(288, 15, NULL, 5, 5, 1, 'student', 5, 3, 4, 4, NULL, NULL, NULL, 15, 1, 'approved', '2025-05-26 21:27:12', '2025-05-29 09:16:48', 1),
(289, 16, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(290, 16, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(291, 16, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(292, 16, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 16, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(293, 17, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(294, 17, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(295, 17, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(296, 17, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(297, 18, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(298, 18, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(299, 18, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(300, 18, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(301, 19, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(302, 19, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(303, 19, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(304, 19, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(305, 20, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(306, 20, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(307, 20, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(308, 20, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(309, 21, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 21, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(310, 21, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 21, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(311, 21, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 21, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(312, 21, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 21, 0, 'pending', '2025-05-26 21:27:12', '2025-05-26 21:27:12', 1),
(313, 22, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(314, 22, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(315, 22, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(316, 22, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 22, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(317, 23, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 23, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(318, 23, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 23, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(319, 23, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 23, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(320, 23, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 23, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(321, 24, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 24, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(322, 24, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 24, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(323, 24, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 24, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(324, 24, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 24, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(325, 25, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(326, 25, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(327, 25, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(328, 25, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 25, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(329, 26, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(330, 26, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(331, 26, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(332, 26, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 26, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(333, 27, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 27, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(334, 27, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 27, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(335, 27, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 27, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(336, 27, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 27, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(337, 28, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 28, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(338, 28, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 28, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(339, 28, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 28, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(340, 28, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 28, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(341, 29, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 29, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(342, 29, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 29, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(343, 29, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 29, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(344, 29, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 29, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(345, 30, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(346, 30, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(347, 30, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(348, 30, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 30, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(349, 31, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 31, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(350, 31, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 31, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(351, 31, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 31, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(352, 31, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 31, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(353, 32, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 32, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(354, 32, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 32, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(355, 32, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 32, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(356, 32, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 32, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(357, 33, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 33, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(358, 33, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 33, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(359, 33, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 33, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(360, 33, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 33, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(361, 34, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(362, 34, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(363, 34, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(364, 34, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(365, 35, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(366, 35, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(367, 35, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(368, 35, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(369, 36, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 36, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(370, 36, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 36, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(371, 36, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 36, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(372, 36, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 36, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(373, 37, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(374, 37, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(375, 37, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(376, 37, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(377, 38, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 38, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(378, 38, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 38, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(379, 38, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 38, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(380, 38, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 38, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(381, 39, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 39, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(382, 39, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 39, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(383, 39, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 39, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(384, 39, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 39, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(385, 40, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(386, 40, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(387, 40, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(388, 40, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(389, 41, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 41, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(390, 41, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 41, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(391, 41, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 41, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(392, 41, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 41, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(393, 42, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(394, 42, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(395, 42, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(396, 42, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(397, 43, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 43, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(398, 43, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 43, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(399, 43, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 43, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(400, 43, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 43, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(401, 44, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 44, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(402, 44, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 44, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(403, 44, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 44, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(404, 44, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 44, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(405, 45, NULL, 1, 1, 1, 'student', 5, 5, 4, 3, NULL, NULL, NULL, 45, 1, 'approved', '2025-05-26 21:27:13', '2025-05-29 09:18:27', 1),
(406, 45, NULL, 3, 3, 1, 'student', 4, 4, 3, 3, NULL, NULL, NULL, 45, 1, 'approved', '2025-05-26 21:27:13', '2025-05-29 09:19:10', 1),
(407, 45, NULL, 4, 4, 1, 'student', 4, 4, 1, 3, NULL, NULL, NULL, 45, 1, 'approved', '2025-05-26 21:27:13', '2025-05-29 09:18:43', 1),
(408, 45, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 45, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(409, 46, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 46, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(410, 46, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 46, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(411, 46, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 46, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(412, 46, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 46, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(413, 47, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 47, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(414, 47, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 47, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(415, 47, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 47, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(416, 47, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 47, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(417, 48, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 48, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(418, 48, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 48, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(419, 48, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 48, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(420, 48, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 48, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(421, 49, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 49, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(422, 49, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 49, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(423, 49, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 49, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(424, 49, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 49, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(425, 50, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(426, 50, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(427, 50, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(428, 50, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 50, 0, 'pending', '2025-05-26 21:27:13', '2025-05-26 21:27:13', 1),
(429, NULL, 1, 2, 2, NULL, 'peer', 5, 4, 5, 2, NULL, NULL, NULL, 54, 1, 'approved', '2025-05-26 21:51:43', '2025-05-26 21:51:43', 1),
(430, NULL, 1, 1, 1, NULL, 'self', 4, 4, 5, 5, 34.00, '{\"performance_planning\":{\"lessons_delivered\":{\"target\":500,\"actual_performance\":\"450\",\"score\":\"8\",\"evidence\":\"po\"},\"exercises_tests\":{\"target\":700,\"actual_performance\":\"600\",\"score\":\"8\",\"evidence\":\"po\"},\"records_maintained\":{\"target\":8,\"actual_performance\":\"7\",\"score\":\"9\",\"evidence\":\"po\"},\"sporting_sessions\":{\"target\":20,\"actual_performance\":\"19\",\"score\":\"9\",\"evidence\":\"po\"}},\"personal_attributes\":{\"technical_skills\":\"excellent\",\"knowledge_of_standards\":\"excellent\",\"creativity_innovation\":\"satisfactory\",\"ethics\":\"excellent\",\"teamwork\":\"requires_improvement\",\"communication_skills\":\"excellent\",\"interpersonal_relations\":\"very_good\",\"judgement_decision_making\":\"excellent\",\"leadership_management\":\"excellent\"}}', NULL, 54, 1, 'approved', '2025-05-26 21:54:48', '2025-05-29 09:11:48', 1),
(431, NULL, 1, 4, 4, NULL, 'peer', 4, 4, 3, 3, NULL, NULL, NULL, 54, 1, 'approved', '2025-05-29 09:09:13', '2025-05-29 09:09:13', 1),
(432, NULL, 5, 5, 5, NULL, 'self', 5, 3, 4, 3, 30.00, '{\"performance_planning\":{\"lessons_delivered\":{\"target\":500,\"actual_performance\":\"500\",\"score\":\"10\",\"evidence\":\"fsa\"},\"exercises_tests\":{\"target\":700,\"actual_performance\":\"560\",\"score\":\"8\",\"evidence\":\"fas\"},\"records_maintained\":{\"target\":8,\"actual_performance\":\"6\",\"score\":\"4\",\"evidence\":\"fsa\"},\"sporting_sessions\":{\"target\":20,\"actual_performance\":\"7\",\"score\":\"8\",\"evidence\":\"faf\"}},\"personal_attributes\":{\"technical_skills\":\"excellent\",\"knowledge_of_standards\":\"excellent\",\"creativity_innovation\":\"excellent\",\"ethics\":\"excellent\",\"teamwork\":\"satisfactory\",\"communication_skills\":\"excellent\",\"interpersonal_relations\":\"excellent\",\"judgement_decision_making\":\"requires_improvement\",\"leadership_management\":\"excellent\"}}', 'Youve done well', 61, 1, 'approved', '2025-05-29 09:25:23', '2025-05-29 09:33:06', 1),
(433, NULL, 5, 2, 2, NULL, 'peer', 3, 4, 5, 3, NULL, NULL, NULL, 61, 1, 'approved', '2025-05-29 09:25:55', '2025-05-29 09:25:55', 1),
(434, NULL, 5, 1, 1, NULL, 'peer', 5, 3, 3, 4, NULL, NULL, NULL, 61, 1, 'approved', '2025-05-29 09:26:16', '2025-05-29 09:26:16', 1),
(435, NULL, 5, 4, 4, NULL, 'peer', 4, 4, 3, 4, NULL, NULL, NULL, 61, 1, 'approved', '2025-05-29 09:26:39', '2025-05-29 09:26:39', 1),
(436, NULL, 1, 5, 5, NULL, 'peer', 4, 4, 4, 5, NULL, NULL, NULL, 54, 1, 'approved', '2025-05-29 09:35:07', '2025-05-29 09:35:07', 1),
(437, NULL, 3, 3, 3, NULL, 'self', 5, 5, 5, 4, 16.00, '{\"performance_planning\":{\"lessons_delivered\":{\"target\":500,\"actual_performance\":\"5\",\"score\":\"3\",\"evidence\":\"da\"},\"exercises_tests\":{\"target\":700,\"actual_performance\":\"3\",\"score\":\"7\",\"evidence\":\"da\"},\"records_maintained\":{\"target\":8,\"actual_performance\":\"3\",\"score\":\"3\",\"evidence\":\"da\"},\"sporting_sessions\":{\"target\":20,\"actual_performance\":\"5\",\"score\":\"3\",\"evidence\":\"fa\"}},\"personal_attributes\":{\"technical_skills\":\"excellent\",\"knowledge_of_standards\":\"excellent\",\"creativity_innovation\":\"excellent\",\"ethics\":\"excellent\",\"teamwork\":\"satisfactory\",\"communication_skills\":\"excellent\",\"interpersonal_relations\":\"excellent\",\"judgement_decision_making\":\"requires_improvement\",\"leadership_management\":\"excellent\"}}', 'incomplete records', 58, 1, 'rejected', '2025-05-29 10:28:35', '2025-05-29 17:18:36', 1),
(438, 52, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 69, 0, 'pending', '2025-05-30 21:29:43', '2025-05-30 21:29:43', 12),
(439, 52, NULL, 2, 2, 1, 'student', 3, 5, 5, 3, NULL, NULL, NULL, 69, 1, 'approved', '2025-05-30 21:29:43', '2025-05-30 21:46:45', 12),
(440, 52, NULL, 3, 3, 1, 'student', 5, 4, 2, 1, NULL, NULL, NULL, 69, 1, 'approved', '2025-05-30 21:29:43', '2025-06-01 11:02:27', 12),
(441, 52, NULL, 4, 4, 1, 'student', 4, 3, 3, 3, NULL, NULL, NULL, 69, 1, 'approved', '2025-05-30 21:29:43', '2025-06-01 17:09:02', 12),
(442, NULL, 1, 1, 1, NULL, 'self', 5, 3, 3, 4, 35.00, '{\"performance_planning\":{\"lessons_delivered\":{\"target\":500,\"actual_performance\":\"500\",\"score\":\"8\",\"evidence\":\"p\"},\"exercises_tests\":{\"target\":700,\"actual_performance\":\"650\",\"score\":\"10\",\"evidence\":\"p\"},\"records_maintained\":{\"target\":8,\"actual_performance\":\"7\",\"score\":\"9\",\"evidence\":\"p\"},\"sporting_sessions\":{\"target\":20,\"actual_performance\":\"17\",\"score\":\"8\",\"evidence\":\"p\"}},\"personal_attributes\":{\"technical_skills\":\"excellent\",\"knowledge_of_standards\":\"requires_improvement\",\"creativity_innovation\":\"excellent\",\"ethics\":\"excellent\",\"teamwork\":\"very_good\",\"communication_skills\":\"very_good\",\"interpersonal_relations\":\"excellent\",\"judgement_decision_making\":\"excellent\",\"leadership_management\":\"excellent\"}}', NULL, 54, 1, 'pending', '2025-06-01 10:54:34', '2025-06-01 10:54:34', 12),
(443, NULL, 1, 8, 2, NULL, 'peer', 3, 3, 4, 4, NULL, 'jhgkjshsj', NULL, 54, 1, 'approved', '2025-06-01 10:55:06', '2025-06-01 10:55:06', 12),
(444, NULL, 3, 1, 1, NULL, 'peer', 5, 3, 1, 2, NULL, 'keep up bro', NULL, 58, 1, 'approved', '2025-06-01 10:56:47', '2025-06-01 10:56:47', 12),
(445, 53, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 72, 0, 'pending', '2025-06-01 11:17:31', '2025-06-01 11:17:31', 12),
(446, 53, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 72, 0, 'pending', '2025-06-01 11:17:31', '2025-06-01 11:17:31', 12),
(447, 53, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 72, 0, 'pending', '2025-06-01 11:17:31', '2025-06-01 11:17:31', 12),
(448, 53, NULL, 5, 5, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 72, 0, 'pending', '2025-06-01 11:17:31', '2025-06-01 11:17:31', 12),
(449, 54, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 73, 0, 'pending', '2025-06-01 11:18:35', '2025-06-01 11:18:35', 12),
(450, 54, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 73, 0, 'pending', '2025-06-01 11:18:35', '2025-06-01 11:18:35', 12),
(451, 54, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 73, 0, 'pending', '2025-06-01 11:18:35', '2025-06-01 11:18:35', 12),
(452, 54, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 73, 0, 'pending', '2025-06-01 11:18:35', '2025-06-01 11:18:35', 12),
(453, 55, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 74, 0, 'pending', '2025-06-01 11:19:43', '2025-06-01 11:19:43', 12),
(454, 55, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 74, 0, 'pending', '2025-06-01 11:19:43', '2025-06-01 11:19:43', 12),
(455, 55, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 74, 0, 'pending', '2025-06-01 11:19:43', '2025-06-01 11:19:43', 12),
(456, 55, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 74, 0, 'pending', '2025-06-01 11:19:43', '2025-06-01 11:19:43', 12),
(457, 56, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 75, 0, 'pending', '2025-06-01 11:22:19', '2025-06-01 11:22:19', 12),
(458, 56, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 75, 0, 'pending', '2025-06-01 11:22:19', '2025-06-01 11:22:19', 12),
(459, 56, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 75, 0, 'pending', '2025-06-01 11:22:19', '2025-06-01 11:22:19', 12),
(460, 56, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 75, 0, 'pending', '2025-06-01 11:22:19', '2025-06-01 11:22:19', 12),
(461, 57, NULL, 1, 1, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 76, 0, 'pending', '2025-06-01 16:48:18', '2025-06-01 16:48:18', 12),
(462, 57, NULL, 2, 2, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 76, 0, 'pending', '2025-06-01 16:48:18', '2025-06-01 16:48:18', 12),
(463, 57, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 76, 0, 'pending', '2025-06-01 16:48:18', '2025-06-01 16:48:18', 12),
(464, 57, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 76, 0, 'pending', '2025-06-01 16:48:18', '2025-06-01 16:48:18', 12),
(465, NULL, 4, 9, 1, NULL, 'peer', 4, 3, 3, 3, NULL, NULL, NULL, 59, 1, 'approved', '2025-06-01 17:12:00', '2025-06-01 17:12:00', 12),
(466, 58, NULL, 1, 1, 1, 'student', 4, 2, 3, 4, NULL, 'kjfakjga', NULL, 77, 1, 'approved', '2025-06-02 07:58:27', '2025-06-03 07:28:19', 12),
(467, 58, NULL, 2, 2, 1, 'student', 4, 4, 3, 4, NULL, 'kdkjsa', NULL, 77, 1, 'approved', '2025-06-02 07:58:27', '2025-06-02 08:00:07', 12),
(468, 58, NULL, 3, 3, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 77, 0, 'pending', '2025-06-02 07:58:27', '2025-06-02 07:58:27', 12),
(469, 58, NULL, 4, 4, 1, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 77, 0, 'pending', '2025-06-02 07:58:27', '2025-06-02 07:58:27', 12),
(470, NULL, 4, 4, 4, NULL, 'self', 5, 3, 3, 1, 18.00, '{\"performance_planning\":{\"lessons_delivered\":{\"target\":500,\"actual_performance\":\"490\",\"score\":\"4\",\"evidence\":\"fad\"},\"exercises_tests\":{\"target\":700,\"actual_performance\":\"650\",\"score\":\"4\",\"evidence\":\"faa\"},\"records_maintained\":{\"target\":8,\"actual_performance\":\"7\",\"score\":\"4\",\"evidence\":\"faf\"},\"sporting_sessions\":{\"target\":20,\"actual_performance\":\"13\",\"score\":\"6\",\"evidence\":\"fasa\"}},\"personal_attributes\":{\"technical_skills\":\"excellent\",\"knowledge_of_standards\":\"excellent\",\"creativity_innovation\":\"excellent\",\"ethics\":\"excellent\",\"teamwork\":\"excellent\",\"communication_skills\":\"satisfactory\",\"interpersonal_relations\":\"excellent\",\"judgement_decision_making\":\"requires_improvement\",\"leadership_management\":\"excellent\"}}', NULL, 59, 1, 'approved', '2025-06-03 07:34:16', '2025-06-03 07:37:42', 12);

--
-- Triggers `evaluations`
--
DELIMITER $$
CREATE TRIGGER `prevent_zero_ratings` BEFORE INSERT ON `evaluations` FOR EACH ROW BEGIN
    IF NEW.knowledge_rating = 0 OR NEW.teaching_skill = 0 OR 
       NEW.communication = 0 OR NEW.punctuality = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Ratings cannot be zero';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_cycles`
--

CREATE TABLE `evaluation_cycles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluation_cycles`
--

INSERT INTO `evaluation_cycles` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '2025-Q1', 0, '2025-05-24 07:41:21', '2025-05-25 21:53:07'),
(2, '2025-Q2', 0, '2025-05-25 21:53:07', '2025-05-26 21:27:12'),
(12, '2025-Q0', 1, '2025-05-26 21:27:12', '2025-05-26 21:27:12');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Grade 10', NULL, NULL),
(2, 'Grade 11', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hods`
--

CREATE TABLE `hods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hods`
--

INSERT INTO `hods` (`id`, `user_id`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 53, 1, '2025-05-19 21:33:27', '2025-05-19 21:33:27'),
(2, 55, 2, '2025-05-19 21:33:53', '2025-05-19 21:33:53'),
(3, 57, 3, '2025-05-19 21:34:08', '2025-05-19 21:34:08'),
(4, 60, 4, '2025-05-19 21:34:22', '2025-05-19 21:34:22');

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
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2025_03_10_000000_create_departments_table', 1),
(3, '2025_03_10_080704_create_subjects_table', 1),
(4, '2025_03_11_074136_create_grades_table', 1),
(5, '2025_03_12_074055_create_classes_table', 1),
(6, '2025_03_13_080000_create_users_table', 1),
(7, '2025_03_14_080000_create_teachers_table', 1),
(8, '2025_03_15_080200_create_students_table', 1),
(9, '2025_03_16_085042_create_teacher_class_table', 1),
(10, '2025_03_17_085140_create_student_class_table', 1),
(11, '2025_03_18_085300_create_evaluations_table', 1),
(12, '2025_03_20_071005_add__subject__id__to__users__table', 1),
(13, '2025_03_21_000000_create_cache_table', 1),
(14, '2025_04_18_220204_add_department_id_to_users_table', 1),
(15, '2025_04_19_235048_create_departments_table', 1),
(16, '2025_04_20_141048_add_hod_id_to_departments_table', 1),
(17, '2025_04_20_142812_add_name_to_users_table', 1),
(18, '2025_04_24_194652_add_name_to_grades_table', 1),
(19, '2025_04_24_203543_add_department_id_to_subjects_table', 1),
(20, '2025_04_24_204145_add_foreign_key_to_subjects_table', 1),
(21, '2025_04_24_230821_reset_all_tables', 1),
(22, '2025_04_25_000820_fix_teacher_class_column_names', 1),
(23, '2025_04_25_001258_standardize_pivot_table_columns', 1),
(24, '2025_04_26_100432_create_student_subject_pivot_table', 1),
(25, '2025_04_29_161552_create_class_subject_table', 1),
(26, '2025_05_09_141357_add_user_id_to_student_subject_table', 1),
(27, '2025_05_10_203157_create_hods_table', 1),
(28, '2025_05_19_195600_add_department_id_to_teachers_table', 2),
(31, '2025_05_23_052626_cleanup_evaluation_scores', 3),
(32, '2025_05_23_141737_update_evaluations_ratings_constraints', 3),
(35, '2025_05_24_075527_create_evaluation_cycles_table', 4),
(36, '2025_05_24_075634_add_evaluation_cycle_to_evaluations_table', 4),
(37, '2025_05_25_230821_make_ratings_nullable', 5),
(38, '2025_05_26_133727_add_hod_comments_to_evaluations_table', 6),
(40, '2025_05_26_224426_add_closed_status_to_evaluations', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('22GoZCW3ru9O38VpM1hSDDYDUZKGVyrwEagZi65U', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMHh6WDA2MHk5NVpnNVhlRkszM09WMEJTbW1iV1B3RWZkTUFkaVA3dyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1749371917),
('2fywfrHURDmLtLN39GK496du0Rvz29ARXx2RDMR0', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidXpZcGI2WGNrdzk2eTA1N1A1a3NWbU1SZUptdlhHcnBxQnk1VTRhcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9sb2NhbGhvc3QvcHJvdG90eXBlL3B1YmxpYy9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1749371079),
('8gLFILZrLdNQgM6mbUjrWGkLvyOFNqx3PGQinE4c', 57, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZ05SaEtQRkdvOGFabk54NTZrVlNFNE5BdWhKTDl1UjlSSkFPWk1XSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9sb2NhbGhvc3QvcHJvdG90eXBlL3B1YmxpYy9ob2QvcmVwb3J0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTc7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzQ5MDU5MjAyO319', 1749059218),
('b1Yd4gdL4kdB5mQ2agHNyXOblDl15MYcdLT23j7a', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUtxQmozelkyNkNxTnFKbjZBQzNDdkFmTXlRN0tzVVc3NXlNbGxDRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHA6Ly9sb2NhbGhvc3QvVGVhY2hlcl9FdmFsdWF0aW9uX1N5c3RlbS9wdWJsaWMvbG9naW4iO319', 1749579013),
('Hmw6m57H8jmwnvJHK0vHcDJlDZSNXgDZ5YqVIXRA', 59, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWWYwSUZuckVBdmVXUnR0VWtHaWd3clF0QVdsUVoyb1Q4VFo0M2JwMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTk6Imh0dHA6Ly9sb2NhbGhvc3QvcHJvdG90eXBlL3B1YmxpYy90ZWFjaGVyL3RlYWNoZXIvZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTk7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzQ4OTQzMDQwO319', 1748943045),
('khcKNsGNj4Nfm7pKXwp4CrrmHQsEkFRLHCLbKlKE', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZnVva3BZMmNCWWZXalo5U25CbE1IWjJvSGZaWHRnQVByNTVmdDFieSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9sb2NhbGhvc3QvcHJvdG90eXBlL3B1YmxpYy9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1748942266),
('mMmKCSWzsbYaxYxCWj3XoxesQJZgPwOVCI8MNT1K', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT1hhbThqTjZHMUZ1MGwxZGJDQUJnaWdWZksweEtzcnp0eDVhWGJNbCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHA6Ly9sb2NhbGhvc3QvVGVhY2hlcl9FdmFsdWF0aW9uX1N5c3RlbS9wdWJsaWMvbG9naW4iO319', 1749493828),
('NwU2axwu8jXMqxlukZofqESLhyp15DQLTYHgWC12', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0pBc1F3RTA4U1VsYm1sbzRxQk5oRDhzcmxLck5JTkJBbU1Sb0hTSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHA6Ly9sb2NhbGhvc3QvVGVhY2hlcl9FdmFsdWF0aW9uX1N5c3RlbS9wdWJsaWMvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1749493552),
('Ohuqb50akdesxaNiKXav9EtmvvhM2VnXcO4e6UF0', 59, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVFN2REVvWHhacFNRZVBTUmFZZGUwWldjdklCOXBiQ1pNV2lpYXRlUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTk6Imh0dHA6Ly9sb2NhbGhvc3QvcHJvdG90eXBlL3B1YmxpYy90ZWFjaGVyL2V2YWx1YXRpb24tcmVwb3J0Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTk7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzQ4OTQzNDk0O319', 1748947879),
('xdOfJgj2pOEee2q7RJcshX0JNGkxFyh4ajB0wb4i', 59, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTGNFZHBlTWlQdkZ3R3dtTTFKMnNNZm5NaHZhT2ZzSWQ1ZVpYQTdUcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90ZWFjaGVyL3RlYWNoZXIvZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTk7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzQ4OTQzMTAwO319', 1748943110);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, '2025-05-11 01:27:58', '2025-05-11 01:27:58'),
(2, 4, '2025-05-11 01:27:58', '2025-05-11 01:27:58'),
(3, 5, '2025-05-11 01:27:59', '2025-05-11 01:27:59'),
(4, 6, '2025-05-11 01:27:59', '2025-05-11 01:27:59'),
(5, 7, '2025-05-11 01:28:00', '2025-05-11 01:28:00'),
(6, 8, '2025-05-11 01:28:00', '2025-05-11 01:28:00'),
(7, 9, '2025-05-11 01:28:01', '2025-05-11 01:28:01'),
(8, 10, '2025-05-11 01:28:01', '2025-05-11 01:28:01'),
(9, 11, '2025-05-11 01:28:02', '2025-05-11 01:28:02'),
(10, 12, '2025-05-11 01:28:02', '2025-05-11 01:28:02'),
(11, 13, '2025-05-11 01:28:03', '2025-05-11 01:28:03'),
(12, 14, '2025-05-11 01:28:03', '2025-05-11 01:28:03'),
(13, 15, '2025-05-11 01:28:04', '2025-05-11 01:28:04'),
(14, 16, '2025-05-11 01:28:05', '2025-05-11 01:28:05'),
(15, 17, '2025-05-11 01:28:05', '2025-05-11 01:28:05'),
(16, 18, '2025-05-11 01:28:06', '2025-05-11 01:28:06'),
(17, 19, '2025-05-11 01:28:06', '2025-05-11 01:28:06'),
(18, 20, '2025-05-11 01:28:07', '2025-05-11 01:28:07'),
(19, 21, '2025-05-11 01:28:07', '2025-05-11 01:28:07'),
(20, 22, '2025-05-11 01:28:08', '2025-05-11 01:28:08'),
(21, 23, '2025-05-11 01:28:08', '2025-05-11 01:28:08'),
(22, 24, '2025-05-11 01:28:09', '2025-05-11 01:28:09'),
(23, 25, '2025-05-11 01:28:09', '2025-05-11 01:28:09'),
(24, 26, '2025-05-11 01:28:10', '2025-05-11 01:28:10'),
(25, 27, '2025-05-11 01:28:11', '2025-05-11 01:28:11'),
(26, 28, '2025-05-11 01:28:11', '2025-05-11 01:28:11'),
(27, 29, '2025-05-11 01:28:12', '2025-05-11 01:28:12'),
(28, 30, '2025-05-11 01:28:12', '2025-05-11 01:28:12'),
(29, 31, '2025-05-11 01:28:13', '2025-05-11 01:28:13'),
(30, 32, '2025-05-11 01:28:13', '2025-05-11 01:28:13'),
(31, 33, '2025-05-11 01:28:14', '2025-05-11 01:28:14'),
(32, 34, '2025-05-11 01:28:14', '2025-05-11 01:28:14'),
(33, 35, '2025-05-11 01:28:15', '2025-05-11 01:28:15'),
(34, 36, '2025-05-11 01:28:15', '2025-05-11 01:28:15'),
(35, 37, '2025-05-11 01:28:16', '2025-05-11 01:28:16'),
(36, 38, '2025-05-11 01:28:16', '2025-05-11 01:28:16'),
(37, 39, '2025-05-11 01:28:17', '2025-05-11 01:28:17'),
(38, 40, '2025-05-11 01:28:17', '2025-05-11 01:28:17'),
(39, 41, '2025-05-11 01:28:18', '2025-05-11 01:28:18'),
(40, 42, '2025-05-11 01:28:18', '2025-05-11 01:28:18'),
(41, 43, '2025-05-11 01:28:19', '2025-05-11 01:28:19'),
(42, 44, '2025-05-11 01:28:19', '2025-05-11 01:28:19'),
(43, 45, '2025-05-11 01:28:20', '2025-05-11 01:28:20'),
(44, 46, '2025-05-11 01:28:20', '2025-05-11 01:28:20'),
(45, 47, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(46, 48, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(47, 49, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(48, 50, '2025-05-11 01:28:22', '2025-05-11 01:28:22'),
(49, 51, '2025-05-11 01:28:22', '2025-05-11 01:28:22'),
(50, 52, '2025-05-11 01:28:23', '2025-05-11 01:28:23'),
(51, 68, '2025-05-30 20:00:05', '2025-05-30 20:00:05'),
(52, 69, '2025-05-30 21:29:43', '2025-05-30 21:29:43'),
(53, 72, '2025-06-01 11:17:31', '2025-06-01 11:17:31'),
(54, 73, '2025-06-01 11:18:35', '2025-06-01 11:18:35'),
(55, 74, '2025-06-01 11:19:43', '2025-06-01 11:19:43'),
(56, 75, '2025-06-01 11:22:19', '2025-06-01 11:22:19'),
(57, 76, '2025-06-01 16:48:18', '2025-06-01 16:48:18'),
(58, 77, '2025-06-02 07:58:25', '2025-06-02 07:58:25');

-- --------------------------------------------------------

--
-- Table structure for table `student_class`
--

CREATE TABLE `student_class` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_class`
--

INSERT INTO `student_class` (`id`, `student_id`, `class_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(2, 2, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(3, 3, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(4, 4, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(5, 5, 3, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(6, 6, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(7, 7, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(8, 8, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(9, 9, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(10, 10, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(11, 11, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(12, 12, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(13, 13, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(14, 14, 3, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(15, 15, 3, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(16, 16, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(17, 17, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(18, 18, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(19, 19, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(20, 20, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(21, 21, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(22, 22, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(23, 23, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(24, 24, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(25, 25, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(26, 26, 3, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(27, 27, 3, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(28, 28, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(29, 29, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(30, 30, 3, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(31, 31, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(32, 32, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(33, 33, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(34, 34, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(35, 35, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(36, 36, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(37, 37, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(38, 38, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(39, 39, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(40, 40, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(41, 41, 3, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(42, 42, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(43, 43, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(44, 44, 3, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(45, 45, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(46, 46, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(47, 47, 3, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(48, 48, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(49, 49, 1, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(50, 50, 2, '2025-05-11 01:28:28', '2025-05-11 01:28:28'),
(51, 51, 1, '2025-05-30 20:00:05', '2025-05-30 20:00:05'),
(53, 52, 3, '2025-06-01 11:00:13', '2025-06-01 11:00:13'),
(54, 53, 1, '2025-06-01 11:17:31', '2025-06-01 11:17:31'),
(55, 54, 3, '2025-06-01 11:18:35', '2025-06-01 11:18:35'),
(56, 55, 3, '2025-06-01 11:19:43', '2025-06-01 11:19:43'),
(57, 56, 2, '2025-06-01 11:22:19', '2025-06-01 11:22:19'),
(59, 58, 4, '2025-06-02 07:58:25', '2025-06-02 07:58:25'),
(60, 57, 3, '2025-06-03 07:12:43', '2025-06-03 07:12:43');

-- --------------------------------------------------------

--
-- Table structure for table `student_subject`
--

CREATE TABLE `student_subject` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_subject`
--

INSERT INTO `student_subject` (`id`, `student_id`, `subject_id`, `user_id`, `completed`, `class_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 0, 1, '2025-05-11 01:27:58', '2025-05-11 01:27:58'),
(2, 1, 3, NULL, 0, 1, '2025-05-11 01:27:58', '2025-05-11 01:27:58'),
(3, 1, 4, NULL, 0, 1, '2025-05-11 01:27:58', '2025-05-11 01:27:58'),
(4, 1, 5, NULL, 0, 1, '2025-05-11 01:27:58', '2025-05-11 01:27:58'),
(5, 2, 1, NULL, 0, 3, '2025-05-11 01:27:58', '2025-05-11 01:27:58'),
(6, 2, 2, NULL, 0, 3, '2025-05-11 01:27:58', '2025-05-11 01:27:58'),
(7, 2, 3, NULL, 0, 3, '2025-05-11 01:27:58', '2025-05-11 01:27:58'),
(8, 2, 4, NULL, 0, 3, '2025-05-11 01:27:58', '2025-05-11 01:27:58'),
(9, 3, 2, NULL, 1, 2, '2025-05-11 01:27:59', '2025-05-26 21:32:36'),
(10, 3, 3, NULL, 1, 2, '2025-05-11 01:27:59', '2025-05-28 11:59:08'),
(11, 3, 4, NULL, 1, 2, '2025-05-11 01:27:59', '2025-05-26 10:32:39'),
(12, 3, 5, NULL, 1, 2, '2025-05-11 01:27:59', '2025-05-26 10:32:58'),
(13, 4, 1, NULL, 0, 2, '2025-05-11 01:27:59', '2025-05-11 01:27:59'),
(14, 4, 3, NULL, 0, 2, '2025-05-11 01:27:59', '2025-05-11 01:27:59'),
(15, 4, 4, NULL, 0, 2, '2025-05-11 01:27:59', '2025-05-11 01:27:59'),
(16, 4, 5, NULL, 0, 2, '2025-05-11 01:27:59', '2025-05-11 01:27:59'),
(17, 5, 1, NULL, 0, 2, '2025-05-11 01:28:00', '2025-05-11 01:28:00'),
(18, 5, 2, NULL, 0, 2, '2025-05-11 01:28:00', '2025-05-11 01:28:00'),
(19, 5, 3, NULL, 0, 2, '2025-05-11 01:28:00', '2025-05-11 01:28:00'),
(20, 5, 4, NULL, 0, 2, '2025-05-11 01:28:00', '2025-05-11 01:28:00'),
(21, 6, 1, NULL, 1, 3, '2025-05-11 01:28:00', '2025-05-29 17:14:14'),
(22, 6, 2, NULL, 0, 3, '2025-05-11 01:28:00', '2025-05-11 01:28:00'),
(23, 6, 3, NULL, 0, 3, '2025-05-11 01:28:00', '2025-05-11 01:28:00'),
(24, 6, 4, NULL, 0, 3, '2025-05-11 01:28:00', '2025-05-11 01:28:00'),
(25, 7, 1, NULL, 0, 2, '2025-05-11 01:28:01', '2025-05-11 01:28:01'),
(26, 7, 2, NULL, 0, 2, '2025-05-11 01:28:01', '2025-05-11 01:28:01'),
(27, 7, 3, NULL, 0, 2, '2025-05-11 01:28:01', '2025-05-11 01:28:01'),
(28, 7, 4, NULL, 0, 2, '2025-05-11 01:28:01', '2025-05-11 01:28:01'),
(29, 8, 1, NULL, 1, 3, '2025-05-11 01:28:01', '2025-05-29 09:03:44'),
(30, 8, 2, NULL, 1, 3, '2025-05-11 01:28:01', '2025-05-29 09:04:02'),
(31, 8, 3, NULL, 0, 3, '2025-05-11 01:28:01', '2025-05-11 01:28:01'),
(32, 8, 5, NULL, 1, 3, '2025-05-11 01:28:01', '2025-05-29 09:06:40'),
(33, 9, 1, NULL, 0, 3, '2025-05-11 01:28:02', '2025-05-11 01:28:02'),
(34, 9, 2, NULL, 0, 3, '2025-05-11 01:28:02', '2025-05-11 01:28:02'),
(35, 9, 3, NULL, 0, 3, '2025-05-11 01:28:02', '2025-05-11 01:28:02'),
(36, 9, 5, NULL, 0, 3, '2025-05-11 01:28:02', '2025-05-11 01:28:02'),
(37, 10, 1, NULL, 0, 1, '2025-05-11 01:28:02', '2025-05-11 01:28:02'),
(38, 10, 2, NULL, 0, 1, '2025-05-11 01:28:02', '2025-05-11 01:28:02'),
(39, 10, 3, NULL, 0, 1, '2025-05-11 01:28:02', '2025-05-11 01:28:02'),
(40, 10, 4, NULL, 0, 1, '2025-05-11 01:28:02', '2025-05-11 01:28:02'),
(41, 11, 1, NULL, 0, 3, '2025-05-11 01:28:03', '2025-05-11 01:28:03'),
(42, 11, 2, NULL, 0, 3, '2025-05-11 01:28:03', '2025-05-11 01:28:03'),
(43, 11, 3, NULL, 0, 3, '2025-05-11 01:28:03', '2025-05-11 01:28:03'),
(44, 11, 5, NULL, 0, 3, '2025-05-11 01:28:03', '2025-05-11 01:28:03'),
(45, 12, 2, NULL, 0, 2, '2025-05-11 01:28:03', '2025-05-11 01:28:03'),
(46, 12, 3, NULL, 0, 2, '2025-05-11 01:28:03', '2025-05-11 01:28:03'),
(47, 12, 4, NULL, 0, 2, '2025-05-11 01:28:03', '2025-05-11 01:28:03'),
(48, 12, 5, NULL, 0, 2, '2025-05-11 01:28:03', '2025-05-11 01:28:03'),
(49, 13, 2, NULL, 0, 1, '2025-05-11 01:28:04', '2025-05-11 01:28:04'),
(50, 13, 3, NULL, 0, 1, '2025-05-11 01:28:04', '2025-05-11 01:28:04'),
(51, 13, 4, NULL, 0, 1, '2025-05-11 01:28:04', '2025-05-11 01:28:04'),
(52, 13, 5, NULL, 0, 1, '2025-05-11 01:28:04', '2025-05-11 01:28:04'),
(53, 14, 1, NULL, 0, 3, '2025-05-11 01:28:05', '2025-05-11 01:28:05'),
(54, 14, 3, NULL, 0, 3, '2025-05-11 01:28:05', '2025-05-11 01:28:05'),
(55, 14, 4, NULL, 0, 3, '2025-05-11 01:28:05', '2025-05-11 01:28:05'),
(56, 14, 5, NULL, 0, 3, '2025-05-11 01:28:05', '2025-05-11 01:28:05'),
(57, 15, 1, NULL, 1, 1, '2025-05-11 01:28:05', '2025-05-29 09:16:05'),
(58, 15, 2, NULL, 1, 1, '2025-05-11 01:28:05', '2025-05-29 09:16:25'),
(59, 15, 3, NULL, 0, 1, '2025-05-11 01:28:05', '2025-05-11 01:28:05'),
(60, 15, 5, NULL, 1, 1, '2025-05-11 01:28:05', '2025-05-29 09:16:48'),
(61, 16, 1, NULL, 0, 1, '2025-05-11 01:28:06', '2025-05-11 01:28:06'),
(62, 16, 3, NULL, 0, 1, '2025-05-11 01:28:06', '2025-05-11 01:28:06'),
(63, 16, 4, NULL, 0, 1, '2025-05-11 01:28:06', '2025-05-11 01:28:06'),
(64, 16, 5, NULL, 0, 1, '2025-05-11 01:28:06', '2025-05-11 01:28:06'),
(65, 17, 1, NULL, 0, 2, '2025-05-11 01:28:06', '2025-05-11 01:28:06'),
(66, 17, 2, NULL, 0, 2, '2025-05-11 01:28:06', '2025-05-11 01:28:06'),
(67, 17, 3, NULL, 0, 2, '2025-05-11 01:28:06', '2025-05-11 01:28:06'),
(68, 17, 4, NULL, 0, 2, '2025-05-11 01:28:06', '2025-05-11 01:28:06'),
(69, 18, 1, NULL, 0, 1, '2025-05-11 01:28:07', '2025-05-11 01:28:07'),
(70, 18, 2, NULL, 0, 1, '2025-05-11 01:28:07', '2025-05-11 01:28:07'),
(71, 18, 4, NULL, 0, 1, '2025-05-11 01:28:07', '2025-05-11 01:28:07'),
(72, 18, 5, NULL, 0, 1, '2025-05-11 01:28:07', '2025-05-11 01:28:07'),
(73, 19, 2, NULL, 0, 1, '2025-05-11 01:28:07', '2025-05-11 01:28:07'),
(74, 19, 3, NULL, 0, 1, '2025-05-11 01:28:07', '2025-05-11 01:28:07'),
(75, 19, 4, NULL, 0, 1, '2025-05-11 01:28:07', '2025-05-11 01:28:07'),
(76, 19, 5, NULL, 0, 1, '2025-05-11 01:28:07', '2025-05-11 01:28:07'),
(77, 20, 1, NULL, 0, 2, '2025-05-11 01:28:08', '2025-05-11 01:28:08'),
(78, 20, 2, NULL, 0, 2, '2025-05-11 01:28:08', '2025-05-11 01:28:08'),
(79, 20, 3, NULL, 0, 2, '2025-05-11 01:28:08', '2025-05-11 01:28:08'),
(80, 20, 4, NULL, 0, 2, '2025-05-11 01:28:08', '2025-05-11 01:28:08'),
(81, 21, 1, NULL, 0, 3, '2025-05-11 01:28:08', '2025-05-11 01:28:08'),
(82, 21, 2, NULL, 0, 3, '2025-05-11 01:28:08', '2025-05-11 01:28:08'),
(83, 21, 4, NULL, 0, 3, '2025-05-11 01:28:08', '2025-05-11 01:28:08'),
(84, 21, 5, NULL, 0, 3, '2025-05-11 01:28:08', '2025-05-11 01:28:08'),
(85, 22, 1, NULL, 0, 3, '2025-05-11 01:28:09', '2025-05-11 01:28:09'),
(86, 22, 2, NULL, 0, 3, '2025-05-11 01:28:09', '2025-05-11 01:28:09'),
(87, 22, 4, NULL, 0, 3, '2025-05-11 01:28:09', '2025-05-11 01:28:09'),
(88, 22, 5, NULL, 0, 3, '2025-05-11 01:28:09', '2025-05-11 01:28:09'),
(89, 23, 1, NULL, 0, 1, '2025-05-11 01:28:09', '2025-05-11 01:28:09'),
(90, 23, 3, NULL, 0, 1, '2025-05-11 01:28:09', '2025-05-11 01:28:09'),
(91, 23, 4, NULL, 0, 1, '2025-05-11 01:28:09', '2025-05-11 01:28:09'),
(92, 23, 5, NULL, 0, 1, '2025-05-11 01:28:09', '2025-05-11 01:28:09'),
(93, 24, 1, NULL, 0, 1, '2025-05-11 01:28:10', '2025-05-11 01:28:10'),
(94, 24, 2, NULL, 0, 1, '2025-05-11 01:28:10', '2025-05-11 01:28:10'),
(95, 24, 4, NULL, 0, 1, '2025-05-11 01:28:10', '2025-05-11 01:28:10'),
(96, 24, 5, NULL, 0, 1, '2025-05-11 01:28:10', '2025-05-11 01:28:10'),
(97, 25, 1, NULL, 0, 3, '2025-05-11 01:28:11', '2025-05-11 01:28:11'),
(98, 25, 2, NULL, 0, 3, '2025-05-11 01:28:11', '2025-05-11 01:28:11'),
(99, 25, 4, NULL, 0, 3, '2025-05-11 01:28:11', '2025-05-11 01:28:11'),
(100, 25, 5, NULL, 0, 3, '2025-05-11 01:28:11', '2025-05-11 01:28:11'),
(101, 26, 1, NULL, 0, 3, '2025-05-11 01:28:11', '2025-05-11 01:28:11'),
(102, 26, 2, NULL, 0, 3, '2025-05-11 01:28:11', '2025-05-11 01:28:11'),
(103, 26, 3, NULL, 0, 3, '2025-05-11 01:28:11', '2025-05-11 01:28:11'),
(104, 26, 5, NULL, 0, 3, '2025-05-11 01:28:11', '2025-05-11 01:28:11'),
(105, 27, 1, NULL, 0, 2, '2025-05-11 01:28:12', '2025-05-11 01:28:12'),
(106, 27, 3, NULL, 0, 2, '2025-05-11 01:28:12', '2025-05-11 01:28:12'),
(107, 27, 4, NULL, 0, 2, '2025-05-11 01:28:12', '2025-05-11 01:28:12'),
(108, 27, 5, NULL, 0, 2, '2025-05-11 01:28:12', '2025-05-11 01:28:12'),
(109, 28, 1, NULL, 0, 3, '2025-05-11 01:28:12', '2025-05-11 01:28:12'),
(110, 28, 2, NULL, 0, 3, '2025-05-11 01:28:12', '2025-05-11 01:28:12'),
(111, 28, 4, NULL, 0, 3, '2025-05-11 01:28:12', '2025-05-11 01:28:12'),
(112, 28, 5, NULL, 0, 3, '2025-05-11 01:28:12', '2025-05-11 01:28:12'),
(113, 29, 1, NULL, 0, 3, '2025-05-11 01:28:13', '2025-05-11 01:28:13'),
(114, 29, 2, NULL, 0, 3, '2025-05-11 01:28:13', '2025-05-11 01:28:13'),
(115, 29, 3, NULL, 0, 3, '2025-05-11 01:28:13', '2025-05-11 01:28:13'),
(116, 29, 4, NULL, 0, 3, '2025-05-11 01:28:13', '2025-05-11 01:28:13'),
(117, 30, 1, NULL, 0, 2, '2025-05-11 01:28:13', '2025-05-11 01:28:13'),
(118, 30, 2, NULL, 0, 2, '2025-05-11 01:28:13', '2025-05-11 01:28:13'),
(119, 30, 4, NULL, 0, 2, '2025-05-11 01:28:13', '2025-05-11 01:28:13'),
(120, 30, 5, NULL, 0, 2, '2025-05-11 01:28:13', '2025-05-11 01:28:13'),
(121, 31, 1, NULL, 0, 2, '2025-05-11 01:28:14', '2025-05-11 01:28:14'),
(122, 31, 2, NULL, 0, 2, '2025-05-11 01:28:14', '2025-05-11 01:28:14'),
(123, 31, 3, NULL, 0, 2, '2025-05-11 01:28:14', '2025-05-11 01:28:14'),
(124, 31, 4, NULL, 0, 2, '2025-05-11 01:28:14', '2025-05-11 01:28:14'),
(125, 32, 1, NULL, 0, 3, '2025-05-11 01:28:14', '2025-05-11 01:28:14'),
(126, 32, 3, NULL, 0, 3, '2025-05-11 01:28:14', '2025-05-11 01:28:14'),
(127, 32, 4, NULL, 0, 3, '2025-05-11 01:28:14', '2025-05-11 01:28:14'),
(128, 32, 5, NULL, 0, 3, '2025-05-11 01:28:14', '2025-05-11 01:28:14'),
(129, 33, 1, NULL, 0, 1, '2025-05-11 01:28:15', '2025-05-11 01:28:15'),
(130, 33, 2, NULL, 0, 1, '2025-05-11 01:28:15', '2025-05-11 01:28:15'),
(131, 33, 4, NULL, 0, 1, '2025-05-11 01:28:15', '2025-05-11 01:28:15'),
(132, 33, 5, NULL, 0, 1, '2025-05-11 01:28:15', '2025-05-11 01:28:15'),
(133, 34, 1, NULL, 0, 3, '2025-05-11 01:28:15', '2025-05-11 01:28:15'),
(134, 34, 2, NULL, 0, 3, '2025-05-11 01:28:15', '2025-05-11 01:28:15'),
(135, 34, 4, NULL, 0, 3, '2025-05-11 01:28:15', '2025-05-11 01:28:15'),
(136, 34, 5, NULL, 0, 3, '2025-05-11 01:28:15', '2025-05-11 01:28:15'),
(137, 35, 1, NULL, 0, 2, '2025-05-11 01:28:16', '2025-05-11 01:28:16'),
(138, 35, 2, NULL, 0, 2, '2025-05-11 01:28:16', '2025-05-11 01:28:16'),
(139, 35, 3, NULL, 0, 2, '2025-05-11 01:28:16', '2025-05-11 01:28:16'),
(140, 35, 5, NULL, 0, 2, '2025-05-11 01:28:16', '2025-05-11 01:28:16'),
(141, 36, 1, NULL, 0, 2, '2025-05-11 01:28:16', '2025-05-11 01:28:16'),
(142, 36, 2, NULL, 0, 2, '2025-05-11 01:28:16', '2025-05-11 01:28:16'),
(143, 36, 4, NULL, 0, 2, '2025-05-11 01:28:16', '2025-05-11 01:28:16'),
(144, 36, 5, NULL, 0, 2, '2025-05-11 01:28:16', '2025-05-11 01:28:16'),
(145, 37, 1, NULL, 0, 2, '2025-05-11 01:28:17', '2025-05-11 01:28:17'),
(146, 37, 3, NULL, 0, 2, '2025-05-11 01:28:17', '2025-05-11 01:28:17'),
(147, 37, 4, NULL, 0, 2, '2025-05-11 01:28:17', '2025-05-11 01:28:17'),
(148, 37, 5, NULL, 0, 2, '2025-05-11 01:28:17', '2025-05-11 01:28:17'),
(149, 38, 1, NULL, 0, 3, '2025-05-11 01:28:17', '2025-05-11 01:28:17'),
(150, 38, 3, NULL, 0, 3, '2025-05-11 01:28:17', '2025-05-11 01:28:17'),
(151, 38, 4, NULL, 0, 3, '2025-05-11 01:28:17', '2025-05-11 01:28:17'),
(152, 38, 5, NULL, 0, 3, '2025-05-11 01:28:17', '2025-05-11 01:28:17'),
(153, 39, 2, NULL, 0, 2, '2025-05-11 01:28:18', '2025-05-11 01:28:18'),
(154, 39, 3, NULL, 0, 2, '2025-05-11 01:28:18', '2025-05-11 01:28:18'),
(155, 39, 4, NULL, 0, 2, '2025-05-11 01:28:18', '2025-05-11 01:28:18'),
(156, 39, 5, NULL, 0, 2, '2025-05-11 01:28:18', '2025-05-11 01:28:18'),
(157, 40, 1, NULL, 0, 3, '2025-05-11 01:28:18', '2025-05-11 01:28:18'),
(158, 40, 2, NULL, 0, 3, '2025-05-11 01:28:18', '2025-05-11 01:28:18'),
(159, 40, 4, NULL, 0, 3, '2025-05-11 01:28:18', '2025-05-11 01:28:18'),
(160, 40, 5, NULL, 0, 3, '2025-05-11 01:28:18', '2025-05-11 01:28:18'),
(161, 41, 1, NULL, 0, 2, '2025-05-11 01:28:19', '2025-05-11 01:28:19'),
(162, 41, 2, NULL, 0, 2, '2025-05-11 01:28:19', '2025-05-11 01:28:19'),
(163, 41, 4, NULL, 0, 2, '2025-05-11 01:28:19', '2025-05-11 01:28:19'),
(164, 41, 5, NULL, 0, 2, '2025-05-11 01:28:19', '2025-05-11 01:28:19'),
(165, 42, 1, NULL, 0, 1, '2025-05-11 01:28:19', '2025-05-11 01:28:19'),
(166, 42, 2, NULL, 0, 1, '2025-05-11 01:28:19', '2025-05-11 01:28:19'),
(167, 42, 3, NULL, 0, 1, '2025-05-11 01:28:19', '2025-05-11 01:28:19'),
(168, 42, 5, NULL, 0, 1, '2025-05-11 01:28:19', '2025-05-11 01:28:19'),
(169, 43, 1, NULL, 0, 3, '2025-05-11 01:28:20', '2025-05-11 01:28:20'),
(170, 43, 2, NULL, 0, 3, '2025-05-11 01:28:20', '2025-05-11 01:28:20'),
(171, 43, 4, NULL, 0, 3, '2025-05-11 01:28:20', '2025-05-11 01:28:20'),
(172, 43, 5, NULL, 0, 3, '2025-05-11 01:28:20', '2025-05-11 01:28:20'),
(173, 44, 2, NULL, 0, 2, '2025-05-11 01:28:20', '2025-05-11 01:28:20'),
(174, 44, 3, NULL, 0, 2, '2025-05-11 01:28:20', '2025-05-11 01:28:20'),
(175, 44, 4, NULL, 0, 2, '2025-05-11 01:28:20', '2025-05-11 01:28:20'),
(176, 44, 5, NULL, 0, 2, '2025-05-11 01:28:20', '2025-05-11 01:28:20'),
(177, 45, 1, NULL, 1, 2, '2025-05-11 01:28:21', '2025-05-29 09:18:27'),
(178, 45, 3, NULL, 1, 2, '2025-05-11 01:28:21', '2025-05-29 09:19:11'),
(179, 45, 4, NULL, 1, 2, '2025-05-11 01:28:21', '2025-05-29 09:18:43'),
(180, 45, 5, NULL, 0, 2, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(181, 46, 1, NULL, 0, 1, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(182, 46, 2, NULL, 0, 1, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(183, 46, 3, NULL, 0, 1, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(184, 46, 5, NULL, 0, 1, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(185, 47, 1, NULL, 0, 1, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(186, 47, 2, NULL, 0, 1, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(187, 47, 4, NULL, 0, 1, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(188, 47, 5, NULL, 0, 1, '2025-05-11 01:28:21', '2025-05-11 01:28:21'),
(189, 48, 1, NULL, 0, 3, '2025-05-11 01:28:22', '2025-05-11 01:28:22'),
(190, 48, 2, NULL, 0, 3, '2025-05-11 01:28:22', '2025-05-11 01:28:22'),
(191, 48, 3, NULL, 0, 3, '2025-05-11 01:28:22', '2025-05-11 01:28:22'),
(192, 48, 4, NULL, 0, 3, '2025-05-11 01:28:22', '2025-05-11 01:28:22'),
(193, 49, 1, NULL, 0, 1, '2025-05-11 01:28:22', '2025-05-11 01:28:22'),
(194, 49, 2, NULL, 0, 1, '2025-05-11 01:28:22', '2025-05-11 01:28:22'),
(195, 49, 3, NULL, 0, 1, '2025-05-11 01:28:22', '2025-05-11 01:28:22'),
(196, 49, 5, NULL, 0, 1, '2025-05-11 01:28:22', '2025-05-11 01:28:22'),
(197, 50, 1, NULL, 0, 2, '2025-05-11 01:28:23', '2025-05-11 01:28:23'),
(198, 50, 2, NULL, 0, 2, '2025-05-11 01:28:23', '2025-05-11 01:28:23'),
(199, 50, 3, NULL, 0, 2, '2025-05-11 01:28:23', '2025-05-11 01:28:23'),
(200, 50, 5, NULL, 0, 2, '2025-05-11 01:28:23', '2025-05-11 01:28:23'),
(201, 51, 1, NULL, 0, 1, '2025-05-30 20:00:05', '2025-05-30 20:00:05'),
(202, 51, 2, NULL, 0, 1, '2025-05-30 20:00:05', '2025-05-30 20:00:05'),
(203, 51, 3, NULL, 0, 1, '2025-05-30 20:00:05', '2025-05-30 20:00:05'),
(204, 51, 4, NULL, 0, 1, '2025-05-30 20:00:05', '2025-05-30 20:00:05'),
(209, 52, 1, NULL, 0, 3, '2025-06-01 11:00:13', '2025-06-01 11:00:13'),
(210, 52, 2, NULL, 0, 3, '2025-06-01 11:00:13', '2025-06-01 11:00:13'),
(211, 52, 3, NULL, 1, 3, '2025-06-01 11:00:13', '2025-06-01 11:02:27'),
(212, 52, 4, NULL, 1, 3, '2025-06-01 11:00:13', '2025-06-01 17:09:02'),
(213, 53, 1, NULL, 0, 1, '2025-06-01 11:17:31', '2025-06-01 11:17:31'),
(214, 53, 3, NULL, 0, 1, '2025-06-01 11:17:31', '2025-06-01 11:17:31'),
(215, 53, 4, NULL, 0, 1, '2025-06-01 11:17:31', '2025-06-01 11:17:31'),
(216, 53, 5, NULL, 0, 1, '2025-06-01 11:17:31', '2025-06-01 11:17:31'),
(217, 54, 1, NULL, 0, 3, '2025-06-01 11:18:35', '2025-06-01 11:18:35'),
(218, 54, 2, NULL, 0, 3, '2025-06-01 11:18:35', '2025-06-01 11:18:35'),
(219, 54, 3, NULL, 0, 3, '2025-06-01 11:18:35', '2025-06-01 11:18:35'),
(220, 54, 4, NULL, 0, 3, '2025-06-01 11:18:35', '2025-06-01 11:18:35'),
(221, 55, 1, NULL, 0, 3, '2025-06-01 11:19:43', '2025-06-01 11:19:43'),
(222, 55, 2, NULL, 0, 3, '2025-06-01 11:19:43', '2025-06-01 11:19:43'),
(223, 55, 3, NULL, 0, 3, '2025-06-01 11:19:43', '2025-06-01 11:19:43'),
(224, 55, 4, NULL, 0, 3, '2025-06-01 11:19:43', '2025-06-01 11:19:43'),
(225, 56, 1, NULL, 0, 2, '2025-06-01 11:22:19', '2025-06-01 11:22:19'),
(226, 56, 2, NULL, 0, 2, '2025-06-01 11:22:19', '2025-06-01 11:22:19'),
(227, 56, 3, NULL, 0, 2, '2025-06-01 11:22:19', '2025-06-01 11:22:19'),
(228, 56, 4, NULL, 0, 2, '2025-06-01 11:22:19', '2025-06-01 11:22:19'),
(233, 58, 1, NULL, 1, 4, '2025-06-02 07:58:25', '2025-06-03 07:28:19'),
(234, 58, 2, NULL, 1, 4, '2025-06-02 07:58:25', '2025-06-02 08:00:07'),
(235, 58, 3, NULL, 0, 4, '2025-06-02 07:58:25', '2025-06-02 07:58:25'),
(236, 58, 4, NULL, 0, 4, '2025-06-02 07:58:25', '2025-06-02 07:58:25'),
(237, 57, 1, NULL, 0, 3, '2025-06-03 07:12:43', '2025-06-03 07:12:43'),
(238, 57, 2, NULL, 0, 3, '2025-06-03 07:12:43', '2025-06-03 07:12:43'),
(239, 57, 3, NULL, 0, 3, '2025-06-03 07:12:43', '2025-06-03 07:12:43'),
(240, 57, 4, NULL, 0, 3, '2025-06-03 07:12:43', '2025-06-03 07:12:43');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 'Mathematics', 1, '2025-05-11 01:27:56', '2025-05-11 01:27:56'),
(2, 'Physics', 2, '2025-05-11 01:27:56', '2025-05-11 01:27:56'),
(3, 'English', 3, '2025-05-11 01:27:56', '2025-05-11 01:27:56'),
(4, 'Shona', 3, '2025-05-11 01:27:56', '2025-05-11 01:27:56'),
(5, 'History', 4, '2025-05-11 01:27:56', '2025-05-11 01:27:56');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `department_id`, `user_id`, `subject_id`, `created_at`, `updated_at`) VALUES
(1, 1, 54, 1, '2025-05-11 01:28:27', '2025-05-11 01:28:27'),
(2, 2, 56, 2, '2025-05-11 01:28:27', '2025-05-11 01:28:27'),
(3, 3, 58, 3, '2025-05-11 01:28:27', '2025-05-11 01:28:27'),
(4, 3, 59, 4, '2025-05-11 01:28:27', '2025-05-11 01:28:27'),
(5, 4, 61, 5, '2025-05-11 01:28:27', '2025-05-11 01:28:27'),
(8, 2, 70, 2, '2025-05-30 22:09:03', '2025-05-30 22:09:03'),
(9, 1, 71, 1, '2025-05-31 06:16:23', '2025-05-31 06:16:23');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_class`
--

CREATE TABLE `teacher_class` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_class`
--

INSERT INTO `teacher_class` (`id`, `teacher_id`, `class_id`, `subject_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(2, 1, 2, 4, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(3, 2, 1, 2, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(4, 2, 2, 1, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(5, 2, 3, 5, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(6, 3, 1, 2, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(7, 3, 2, 3, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(8, 3, 3, 4, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(9, 4, 1, 5, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(10, 4, 2, 1, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(11, 4, 3, 5, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(12, 5, 1, 4, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(13, 5, 2, 2, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(14, 5, 3, 1, '2025-05-11 02:10:39', '2025-05-11 02:10:39'),
(15, 9, 4, 1, '2025-05-31 06:16:23', '2025-05-31 06:16:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT 'default_username',
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student','hod','deputy_head') NOT NULL DEFAULT 'student',
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `subject_id`, `remember_token`, `created_at`, `updated_at`, `department_id`) VALUES
(1, 'System Administrator', 'sysadmin', 'admin@school.edu', '2025-05-11 01:27:56', '$2y$12$DW3cET7k.KkSOfmXhAGVNe4QLo21QgsUAUGFoiDZDx4UTivqZqcYO', 'admin', NULL, 'mj2Jv7ppFBsV8n8XjQOBGLnpgG2pqURqiRmuWqQIhlCFoIvSAhFTeTQACLRX', '2025-05-11 01:27:56', '2025-05-11 01:27:56', NULL),
(2, 'Deputy Head Academic', 'deputy_head', 'deputy@school.edu', '2025-05-11 01:27:57', '$2y$12$HMKqoczulDJdZ.DdrI2ove6LV2bObF7J9Wg8pLaufX4cntn5lcxo.', 'deputy_head', NULL, 'n8FnlZKXxC', '2025-05-11 01:27:57', '2025-05-11 01:27:57', NULL),
(3, 'Student 001', 'student_001', 'student001@school.edu', '2025-05-11 01:27:57', '$2y$12$Z/Wp1.rlTiqJ1M9kYhBqyenr6Nk5FL8EQMZ8f30R1WilbuSoYa19G', 'student', NULL, 'BwkYawdVlfH3wtDLQ0UbMMgGMjSarx541SVrujOLZ4VTKYZdltrxWmw8qLLv', '2025-05-11 01:27:57', '2025-05-11 01:27:57', NULL),
(4, 'Student 002', 'student_002', 'student002@school.edu', '2025-05-11 01:27:58', '$2y$12$13W3ZsWCpPR4i1rPxuzMMufdE.3lKXWZOpq67hmAslXNYmrHDWziO', 'student', NULL, '78FPR8d9os', '2025-05-11 01:27:58', '2025-05-11 01:27:58', NULL),
(5, 'Student 003', 'student_003', 'student003@school.edu', '2025-05-11 01:27:59', '$2y$12$ZOXfIh/EFyw7Xv8eoKoaG.QtRImJLqEvhMFrDTROSczllg90pRQS.', 'student', NULL, '9BdnNbMOn4mQCSUHEWik4FuWmHmySYSQM0iweRIWvk7vTXePzXBHp7V92aHl', '2025-05-11 01:27:59', '2025-05-11 01:27:59', NULL),
(6, 'Student 004', 'student_004', 'student004@school.edu', '2025-05-11 01:27:59', '$2y$12$amWpSBCkmadqLbSUN.wq6.sEqR9lWaCqgppwQ6uBfMm0v6yoNPPri', 'student', NULL, 'gwz5rl1M8v', '2025-05-11 01:27:59', '2025-05-11 01:27:59', NULL),
(7, 'Student 005', 'student_005', 'student005@school.edu', '2025-05-11 01:28:00', '$2y$12$FKXMiiysKWIuKqjC2OU04uHg1IOOnmvfxAMZzd7/USo9.dzo4Qf6O', 'student', NULL, '12Hpqmjez6aoajNuof1MerSxLdZZijstmT6pd6SyJQqYrbSe71xJyleQwtO3', '2025-05-11 01:28:00', '2025-05-11 01:28:00', NULL),
(8, 'Student 006', 'student_006', 'student006@school.edu', '2025-05-11 01:28:00', '$2y$12$ovZbd1tkSMVWsxxg//dQdOT6Cnj1Wi4Oj7HmWtuaasBWBVZq9xmYu', 'student', NULL, '58EZNHpddR0gKEy9SqvZVdVqiyTYpOgYg5x860wo65DXi1rKPYnBQlmqrKaI', '2025-05-11 01:28:00', '2025-05-11 01:28:00', NULL),
(9, 'Student 007', 'student_007', 'student007@school.edu', '2025-05-11 01:28:01', '$2y$12$A0PIzbjD08XgLEZqCGSSD.DDommsrW4N.dQ.T8XMN1JBQlSqllRhC', 'student', NULL, 'y3tWAjLXzi', '2025-05-11 01:28:01', '2025-05-11 01:28:01', NULL),
(10, 'Student 008', 'student_008', 'student008@school.edu', '2025-05-11 01:28:01', '$2y$12$LN7U3WUxxhBcQS0.Xwz3XuEalKl2a9eytS6cFwPGwS9Q70mNsBRbm', 'student', NULL, 'l19b9phR8euT1kCGaMJP2c4N0SlpZi9XQWk2Jibr2T9Sns0Ms6ZAy8guoBYw', '2025-05-11 01:28:01', '2025-05-11 01:28:01', NULL),
(11, 'Student 009', 'student_009', 'student009@school.edu', '2025-05-11 01:28:02', '$2y$12$AQnsPpcgzNSjmlaaplf4Z.l.6SKkNcYVPaJArcS5ueUhO8Jg5CONK', 'student', NULL, 'bLRfBkLn6q', '2025-05-11 01:28:02', '2025-05-11 01:28:02', NULL),
(12, 'Student 010', 'student_010', 'student010@school.edu', '2025-05-11 01:28:02', '$2y$12$cPxsB9TDtUQNa7AZIeu/1ufzLrvnfES9f45XCwQXJKX/nGBxZNtpy', 'student', NULL, 'ZRdaqVc953', '2025-05-11 01:28:02', '2025-05-11 01:28:02', NULL),
(13, 'Student 011', 'student_011', 'student011@school.edu', '2025-05-11 01:28:03', '$2y$12$XPT0zGMAzkf8Xh0D4W63fezYCxSX/szpDj77OI9oNWDXkLemaub4y', 'student', NULL, 'ALaU79S6z9', '2025-05-11 01:28:03', '2025-05-11 01:28:03', NULL),
(14, 'Student 012', 'student_012', 'student012@school.edu', '2025-05-11 01:28:03', '$2y$12$pXBCAeB9gFGBKWuvAbF.fukVzEEk.rFYv1AQo9ZcBpJdCTHnZ0Sci', 'student', NULL, 'YRI8qj63Vb', '2025-05-11 01:28:03', '2025-05-11 01:28:03', NULL),
(15, 'Student 013', 'student_013', 'student013@school.edu', '2025-05-11 01:28:04', '$2y$12$Rje.odP3nVxWp8t7ZKGJWuKSy5pCSMsBSHZwq0Fa13LG1M3gOkb7K', 'student', NULL, 'lU7aBfzj3V', '2025-05-11 01:28:04', '2025-05-11 01:28:04', NULL),
(16, 'Student 014', 'student_014', 'student014@school.edu', '2025-05-11 01:28:05', '$2y$12$5DovKGGXsA4ADvnR3eBYpudcmj235y.DSoj.f2QrcZHojoPr4CNc.', 'student', NULL, 'rec9eISpo1', '2025-05-11 01:28:05', '2025-05-11 01:28:05', NULL),
(17, 'Student 015', 'student_015', 'student015@school.edu', '2025-05-11 01:28:05', '$2y$12$IpmRtr8b5J/m.NI61kvlv.j4ikNUS15bnPRycZTFA1N/NE4e3wJB2', 'student', NULL, 'KPJynxNX2zNkQpTz3WztptaZrdtQV8gYWSmPywzq4lXqxIRwoyl6qywVU1OX', '2025-05-11 01:28:05', '2025-05-11 01:28:05', NULL),
(18, 'Student 016', 'student_016', 'student016@school.edu', '2025-05-11 01:28:06', '$2y$12$FTLVq0i1CSWGXDs760hDn.7MpZd.5bRdVkEcnRgrGIAedc5x.ZKxe', 'student', NULL, 'MB6GZTLcjR', '2025-05-11 01:28:06', '2025-05-11 01:28:06', NULL),
(19, 'Student 017', 'student_017', 'student017@school.edu', '2025-05-11 01:28:06', '$2y$12$2ve6kOjFiI/KoUwaxseDhOihijURspGApJTK3kTehruqmYTPUZiau', 'student', NULL, 'DJmtX1TDYk', '2025-05-11 01:28:06', '2025-05-11 01:28:06', NULL),
(20, 'Student 018', 'student_018', 'student018@school.edu', '2025-05-11 01:28:07', '$2y$12$XfkdPYGSiKjvhwHL/UHRJekDyPRM.1ZrOXBoyV8AovkhqedQCtsvS', 'student', NULL, '7TAeuKfIms', '2025-05-11 01:28:07', '2025-05-11 01:28:07', NULL),
(21, 'Student 019', 'student_019', 'student019@school.edu', '2025-05-11 01:28:07', '$2y$12$Xz38qqxVrtrDumzynuEZquyfOgbNPnKVXO9zO2Z24OOEIJFINqrji', 'student', NULL, 'EOmnuenXiq', '2025-05-11 01:28:07', '2025-05-11 01:28:07', NULL),
(22, 'Student 020', 'student_020', 'student020@school.edu', '2025-05-11 01:28:08', '$2y$12$Lu19/MPx9ZsrUKI8igqvre3uphkgEBLqYIrSWqick95VBdvbUfz1a', 'student', NULL, 'GsgdFl4uTN', '2025-05-11 01:28:08', '2025-05-11 01:28:08', NULL),
(23, 'Student 021', 'student_021', 'student021@school.edu', '2025-05-11 01:28:08', '$2y$12$4DjJp9x9688Lk.ZDt0TJCOoJtTTjCCOALLUZnoO9ObMiwPrGn9Hg.', 'student', NULL, '19rxRzrrFB', '2025-05-11 01:28:08', '2025-05-11 01:28:08', NULL),
(24, 'Student 022', 'student_022', 'student022@school.edu', '2025-05-11 01:28:09', '$2y$12$CGkphN2.IIj84rYC105xy.r1pHxhuYIZEdJwj8QlI2stRwrVyN9sC', 'student', NULL, 'NpKc5e48vn', '2025-05-11 01:28:09', '2025-05-11 01:28:09', NULL),
(25, 'Student 023', 'student_023', 'student023@school.edu', '2025-05-11 01:28:09', '$2y$12$3nRoWEgF80BZXuZD2dUmveHS8bxCKqgJY0VRhJ5uIfXNe3ds3D8ai', 'student', NULL, 'YUeq3B64j1', '2025-05-11 01:28:09', '2025-05-11 01:28:09', NULL),
(26, 'Student 024', 'student_024', 'student024@school.edu', '2025-05-11 01:28:10', '$2y$12$7.xamtqHD/LkFBEh9NyXvO5pQ8tCpejrdI1PgaMQZfY8xUo9xafX2', 'student', NULL, 'J2rakjOqWH', '2025-05-11 01:28:10', '2025-05-11 01:28:10', NULL),
(27, 'Student 025', 'student_025', 'student025@school.edu', '2025-05-11 01:28:11', '$2y$12$d8VG8rG5OLqc9OiYFU403.JpnIz4q05f1zZIEBT00IcG2ZHqdt9Zy', 'student', NULL, 'IvRjvYhpXb', '2025-05-11 01:28:11', '2025-05-11 01:28:11', NULL),
(28, 'Student 026', 'student_026', 'student026@school.edu', '2025-05-11 01:28:11', '$2y$12$j4YkfFTuB3Wxx9h5DoRhdOc3Behfq5nDJnyRTCtkaR.tA40oiAdVu', 'student', NULL, 'tidvwPSsMd', '2025-05-11 01:28:11', '2025-05-11 01:28:11', NULL),
(29, 'Student 027', 'student_027', 'student027@school.edu', '2025-05-11 01:28:12', '$2y$12$7Q6CV/pKSnoMjTSJdotXg.V/5rET9BTGA5gsIGIPy8bFw6Tg0xpnS', 'student', NULL, 'zbcxrkgeal', '2025-05-11 01:28:12', '2025-05-11 01:28:12', NULL),
(30, 'Student 028', 'student_028', 'student028@school.edu', '2025-05-11 01:28:12', '$2y$12$CEA3O9QG2Gge.1q8j217AemB6YfOpeVV9LV7Q0jGTZMcA./N0BYeS', 'student', NULL, 'MHPvXX0HkD', '2025-05-11 01:28:12', '2025-05-11 01:28:12', NULL),
(31, 'Student 029', 'student_029', 'student029@school.edu', '2025-05-11 01:28:13', '$2y$12$Z7tf.uQxeRrDiGsiMvTUDOj5ARR25WlucTdLiWkjb0oB7oK.HFbMG', 'student', NULL, 'Mjmmh3PFR7', '2025-05-11 01:28:13', '2025-05-11 01:28:13', NULL),
(32, 'Student 030', 'student_030', 'student030@school.edu', '2025-05-11 01:28:13', '$2y$12$.NlYqnaCLz3yyZH7OJtoEOvWfuhU9NfcL2k.1UCFX5JMs5Bl1bQf.', 'student', NULL, 'LA6XXV73U2', '2025-05-11 01:28:13', '2025-05-11 01:28:13', NULL),
(33, 'Student 031', 'student_031', 'student031@school.edu', '2025-05-11 01:28:14', '$2y$12$s.o7JgxnwyEXNwGS1N.N9OavepMweVcky5kYO9B/jll4FspUKX4lC', 'student', NULL, 'oQf8yL29LS', '2025-05-11 01:28:14', '2025-05-11 01:28:14', NULL),
(34, 'Student 032', 'student_032', 'student032@school.edu', '2025-05-11 01:28:14', '$2y$12$jvMl7wkMdY9i/TtROdROMOQtyV51uuMzCwakVtBU9naUYQFYdiJoq', 'student', NULL, 'qJyW8GYSry', '2025-05-11 01:28:14', '2025-05-11 01:28:14', NULL),
(35, 'Student 033', 'student_033', 'student033@school.edu', '2025-05-11 01:28:15', '$2y$12$N5unHFO4EJxvNQ5Ojaa3vOF1ZL1kcmtJ5cHLb9Bsp67E56pmnir7i', 'student', NULL, 'MZ5Pep3bUd', '2025-05-11 01:28:15', '2025-05-11 01:28:15', NULL),
(36, 'Student 034', 'student_034', 'student034@school.edu', '2025-05-11 01:28:15', '$2y$12$tkt7xABNEEV9cwoDXmfgx.D/.rf3I7m3uQJI7kPff9ym.FevV8EGK', 'student', NULL, '9XEKL7dCYe', '2025-05-11 01:28:15', '2025-05-11 01:28:15', NULL),
(37, 'Student 035', 'student_035', 'student035@school.edu', '2025-05-11 01:28:16', '$2y$12$VejRrkoUewKipoAPpcHs7e3B9sQrXQDnWjNecWe7Mv4sc/PNVJo4e', 'student', NULL, 'Bcq8Nmxj7H', '2025-05-11 01:28:16', '2025-05-11 01:28:16', NULL),
(38, 'Student 036', 'student_036', 'student036@school.edu', '2025-05-11 01:28:16', '$2y$12$XVRRHEgqOek2UvVHZlcsXeX.PPR.tmE7SVJa4YHwXpbKNcNUp6x46', 'student', NULL, '4KcfYFNlLV', '2025-05-11 01:28:16', '2025-05-11 01:28:16', NULL),
(39, 'Student 037', 'student_037', 'student037@school.edu', '2025-05-11 01:28:17', '$2y$12$k4q6wirRQv2UymaZgsKqxOd3vJBb8nIWlAR2DgA0kKdOKaj12Xr2S', 'student', NULL, 'S4zQZlTNp9', '2025-05-11 01:28:17', '2025-05-11 01:28:17', NULL),
(40, 'Student 038', 'student_038', 'student038@school.edu', '2025-05-11 01:28:17', '$2y$12$PuZ9E0geKBZ0JW2sQoFQTeIxuPxaenKSe1QX7qNrWQnVfi.GHwklS', 'student', NULL, 'UlDvIB4HbV', '2025-05-11 01:28:17', '2025-05-11 01:28:17', NULL),
(41, 'Student 039', 'student_039', 'student039@school.edu', '2025-05-11 01:28:18', '$2y$12$pyev16Q9KCmqO0jJrA65T.qMXljJWXN.DDSf1ILZSDRrv.JtQAfQO', 'student', NULL, '4DrVg88Jnj', '2025-05-11 01:28:18', '2025-05-11 01:28:18', NULL),
(42, 'Student 040', 'student_040', 'student040@school.edu', '2025-05-11 01:28:18', '$2y$12$nMNqUHmhhqGRfwXY22tFAOiHjReGFO0WFufFF4W2YXObH7DnDaaYW', 'student', NULL, 'RdPOl0GJQK', '2025-05-11 01:28:18', '2025-05-11 01:28:18', NULL),
(43, 'Student 041', 'student_041', 'student041@school.edu', '2025-05-11 01:28:19', '$2y$12$VVxq8iujDUM83tZV4dWid.B4IoEpnrPCIFE40SymkSU0bD20OqYiG', 'student', NULL, 'hZ8Ads27cW', '2025-05-11 01:28:19', '2025-05-11 01:28:19', NULL),
(44, 'Student 042', 'student_042', 'student042@school.edu', '2025-05-11 01:28:19', '$2y$12$LZU4VFuWJuSG4eH2k0gXD.qR7Ldj6hWr0wCWos6ONz83xqRet6/7W', 'student', NULL, 'zylWrVVUMz', '2025-05-11 01:28:19', '2025-05-11 01:28:19', NULL),
(45, 'Student 043', 'student_043', 'student043@school.edu', '2025-05-11 01:28:20', '$2y$12$UQwB5ODS7mwRS7jfDCOrhOnH.CtAsNROZzuLi7oo3YIppV1A6t8IO', 'student', NULL, 'DJL5ecS06k', '2025-05-11 01:28:20', '2025-05-11 01:28:20', NULL),
(46, 'Student 044', 'student_044', 'student044@school.edu', '2025-05-11 01:28:20', '$2y$12$ve79XVdlWPt1t42k/JMYSeFhvayMva1YVL2JiQvR.9EOy4fcIHfdi', 'student', NULL, '1jZoCIDChJ', '2025-05-11 01:28:20', '2025-05-11 01:28:20', NULL),
(47, 'Student 045', 'student_045', 'student045@school.edu', '2025-05-11 01:28:21', '$2y$12$NjzsFMrQkL4kcbJxZabr/.gRR6OKASJKIKEYep0oosaODVPLYM7kC', 'student', NULL, '5VDTjYHr1kDoasuLUerARYs2VpEOfYfctZvSw2Qoa0H5zQzxgbtUntqS4EiK', '2025-05-11 01:28:21', '2025-05-11 01:28:21', NULL),
(48, 'Student 046', 'student_046', 'student046@school.edu', '2025-05-11 01:28:21', '$2y$12$/VljDzg2HVhMZkV2OUrDF.QzgwacyvsGZ2ZA9hyuUMxGhC7pl25Ra', 'student', NULL, 'RRgA7jtCz6', '2025-05-11 01:28:21', '2025-05-11 01:28:21', NULL),
(49, 'Student 047', 'student_047', 'student047@school.edu', '2025-05-11 01:28:21', '$2y$12$i.3va2eqDRD6pN6QM35bl.8XsXsk6TFJ1dW4n449f/pHXo1xf802G', 'student', NULL, 'XcGxLVQdYz', '2025-05-11 01:28:21', '2025-05-11 01:28:21', NULL),
(50, 'Student 048', 'student_048', 'student048@school.edu', '2025-05-11 01:28:22', '$2y$12$G/axM6k2dnKV8sJ2LZe2t.lgkKmLTsSw8Ug2Q.JQGGMffiQ3XIb1a', 'student', NULL, '1Co24EOEuE', '2025-05-11 01:28:22', '2025-05-11 01:28:22', NULL),
(51, 'Student 049', 'student_049', 'student049@school.edu', '2025-05-11 01:28:22', '$2y$12$Io.Ni.Iht6M..nRRjlPFfOqeGR/zxrwnD.CQmxQUFTG9Zuar9/boS', 'student', NULL, 'HIEgRjVf98', '2025-05-11 01:28:22', '2025-05-11 01:28:22', NULL),
(52, 'Student 050', 'student_050', 'student050@school.edu', '2025-05-11 01:28:23', '$2y$12$v4D/hsNpqG5LNCCmSoY9q.Mcp0JX.oqT/K8yWwE7a0R5/1gRNvq.6', 'student', NULL, 'uEEIvB3xAs', '2025-05-11 01:28:23', '2025-05-11 01:28:23', NULL),
(53, 'HOD Mathematics', 'mathematics_hod', 'mathematics.hod@school.edu', '2025-05-11 01:28:24', '$2y$12$lBXnierKltTBMfvAQfpPcut2Csgx6JNeGJuM/maWX67cDujebXr0y', 'hod', NULL, 'DqS9xKTjaRn3xS7PW0PiPcJu8hemb0kGi68vKku9xszTXoEO7MCh1oHXNvSg', '2025-05-11 01:28:24', '2025-05-11 01:28:24', 1),
(54, 'Mathematics Teacher', 'mathematics_teacher', 'mathematics.teacher@school.edu', '2025-05-11 01:28:24', '$2y$12$fxOBXgR4Qf4LMtH1ZCkGce3HoSq0u78sII36/yQQdiWHVaUb9Groy', 'teacher', NULL, 'WGzbPX2m47SM0Sljsb1YPkXH9GmGnNZ6xyTWTAUFfDPFZUmg35WEltWz2H25', '2025-05-11 01:28:24', '2025-05-11 01:28:24', 1),
(55, 'HOD Sciences', 'sciences_hod', 'sciences.hod@school.edu', '2025-05-11 01:28:24', '$2y$12$rawRH5XdeRyHdpNWea3qEejGvklRy4QZ56tyetHzeqrlmADiHZz.W', 'hod', NULL, 'nOoI4o7pS1', '2025-05-11 01:28:24', '2025-05-11 01:28:24', 2),
(56, 'Physics Teacher', 'physics_teacher', 'physics.teacher@school.edu', '2025-05-11 01:28:25', '$2y$12$Jek4WEK.h4iKNVrF1ZipBe/7z/P3D2rzezJLHIcFisNJ6TDyafDr6', 'teacher', NULL, 'N5sal1r8Yw', '2025-05-11 01:28:25', '2025-05-11 01:28:25', 2),
(57, 'HOD Languages', 'languages_hod', 'languages.hod@school.edu', '2025-05-11 01:28:25', '$2y$12$QV4WTtmf0mEXcDGLLh/A7.78Xj4lVpch01Wn2IVB0bCt3EYRKbiP2', 'hod', NULL, 'fVDerRLY5GurEDYic0DwLFwHnyrW8pqhlgS1DzqJkwdzFR0jPrbv02en26lG', '2025-05-11 01:28:25', '2025-05-11 01:28:25', 3),
(58, 'English Teacher', 'english_teacher', 'english.teacher@school.edu', '2025-05-11 01:28:26', '$2y$12$7d5xPHjpeuL6OzFBXOOms.aLHh9OE0I..JdXMnRgtIHDEL1U9x93W', 'teacher', NULL, 'pZl8QzM4L53JYAXvZl1N57P3EGsMeGWFVR3HC5tYdEoRYDVAvkXcagRMjfC1', '2025-05-11 01:28:26', '2025-05-11 01:28:26', 3),
(59, 'Shona Teacher', 'shona_teacher', 'shona.teacher@school.edu', '2025-05-11 01:28:26', '$2y$12$jfy.U9KZRNmL/VWLxl0bkul3NXVRrF41oeHVmCWBPfIzybKZkDMnC', 'teacher', NULL, 'o0a5B9tz9duJYz3JMVfaIEWYgRQX4aIKe6RbibOC8ODLMjoxXVClqSVShkdV', '2025-05-11 01:28:26', '2025-05-11 01:28:26', 3),
(60, 'HOD Humanities', 'humanities_hod', 'humanities.hod@school.edu', '2025-05-11 01:28:27', '$2y$12$DXFwetVaUzPacMwFHpmfH.NoVcQFNGzf9O5S4FMNeLH6TiTVWX8l6', 'hod', NULL, 'U96nzrOSOVPjTMf9rAEAHIa9CfSH2YrA9rtU3jiV5AOCpBwEsc9fajMIb25w', '2025-05-11 01:28:27', '2025-05-11 01:28:27', 4),
(61, 'History Teacher', 'history_teacher', 'history.teacher@school.edu', '2025-05-11 01:28:27', '$2y$12$EtK231RZmUMPECfhAU/LoerB3JSYVOQDeQNgh1d1CEYBKzc86jNn6', 'teacher', NULL, 'lPAl0lmU6xfAuuspXSdrtKISEVCS4wgpy1hcS4A8gZpYB7jn00aPVLbae4ha', '2025-05-11 01:28:27', '2025-05-11 01:28:27', 4),
(62, 'Kufa Joshua', 'jkufa', 'kufaj@school.edu', NULL, '$2y$12$NVOzGWGqDkWgQJJ0yeUjmeaCct3yCzHzTk9O62Y9yV1xzPV/PK312', 'teacher', NULL, NULL, '2025-05-20 11:08:35', '2025-05-29 09:52:35', NULL),
(68, 'LOlli', 'lll', 'loll@school.edu', NULL, '$2y$12$NJ2QvZ5Y6A8K1UMKSs.WS.osNeSF0qhrkuVpsZyGNDpx7SK35meI6', 'student', NULL, NULL, '2025-05-30 20:00:05', '2025-05-30 20:00:05', NULL),
(69, 'paul', 'paul', 'paul@school.edu', NULL, '$2y$12$WE3uV745yq3nBXFoytDHveAiP0CyaJFdhwOh09I4iHVM9wahSyI3G', 'student', NULL, NULL, '2025-05-30 21:29:43', '2025-06-01 11:00:13', NULL),
(70, 'lo', 'lo', 'lo@gmail.com', NULL, '$2y$12$wUk80cxQ2RgnFIANehvpSODJhLJ6rZXKlu/KNOhYDJSaAq0omcMJu', 'teacher', 2, NULL, '2025-05-30 22:09:03', '2025-05-30 22:09:03', 2),
(71, 'porzingis', 'poz', 'poz@school.edu', NULL, '$2y$12$im2j0cWkuoaG60Hj5plpVOA5j3cjYBeGT/6mMoFDkb2kP3SP7vyGG', 'teacher', 1, NULL, '2025-05-31 06:16:23', '2025-05-31 06:16:23', 1),
(72, 'rose', 'rose', 'rose@school.edu', NULL, '$2y$12$a3xFS4EOLeW8MJiUph.9POc5F3Z.rOX4p4.O0CLDiMwiMeoDxlOb6', 'student', NULL, NULL, '2025-06-01 11:17:31', '2025-06-01 11:17:31', NULL),
(73, 'jack', 'jack', 'jack@school.edu', NULL, '$2y$12$G0pCMJWZHYrRLQ9vGDUuRumR9HVemj.PflK8nRaHDHep6vBtlkOv2', 'student', NULL, NULL, '2025-06-01 11:18:34', '2025-06-01 11:18:34', NULL),
(74, 'dean', 'dean', 'dean@school.edu', NULL, '$2y$12$EpszXDN3HWEyH1QthrYeBexGKJ1RvXj02YylMPDpV3eQ4NbZ6ABXm', 'student', NULL, NULL, '2025-06-01 11:19:43', '2025-06-01 11:19:43', NULL),
(75, 'red', 'red', 'red@school.edu', NULL, '$2y$12$O82zpVlFlhELDByHh8Iqz.5cYTpcmzDKSloYODQLFiNYMqZrchrtu', 'student', NULL, NULL, '2025-06-01 11:22:19', '2025-06-01 11:22:19', NULL),
(76, 'Kennias Muumbe', 'kenniasmuumbe', 'kennias@school.edu', NULL, '$2y$12$NA1UuvFuO0js53LyJLN4JOHxUnc6v2a6ccpimzHDOUIiE9WZv2l7q', 'student', NULL, NULL, '2025-06-01 16:48:18', '2025-06-03 07:12:43', NULL),
(77, 'Paul Masimba', 'paulm', 'paulmasimba@school.edu', NULL, '$2y$12$u4Rp48pO5WZsPjUIwYI6Lu0u4cfcKSYC.WpQScgZyNtBavEr7/T02', 'student', NULL, NULL, '2025-06-02 07:58:24', '2025-06-02 07:58:24', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classes_grade_id_foreign` (`grade_id`);

--
-- Indexes for table `class_subject`
--
ALTER TABLE `class_subject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_subject_class_id_foreign` (`class_id`),
  ADD KEY `class_subject_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluations_student_id_foreign` (`student_id`),
  ADD KEY `evaluations_evaluator_teacher_id_foreign` (`evaluator_teacher_id`),
  ADD KEY `evaluations_teacher_id_foreign` (`teacher_id`),
  ADD KEY `evaluations_subject_id_foreign` (`subject_id`),
  ADD KEY `evaluations_user_id_foreign` (`user_id`),
  ADD KEY `evaluations_class_id_foreign` (`class_id`),
  ADD KEY `fk_evaluations_evaluation_cycle_id` (`evaluation_cycle_id`);

--
-- Indexes for table `evaluation_cycles`
--
ALTER TABLE `evaluation_cycles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evaluation_cycles_name_unique` (`name`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hods`
--
ALTER TABLE `hods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hods_user_id_foreign` (`user_id`),
  ADD KEY `hods_department_id_foreign` (`department_id`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `students_user_id_foreign` (`user_id`);

--
-- Indexes for table `student_class`
--
ALTER TABLE `student_class`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_class_student_id_class_id_unique` (`student_id`,`class_id`),
  ADD KEY `student_class_class_id_foreign` (`class_id`);

--
-- Indexes for table `student_subject`
--
ALTER TABLE `student_subject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_subject_student_id_subject_id_class_id_unique` (`student_id`,`subject_id`,`class_id`),
  ADD KEY `student_subject_subject_id_foreign` (`subject_id`),
  ADD KEY `student_subject_class_id_foreign` (`class_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subjects_department_id_foreign` (`department_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teachers_user_id_foreign` (`user_id`),
  ADD KEY `teachers_subject_id_foreign` (`subject_id`),
  ADD KEY `teachers_department_id_foreign` (`department_id`);

--
-- Indexes for table `teacher_class`
--
ALTER TABLE `teacher_class`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_class_teacher_id_class_id_subject_id_unique` (`teacher_id`,`class_id`,`subject_id`),
  ADD KEY `teacher_class_class_id_foreign` (`class_id`),
  ADD KEY `teacher_class_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_subject_id_foreign` (`subject_id`),
  ADD KEY `users_department_id_foreign` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `class_subject`
--
ALTER TABLE `class_subject`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=471;

--
-- AUTO_INCREMENT for table `evaluation_cycles`
--
ALTER TABLE `evaluation_cycles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hods`
--
ALTER TABLE `hods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `student_class`
--
ALTER TABLE `student_class`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `student_subject`
--
ALTER TABLE `student_subject`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `teacher_class`
--
ALTER TABLE `teacher_class`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`);

--
-- Constraints for table `class_subject`
--
ALTER TABLE `class_subject`
  ADD CONSTRAINT `class_subject_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subject_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_evaluator_teacher_id_foreign` FOREIGN KEY (`evaluator_teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_evaluations_evaluation_cycle_id` FOREIGN KEY (`evaluation_cycle_id`) REFERENCES `evaluation_cycles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hods`
--
ALTER TABLE `hods`
  ADD CONSTRAINT `hods_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hods_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `student_class`
--
ALTER TABLE `student_class`
  ADD CONSTRAINT `student_class_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_class_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_subject`
--
ALTER TABLE `student_subject`
  ADD CONSTRAINT `student_subject_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_subject_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_subject_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teachers_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_class`
--
ALTER TABLE `teacher_class`
  ADD CONSTRAINT `teacher_class_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `teacher_class_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `teacher_class_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
