-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2025 at 10:07 PM
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
-- Database: `career`
--

-- --------------------------------------------------------

--
-- Table structure for table `abilities`
--

CREATE TABLE `abilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cv_id` bigint(20) UNSIGNED NOT NULL,
  `abilities_name` varchar(255) NOT NULL,
  `level` enum('beginner','intermediate','advanced','expert') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `badge_name` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `badge_icon_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certificate_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_educations`
--

CREATE TABLE `certificate_educations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certificate_id` bigint(20) UNSIGNED NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cvs`
--

CREATE TABLE `cvs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `resume` text NOT NULL,
  `hobbies` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cvs`
--

INSERT INTO `cvs` (`id`, `user_id`, `resume`, `hobbies`, `created_at`, `updated_at`) VALUES
(1, 2, 'Test kullanıcısının CV\'si', 'Yazılım geliştirme, kitap okuma', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(2, 3, 'Demo kullanıcısının CV\'si', 'Müzik, spor', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(3, 6, 'Zeynep Yılmaz CV\'si', 'Genel hobiler', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(4, 7, 'Emir Demir CV\'si', 'Genel hobiler', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(5, 8, 'Elif Çelik CV\'si', 'Genel hobiler', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(6, 9, 'Yusuf Şahin CV\'si', 'Genel hobiler', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(7, 10, 'Fatma Aydın CV\'si', 'Genel hobiler', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(8, 11, 'Ahmet Kaya CV\'si', 'Genel hobiler', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(9, 12, 'Ayşe Özkan CV\'si', 'Genel hobiler', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(10, 13, 'Mehmet Yıldız CV\'si', 'Genel hobiler', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(11, 14, 'Selin Arslan CV\'si', 'Genel hobiler', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(12, 15, 'Can Doğan CV\'si', 'Genel hobiler', '2025-10-12 17:05:33', '2025-10-12 17:05:33');

-- --------------------------------------------------------

--
-- Table structure for table `educations`
--

CREATE TABLE `educations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cv_id` bigint(20) UNSIGNED NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `degree` varchar(50) NOT NULL,
  `field_of_study` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `experiences`
--

CREATE TABLE `experiences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cv_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `experiences`
--

INSERT INTO `experiences` (`id`, `cv_id`, `company_name`, `position`, `start_date`, `end_date`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'TechCorp A.Ş.', 'Yazılım Geliştirici', '2022-01-15', '2023-06-30', 'Web uygulamaları geliştirme ve bakım süreçlerinde aktif rol aldım. Laravel ve Vue.js teknolojileri kullandım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(2, 1, 'StartupXYZ', 'Full Stack Developer', '2023-07-01', NULL, 'E-ticaret platformu geliştirme projesinde yer aldım. React, Node.js ve MongoDB kullandım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(3, 2, 'Digital Agency', 'Frontend Developer', '2021-03-01', '2022-12-31', 'Responsive web tasarımları ve kullanıcı arayüzü geliştirme konularında çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(4, 3, 'Yazılım Şirketi A', 'Junior Developer', '2023-01-01', NULL, 'Yazılım geliştirme süreçlerinde yer aldım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(5, 3, 'Startup C', 'DevOps Engineer', '2023-03-01', NULL, 'Bulut altyapısı ve CI/CD süreçleri üzerinde çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(6, 4, 'Yazılım Şirketi A', 'Junior Developer', '2023-01-01', NULL, 'Yazılım geliştirme süreçlerinde yer aldım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(7, 4, 'Startup C', 'DevOps Engineer', '2023-03-01', NULL, 'Bulut altyapısı ve CI/CD süreçleri üzerinde çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(8, 5, 'Teknoloji B', 'Backend Developer', '2022-06-01', '2023-05-31', 'API geliştirme ve veritabanı yönetimi konularında çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(9, 5, 'Startup C', 'DevOps Engineer', '2023-03-01', NULL, 'Bulut altyapısı ve CI/CD süreçleri üzerinde çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(10, 6, 'Startup C', 'DevOps Engineer', '2023-03-01', NULL, 'Bulut altyapısı ve CI/CD süreçleri üzerinde çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(11, 7, 'Yazılım Şirketi A', 'Junior Developer', '2023-01-01', NULL, 'Yazılım geliştirme süreçlerinde yer aldım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(12, 7, 'Teknoloji B', 'Backend Developer', '2022-06-01', '2023-05-31', 'API geliştirme ve veritabanı yönetimi konularında çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(13, 8, 'Yazılım Şirketi A', 'Junior Developer', '2023-01-01', NULL, 'Yazılım geliştirme süreçlerinde yer aldım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(14, 8, 'Teknoloji B', 'Backend Developer', '2022-06-01', '2023-05-31', 'API geliştirme ve veritabanı yönetimi konularında çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(15, 9, 'Yazılım Şirketi A', 'Junior Developer', '2023-01-01', NULL, 'Yazılım geliştirme süreçlerinde yer aldım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(16, 10, 'Teknoloji B', 'Backend Developer', '2022-06-01', '2023-05-31', 'API geliştirme ve veritabanı yönetimi konularında çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(17, 11, 'Startup C', 'DevOps Engineer', '2023-03-01', NULL, 'Bulut altyapısı ve CI/CD süreçleri üzerinde çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(18, 12, 'Yazılım Şirketi A', 'Junior Developer', '2023-01-01', NULL, 'Yazılım geliştirme süreçlerinde yer aldım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(19, 12, 'Teknoloji B', 'Backend Developer', '2022-06-01', '2023-05-31', 'API geliştirme ve veritabanı yönetimi konularında çalıştım.', '2025-10-12 17:05:33', '2025-10-12 17:05:33');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
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

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cv_id` bigint(20) UNSIGNED NOT NULL,
  `language_name` varchar(255) NOT NULL,
  `level` enum('basic','conversational','fluent','native') NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `location` varchar(255) DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `parent_id`, `location`, `city_id`) VALUES
(1, 0, 'ADANA', 1),
(2, 1, 'ALADAĞ', 1),
(3, 1, 'CEYHAN', 1),
(4, 1, 'ÇUKUROVA', 1),
(5, 1, 'FEKE', 1),
(6, 1, 'İMAMOĞLU', 1),
(7, 1, 'KARAİSALI', 1),
(8, 1, 'KARATAŞ', 1),
(9, 1, 'KOZAN', 1),
(10, 1, 'POZANTI', 1),
(11, 1, 'SAİMBEYLİ', 1),
(12, 1, 'SARIÇAM', 1),
(13, 1, 'SEYHAN', 1),
(14, 1, 'TUFANBEYLİ', 1),
(15, 1, 'YUMURTALIK', 1),
(16, 1, 'YÜREĞİR', 1),
(17, 0, 'ADIYAMAN', 2),
(18, 1, 'BESNİ', 2),
(19, 1, 'ÇELİKHAN', 2),
(20, 1, 'GERGER', 2),
(21, 1, 'GÖLBAŞI', 2),
(22, 1, 'KAHTA', 2),
(23, 1, 'MERKEZ', 2),
(24, 1, 'SAMSAT', 2),
(25, 1, 'SİNCİK', 2),
(26, 1, 'TUT', 2),
(27, 0, 'AFYONKARAHİSAR', 3),
(28, 1, 'BAŞMAKÇI', 3),
(29, 1, 'BAYAT', 3),
(30, 1, 'BOLVADİN', 3),
(31, 1, 'ÇAY', 3),
(32, 1, 'ÇOBANLAR', 3),
(33, 1, 'DAZKIRI', 3),
(34, 1, 'DİNAR', 3),
(35, 1, 'EMİRDAĞ', 3),
(36, 1, 'EVCİLER', 3),
(37, 1, 'HOCALAR', 3),
(38, 1, 'İHSANİYE', 3),
(39, 1, 'İSCEHİSAR', 3),
(40, 1, 'KIZILÖREN', 3),
(41, 1, 'MERKEZ', 3),
(42, 1, 'SANDIKLI', 3),
(43, 1, 'SİNANPAŞA', 3),
(44, 1, 'ŞUHUT', 3),
(45, 1, 'SULTANDAĞI', 3),
(46, 0, 'AĞRI', 4),
(47, 1, 'DİYADİN', 4),
(48, 1, 'DOĞUBAYAZIT', 4),
(49, 1, 'ELEŞKİRT', 4),
(50, 1, 'HAMUR', 4),
(51, 1, 'MERKEZ', 4),
(52, 1, 'PATNOS', 4),
(53, 1, 'TAŞLIÇAY', 4),
(54, 1, 'TUTAK', 4),
(55, 0, 'AKSARAY', 68),
(56, 1, 'AĞAÇÖREN', 68),
(57, 1, 'ESKİL', 68),
(58, 1, 'GÜLAĞAÇ', 68),
(59, 1, 'GÜZELYURT', 68),
(60, 1, 'MERKEZ', 68),
(61, 1, 'ORTAKÖY', 68),
(62, 1, 'SARIYAHŞİ', 68),
(63, 1, 'SULTANHANI', 68),
(64, 0, 'AMASYA', 5),
(65, 1, 'GÖYNÜCEK', 5),
(66, 1, 'GÜMÜŞHACIKÖY', 5),
(67, 1, 'HAMAMÖZÜ', 5),
(68, 1, 'MERKEZ', 5),
(69, 1, 'MERZİFON', 5),
(70, 1, 'SULUOVA', 5),
(71, 1, 'TAŞOVA', 5),
(72, 0, 'ANKARA', 6),
(73, 1, 'AKYURT', 6),
(74, 1, 'ALTINDAĞ', 6),
(75, 1, 'AYAŞ', 6),
(76, 1, 'BALA', 6),
(77, 1, 'BEYPAZARI', 6),
(78, 1, 'ÇAMLIDERE', 6),
(79, 1, 'ÇANKAYA', 6),
(80, 1, 'ÇUBUK', 6),
(81, 1, 'ELMADAĞ', 6),
(82, 1, 'ETİMESGUT', 6),
(83, 1, 'EVREN', 6),
(84, 1, 'GÖLBAŞI', 6),
(85, 1, 'GÜDÜL', 6),
(86, 1, 'HAYMANA', 6),
(87, 1, 'KAHRAMANKAZAN', 6),
(88, 1, 'KALECİK', 6),
(89, 1, 'KEÇİÖREN', 6),
(90, 1, 'KIZILCAHAMAM', 6),
(91, 1, 'MAMAK', 6),
(92, 1, 'NALLIHAN', 6),
(93, 1, 'POLATLI', 6),
(94, 1, 'PURSAKLAR', 6),
(95, 1, 'ŞEREFLİKOÇHİSAR', 6),
(96, 1, 'SİNCAN', 6),
(97, 1, 'YENİMAHALLE', 6),
(98, 0, 'ANTALYA', 7),
(99, 1, 'AKSEKİ', 7),
(100, 1, 'AKSU', 7),
(101, 1, 'ALANYA', 7),
(102, 1, 'DEMRE', 7),
(103, 1, 'DÖŞEMEALTI', 7),
(104, 1, 'ELMALI', 7),
(105, 1, 'FİNİKE', 7),
(106, 1, 'GAZİPAŞA', 7),
(107, 1, 'GÜNDOĞMUŞ', 7),
(108, 1, 'İBRADI', 7),
(109, 1, 'KAŞ', 7),
(110, 1, 'KEMER', 7),
(111, 1, 'KEPEZ', 7),
(112, 1, 'KONYAALTI', 7),
(113, 1, 'KORKUTELİ', 7),
(114, 1, 'KUMLUCA', 7),
(115, 1, 'MANAVGAT', 7),
(116, 1, 'MURATPAŞA', 7),
(117, 1, 'SERİK', 7),
(118, 0, 'ARDAHAN', 75),
(119, 1, 'ÇILDIR', 75),
(120, 1, 'DAMAL', 75),
(121, 1, 'GÖLE', 75),
(122, 1, 'HANAK', 75),
(123, 1, 'MERKEZ', 75),
(124, 1, 'POSOF', 75),
(125, 0, 'ARTVİN', 8),
(126, 1, 'ARDANUÇ', 8),
(127, 1, 'ARHAVİ', 8),
(128, 1, 'BORÇKA', 8),
(129, 1, 'HOPA', 8),
(130, 1, 'KEMALPAŞA', 8),
(131, 1, 'MERKEZ', 8),
(132, 1, 'MURGUL', 8),
(133, 1, 'ŞAVŞAT', 8),
(134, 1, 'YUSUFELİ', 8),
(135, 0, 'AYDIN', 9),
(136, 1, 'BOZDOĞAN', 9),
(137, 1, 'BUHARKENT', 9),
(138, 1, 'ÇİNE', 9),
(139, 1, 'DİDİM', 9),
(140, 1, 'EFELER', 9),
(141, 1, 'GERMENCİK', 9),
(142, 1, 'İNCİRLİOVA', 9),
(143, 1, 'KARACASU', 9),
(144, 1, 'KARPUZLU', 9),
(145, 1, 'KOÇARLI', 9),
(146, 1, 'KÖŞK', 9),
(147, 1, 'KUŞADASI', 9),
(148, 1, 'KUYUCAK', 9),
(149, 1, 'NAZİLLİ', 9),
(150, 1, 'SÖKE', 9),
(151, 1, 'SULTANHİSAR', 9),
(152, 1, 'YENİPAZAR', 9),
(153, 0, 'BALIKESİR', 10),
(154, 1, 'ALTIEYLÜL', 10),
(155, 1, 'AYVALIK', 10),
(156, 1, 'BALYA', 10),
(157, 1, 'BANDIRMA', 10),
(158, 1, 'BİGADİÇ', 10),
(159, 1, 'BURHANİYE', 10),
(160, 1, 'DURSUNBEY', 10),
(161, 1, 'EDREMİT', 10),
(162, 1, 'ERDEK', 10),
(163, 1, 'GÖMEÇ', 10),
(164, 1, 'GÖNEN', 10),
(165, 1, 'HAVRAN', 10),
(166, 1, 'İVRİNDİ', 10),
(167, 1, 'KARESİ', 10),
(168, 1, 'KEPSUT', 10),
(169, 1, 'MANYAS', 10),
(170, 1, 'MARMARA', 10),
(171, 1, 'SAVAŞTEPE', 10),
(172, 1, 'SINDIRGI', 10),
(173, 1, 'SUSURLUK', 10),
(174, 0, 'BARTIN', 74),
(175, 1, 'AMASRA', 74),
(176, 1, 'KURUCAŞİLE', 74),
(177, 1, 'MERKEZ', 74),
(178, 1, 'ULUS', 74),
(179, 0, 'BATMAN', 72),
(180, 1, 'BEŞİRİ', 72),
(181, 1, 'GERCÜŞ', 72),
(182, 1, 'HASANKEYF', 72),
(183, 1, 'KOZLUK', 72),
(184, 1, 'MERKEZ', 72),
(185, 1, 'SASON', 72),
(186, 0, 'BAYBURT', 69),
(187, 1, 'AYDINTEPE', 69),
(188, 1, 'DEMİRÖZÜ', 69),
(189, 1, 'MERKEZ', 69),
(190, 0, 'BİLECİK', 11),
(191, 1, 'BOZÜYÜK', 11),
(192, 1, 'GÖLPAZARI', 11),
(193, 1, 'İNHİSAR', 11),
(194, 1, 'MERKEZ', 11),
(195, 1, 'OSMANELİ', 11),
(196, 1, 'PAZARYERİ', 11),
(197, 1, 'SÖĞÜT', 11),
(198, 1, 'YENİPAZAR', 11),
(199, 0, 'BİNGÖL', 12),
(200, 1, 'ADAKLI', 12),
(201, 1, 'GENÇ', 12),
(202, 1, 'KARLIOVA', 12),
(203, 1, 'KİĞI', 12),
(204, 1, 'MERKEZ', 12),
(205, 1, 'SOLHAN', 12),
(206, 1, 'YAYLADERE', 12),
(207, 1, 'YEDİSU', 12),
(208, 0, 'BİTLİS', 13),
(209, 1, 'ADİLCEVAZ', 13),
(210, 1, 'AHLAT', 13),
(211, 1, 'GÜROYMAK', 13),
(212, 1, 'HİZAN', 13),
(213, 1, 'MERKEZ', 13),
(214, 1, 'MUTKİ', 13),
(215, 1, 'TATVAN', 13),
(216, 0, 'BOLU', 14),
(217, 1, 'DÖRTDİVAN', 14),
(218, 1, 'GEREDE', 14),
(219, 1, 'GÖYNÜK', 14),
(220, 1, 'KIBRISCIK', 14),
(221, 1, 'MENGEN', 14),
(222, 1, 'MERKEZ', 14),
(223, 1, 'MUDURNU', 14),
(224, 1, 'SEBEN', 14),
(225, 1, 'YENİÇAĞA', 14),
(226, 0, 'BURDUR', 15),
(227, 1, 'AĞLASUN', 15),
(228, 1, 'ALTINYAYLA', 15),
(229, 1, 'BUCAK', 15),
(230, 1, 'ÇAVDIR', 15),
(231, 1, 'ÇELTİKÇİ', 15),
(232, 1, 'GÖLHİSAR', 15),
(233, 1, 'KARAMANLI', 15),
(234, 1, 'KEMER', 15),
(235, 1, 'MERKEZ', 15),
(236, 1, 'TEFENNİ', 15),
(237, 1, 'YEŞİLOVA', 15),
(238, 0, 'BURSA', 16),
(239, 1, 'BÜYÜKORHAN', 16),
(240, 1, 'GEMLİK', 16),
(241, 1, 'GÜRSU', 16),
(242, 1, 'HARMANCIK', 16),
(243, 1, 'İNEGÖL', 16),
(244, 1, 'İZNİK', 16),
(245, 1, 'KARACABEY', 16),
(246, 1, 'KELES', 16),
(247, 1, 'KESTEL', 16),
(248, 1, 'M.KEMALPAŞA', 16),
(249, 1, 'MUDANYA', 16),
(250, 1, 'NİLÜFER', 16),
(251, 1, 'ORHANELİ', 16),
(252, 1, 'ORHANGAZİ', 16),
(253, 1, 'OSMANGAZİ', 16),
(254, 1, 'YENİŞEHİR', 16),
(255, 1, 'YILDIRIM', 16),
(256, 0, 'ÇANAKKALE', 17),
(257, 1, 'AYVACIK', 17),
(258, 1, 'BAYRAMİÇ', 17),
(259, 1, 'BİGA', 17),
(260, 1, 'BOZCAADA', 17),
(261, 1, 'ÇAN', 17),
(262, 1, 'ECEABAT', 17),
(263, 1, 'EZİNE', 17),
(264, 1, 'GELİBOLU', 17),
(265, 1, 'GÖKÇEADA', 17),
(266, 1, 'LAPSEKİ', 17),
(267, 1, 'MERKEZ', 17),
(268, 1, 'YENİCE', 17),
(269, 0, 'ÇANKIRI', 18),
(270, 1, 'ATKARACALAR', 18),
(271, 1, 'BAYRAMÖREN', 18),
(272, 1, 'ÇERKEŞ', 18),
(273, 1, 'ELDİVAN', 18),
(274, 1, 'ILGAZ', 18),
(275, 1, 'KIZILIRMAK', 18),
(276, 1, 'KORGUN', 18),
(277, 1, 'KURŞUNLU', 18),
(278, 1, 'MERKEZ', 18),
(279, 1, 'ORTA', 18),
(280, 1, 'ŞABANÖZÜ', 18),
(281, 1, 'YAPRAKLI', 18),
(282, 0, 'ÇORUM', 19),
(283, 1, 'ALACA', 19),
(284, 1, 'BAYAT', 19),
(285, 1, 'BOĞAZKALE', 19),
(286, 1, 'DODURGA', 19),
(287, 1, 'İSKİLİP', 19),
(288, 1, 'KARGI', 19),
(289, 1, 'LAÇİN', 19),
(290, 1, 'MECİTÖZÜ', 19),
(291, 1, 'MERKEZ', 19),
(292, 1, 'OĞUZLAR', 19),
(293, 1, 'ORTAKÖY', 19),
(294, 1, 'OSMANCIK', 19),
(295, 1, 'SUNGURLU', 19),
(296, 1, 'UĞURLUDAĞ', 19),
(297, 0, 'DENİZLİ', 20),
(298, 1, 'ACIPAYAM', 20),
(299, 1, 'BABADAĞ', 20),
(300, 1, 'BAKLAN', 20),
(301, 1, 'BEKİLLİ', 20),
(302, 1, 'BEYAĞAÇ', 20),
(303, 1, 'BOZKURT', 20),
(304, 1, 'BULDAN', 20),
(305, 1, 'ÇAL', 20),
(306, 1, 'ÇAMELİ', 20),
(307, 1, 'ÇARDAK', 20),
(308, 1, 'ÇİVRİL', 20),
(309, 1, 'GÜNEY', 20),
(310, 1, 'HONAZ', 20),
(311, 1, 'KALE', 20),
(312, 1, 'MERKEZEFENDİ', 20),
(313, 1, 'PAMUKKALE', 20),
(314, 1, 'SARAYKÖY', 20),
(315, 1, 'SERİNHİSAR', 20),
(316, 1, 'TAVAS', 20),
(317, 0, 'DİYARBAKIR', 21),
(318, 1, 'BAĞLAR', 21),
(319, 1, 'BİSMİL', 21),
(320, 1, 'ÇERMİK', 21),
(321, 1, 'ÇINAR', 21),
(322, 1, 'ÇÜNGÜŞ', 21),
(323, 1, 'DİCLE', 21),
(324, 1, 'EĞİL', 21),
(325, 1, 'ERGANİ', 21),
(326, 1, 'HANİ', 21),
(327, 1, 'HAZRO', 21),
(328, 1, 'KAYAPINAR', 21),
(329, 1, 'KOCAKÖY', 21),
(330, 1, 'KULP', 21),
(331, 1, 'LİCE', 21),
(332, 1, 'SİLVAN', 21),
(333, 1, 'SUR', 21),
(334, 1, 'YENİŞEHİR', 21),
(335, 0, 'DÜZCE', 81),
(336, 1, 'AKÇAKOCA', 81),
(337, 1, 'ÇİLİMLİ', 81),
(338, 1, 'CUMAYERİ', 81),
(339, 1, 'GÖLYAKA', 81),
(340, 1, 'GÜMÜŞOVA', 81),
(341, 1, 'KAYNAŞLI', 81),
(342, 1, 'MERKEZ', 81),
(343, 1, 'YIĞILCA', 81),
(344, 0, 'EDİRNE', 22),
(345, 1, 'ENEZ', 22),
(346, 1, 'HAVSA', 22),
(347, 1, 'İPSALA', 22),
(348, 1, 'KEŞAN', 22),
(349, 1, 'LALAPAŞA', 22),
(350, 1, 'MERİÇ', 22),
(351, 1, 'MERKEZ', 22),
(352, 1, 'SÜLOĞLU', 22),
(353, 1, 'UZUNKÖPRÜ', 22),
(354, 0, 'ELAZIĞ', 23),
(355, 1, 'AĞIN', 23),
(356, 1, 'ALACAKAYA', 23),
(357, 1, 'ARICAK', 23),
(358, 1, 'BASKİL', 23),
(359, 1, 'KARAKOÇAN', 23),
(360, 1, 'KEBAN', 23),
(361, 1, 'KOVANCILAR', 23),
(362, 1, 'MADEN', 23),
(363, 1, 'MERKEZ', 23),
(364, 1, 'PALU', 23),
(365, 1, 'SİVRİCE', 23),
(366, 0, 'ERZİNCAN', 24),
(367, 1, 'ÇAYIRLI', 24),
(368, 1, 'İLİÇ', 24),
(369, 1, 'KEMAH', 24),
(370, 1, 'KEMALİYE', 24),
(371, 1, 'MERKEZ', 24),
(372, 1, 'OTLUKBELİ', 24),
(373, 1, 'REFAHİYE', 24),
(374, 1, 'TERCAN', 24),
(375, 1, 'ÜZÜMLÜ', 24),
(376, 0, 'ERZURUM', 25),
(377, 1, 'AŞKALE', 25),
(378, 1, 'AZİZİYE', 25),
(379, 1, 'ÇAT', 25),
(380, 1, 'HINIS', 25),
(381, 1, 'HORASAN', 25),
(382, 1, 'İSPİR', 25),
(383, 1, 'KARAÇOBAN', 25),
(384, 1, 'KARAYAZI', 25),
(385, 1, 'KÖPRÜKÖY', 25),
(386, 1, 'NARMAN', 25),
(387, 1, 'OLTU', 25),
(388, 1, 'OLUR', 25),
(389, 1, 'PALANDÖKEN', 25),
(390, 1, 'PASİNLER', 25),
(391, 1, 'PAZARYOLU', 25),
(392, 1, 'ŞENKAYA', 25),
(393, 1, 'TEKMAN', 25),
(394, 1, 'TORTUM', 25),
(395, 1, 'UZUNDERE', 25),
(396, 1, 'YAKUTİYE', 25),
(397, 0, 'ESKİŞEHİR', 26),
(398, 1, 'ALPU', 26),
(399, 1, 'BEYLİKOVA', 26),
(400, 1, 'ÇİFTELER', 26),
(401, 1, 'GÜNYÜZÜ', 26),
(402, 1, 'HAN', 26),
(403, 1, 'İNÖNÜ', 26),
(404, 1, 'MAHMUDİYE', 26),
(405, 1, 'MİHALGAZİ', 26),
(406, 1, 'MİHALIÇÇIK', 26),
(407, 1, 'ODUNPAZARI', 26),
(408, 1, 'SARICAKAYA', 26),
(409, 1, 'SEYİTGAZİ', 26),
(410, 1, 'SİVRİHİSAR', 26),
(411, 1, 'TEPEBAŞI', 26),
(412, 0, 'GAZİANTEP', 27),
(413, 1, 'ARABAN', 27),
(414, 1, 'İSLAHİYE', 27),
(415, 1, 'KARKAMIŞ', 27),
(416, 1, 'NİZİP', 27),
(417, 1, 'NURDAĞI', 27),
(418, 1, 'OĞUZELİ', 27),
(419, 1, 'ŞAHİNBEY', 27),
(420, 1, 'ŞEHİTKAMİL', 27),
(421, 1, 'YAVUZELİ', 27),
(422, 0, 'GİRESUN', 28),
(423, 1, 'ALUCRA', 28),
(424, 1, 'BULANCAK', 28),
(425, 1, 'ÇAMOLUK', 28),
(426, 1, 'ÇANAKÇI', 28),
(427, 1, 'DERELİ', 28),
(428, 1, 'DOĞANKENT', 28),
(429, 1, 'ESPİYE', 28),
(430, 1, 'EYNESİL', 28),
(431, 1, 'GÖRELE', 28),
(432, 1, 'GÜCE', 28),
(433, 1, 'KEŞAP', 28),
(434, 1, 'MERKEZ', 28),
(435, 1, 'PİRAZİZ', 28),
(436, 1, 'ŞEBİNKARAHİSAR', 28),
(437, 1, 'TİREBOLU', 28),
(438, 1, 'YAĞLIDERE', 28),
(439, 0, 'GÜMÜŞHANE', 29),
(440, 1, 'KELKİT', 29),
(441, 1, 'KÖSE', 29),
(442, 1, 'KÜRTÜN', 29),
(443, 1, 'MERKEZ', 29),
(444, 1, 'ŞİRAN', 29),
(445, 1, 'TORUL', 29),
(446, 0, 'HAKKARİ', 30),
(447, 1, 'ÇUKURCA', 30),
(448, 1, 'DERECİK', 30),
(449, 1, 'MERKEZ', 30),
(450, 1, 'ŞEMDİNLİ', 30),
(451, 1, 'YÜKSEKOVA', 30),
(452, 0, 'HATAY', 31),
(453, 1, 'ALTINÖZÜ', 31),
(454, 1, 'ANTAKYA', 31),
(455, 1, 'ARSUZ', 31),
(456, 1, 'BELEN', 31),
(457, 1, 'DEFNE', 31),
(458, 1, 'DÖRTYOL', 31),
(459, 1, 'ERZİN', 31),
(460, 1, 'HASSA', 31),
(461, 1, 'İSKENDERUN', 31),
(462, 1, 'KIRIKHAN', 31),
(463, 1, 'KUMLU', 31),
(464, 1, 'PAYAS', 31),
(465, 1, 'REYHANLI', 31),
(466, 1, 'SAMANDAĞ', 31),
(467, 1, 'YAYLADAĞI', 31),
(468, 0, 'IĞDIR', 76),
(469, 1, 'ARALIK', 76),
(470, 1, 'KARAKOYUNLU', 76),
(471, 1, 'MERKEZ', 76),
(472, 1, 'TUZLUCA', 76),
(473, 0, 'ISPARTA', 32),
(474, 1, 'AKSU', 32),
(475, 1, 'ATABEY', 32),
(476, 1, 'EĞİRDİR', 32),
(477, 1, 'GELENDOST', 32),
(478, 1, 'GÖNEN', 32),
(479, 1, 'KEÇİBORLU', 32),
(480, 1, 'MERKEZ', 32),
(481, 1, 'ŞARKİKARAAĞAÇ', 32),
(482, 1, 'SENİRKENT', 32),
(483, 1, 'SÜTÇÜLER', 32),
(484, 1, 'ULUBORLU', 32),
(485, 1, 'YALVAÇ', 32),
(486, 1, 'YENİŞARBADEMLİ', 32),
(487, 0, 'İSTANBUL', 34),
(488, 1, 'ADALAR', 34),
(489, 1, 'ARNAVUTKÖY', 34),
(490, 1, 'ATAŞEHİR', 34),
(491, 1, 'AVCILAR', 34),
(492, 1, 'BAĞCILAR', 34),
(493, 1, 'BAHÇELİEVLER', 34),
(494, 1, 'BAKIRKÖY', 34),
(495, 1, 'BAŞAKŞEHİR', 34),
(496, 1, 'BAYRAMPAŞA', 34),
(497, 1, 'BEŞİKTAŞ', 34),
(498, 1, 'BEYKOZ', 34),
(499, 1, 'BEYLİKDÜZÜ', 34),
(500, 1, 'BEYOĞLU', 34),
(501, 1, 'BÜYÜKÇEKMECE', 34),
(502, 1, 'ÇATALCA', 34),
(503, 1, 'ÇEKMEKÖY', 34),
(504, 1, 'ESENLER', 34),
(505, 1, 'ESENYURT', 34),
(506, 1, 'EYÜPSULTAN', 34),
(507, 1, 'FATİH', 34),
(508, 1, 'GAZİOSMANPAŞA', 34),
(509, 1, 'GÜNGÖREN', 34),
(510, 1, 'KADIKÖY', 34),
(511, 1, 'KAĞITHANE', 34),
(512, 1, 'KARTAL', 34),
(513, 1, 'KÜÇÜKÇEKMECE', 34),
(514, 1, 'MALTEPE', 34),
(515, 1, 'PENDİK', 34),
(516, 1, 'SANCAKTEPE', 34),
(517, 1, 'SARIYER', 34),
(518, 1, 'ŞİLE', 34),
(519, 1, 'SİLİVRİ', 34),
(520, 1, 'ŞİŞLİ', 34),
(521, 1, 'SULTANBEYLİ', 34),
(522, 1, 'SULTANGAZİ', 34),
(523, 1, 'TUZLA', 34),
(524, 1, 'ÜMRANİYE', 34),
(525, 1, 'ÜSKÜDAR', 34),
(526, 1, 'ZEYTİNBURNU', 34),
(527, 0, 'İZMİR', 35),
(528, 1, 'ALİAĞA', 35),
(529, 1, 'BALÇOVA', 35),
(530, 1, 'BAYINDIR', 35),
(531, 1, 'BAYRAKLI', 35),
(532, 1, 'BERGAMA', 35),
(533, 1, 'BEYDAĞ', 35),
(534, 1, 'BORNOVA', 35),
(535, 1, 'BUCA', 35),
(536, 1, 'ÇEŞME', 35),
(537, 1, 'ÇİĞLİ', 35),
(538, 1, 'DİKİLİ', 35),
(539, 1, 'FOÇA', 35),
(540, 1, 'GAZİEMİR', 35),
(541, 1, 'GÜZELBAHÇE', 35),
(542, 1, 'KARABAĞLAR', 35),
(543, 1, 'KARABURUN', 35),
(544, 1, 'KARŞIYAKA', 35),
(545, 1, 'KEMALPAŞA', 35),
(546, 1, 'KINIK', 35),
(547, 1, 'KİRAZ', 35),
(548, 1, 'KONAK', 35),
(549, 1, 'MENDERES', 35),
(550, 1, 'MENEMEN', 35),
(551, 1, 'NARLIDERE', 35),
(552, 1, 'ÖDEMİŞ', 35),
(553, 1, 'SEFERİHİSAR', 35),
(554, 1, 'SELÇUK', 35),
(555, 1, 'TİRE', 35),
(556, 1, 'TORBALI', 35),
(557, 1, 'URLA', 35),
(558, 0, 'KAHRAMANMARAŞ', 46),
(559, 1, 'AFŞİN', 46),
(560, 1, 'ANDIRIN', 46),
(561, 1, 'ÇAĞLAYANCERİT', 46),
(562, 1, 'DULKADİROĞLU', 46),
(563, 1, 'EKİNÖZÜ', 46),
(564, 1, 'ELBİSTAN', 46),
(565, 1, 'GÖKSUN', 46),
(566, 1, 'NURHAK', 46),
(567, 1, 'ONİKİŞUBAT', 46),
(568, 1, 'PAZARCIK', 46),
(569, 1, 'TÜRKOĞLU', 46),
(570, 0, 'KARABÜK', 78),
(571, 1, 'EFLANİ', 78),
(572, 1, 'ESKİPAZAR', 78),
(573, 1, 'MERKEZ', 78),
(574, 1, 'OVACIK', 78),
(575, 1, 'SAFRANBOLU', 78),
(576, 1, 'YENİCE', 78),
(577, 0, 'KARAMAN', 70),
(578, 1, 'AYRANCI', 70),
(579, 1, 'BAŞYAYLA', 70),
(580, 1, 'ERMENEK', 70),
(581, 1, 'KAZIMKARABEKİR', 70),
(582, 1, 'MERKEZ', 70),
(583, 1, 'SARIVELİLER', 70),
(584, 0, 'KARS', 36),
(585, 1, 'AKYAKA', 36),
(586, 1, 'ARPAÇAY', 36),
(587, 1, 'DİGOR', 36),
(588, 1, 'KAĞIZMAN', 36),
(589, 1, 'MERKEZ', 36),
(590, 1, 'SARIKAMIŞ', 36),
(591, 1, 'SELİM', 36),
(592, 1, 'SUSUZ', 36),
(593, 0, 'KASTAMONU', 37),
(594, 1, 'ABANA', 37),
(595, 1, 'AĞLI', 37),
(596, 1, 'ARAÇ', 37),
(597, 1, 'AZDAVAY', 37),
(598, 1, 'BOZKURT', 37),
(599, 1, 'ÇATALZEYTİN', 37),
(600, 1, 'CİDE', 37),
(601, 1, 'DADAY', 37),
(602, 1, 'DEVREKANİ', 37),
(603, 1, 'DOĞANYURT', 37),
(604, 1, 'HANÖNÜ', 37),
(605, 1, 'İHSANGAZİ', 37),
(606, 1, 'İNEBOLU', 37),
(607, 1, 'KÜRE', 37),
(608, 1, 'MERKEZ', 37),
(609, 1, 'PINARBAŞI', 37),
(610, 1, 'ŞENPAZAR', 37),
(611, 1, 'SEYDİLER', 37),
(612, 1, 'TAŞKÖPRÜ', 37),
(613, 1, 'TOSYA', 37),
(614, 0, 'KAYSERİ', 38),
(615, 1, 'AKKIŞLA', 38),
(616, 1, 'BÜNYAN', 38),
(617, 1, 'DEVELİ', 38),
(618, 1, 'FELAHİYE', 38),
(619, 1, 'HACILAR', 38),
(620, 1, 'İNCESU', 38),
(621, 1, 'KOCASİNAN', 38),
(622, 1, 'MELİKGAZİ', 38),
(623, 1, 'ÖZVATAN', 38),
(624, 1, 'PINARBAŞI', 38),
(625, 1, 'SARIOĞLAN', 38),
(626, 1, 'SARIZ', 38),
(627, 1, 'TALAS', 38),
(628, 1, 'TOMARZA', 38),
(629, 1, 'YAHYALI', 38),
(630, 1, 'YEŞİLHİSAR', 38),
(631, 0, 'KİLİS', 79),
(632, 1, 'ELBEYLİ', 79),
(633, 1, 'MERKEZ', 79),
(634, 1, 'MUSABEYLİ', 79),
(635, 1, 'POLATELİ', 79),
(636, 0, 'KIRIKKALE', 71),
(637, 1, 'BAHŞİLİ', 71),
(638, 1, 'BALIŞEYH', 71),
(639, 1, 'ÇELEBİ', 71),
(640, 1, 'DELİCE', 71),
(641, 1, 'KARAKEÇİLİ', 71),
(642, 1, 'KESKİN', 71),
(643, 1, 'MERKEZ', 71),
(644, 1, 'SULAKYURT', 71),
(645, 1, 'YAHŞİHAN', 71),
(646, 0, 'KIRKLARELİ', 39),
(647, 1, 'BABAESKİ', 39),
(648, 1, 'DEMİRKÖY', 39),
(649, 1, 'KOFÇAZ', 39),
(650, 1, 'LÜLEBURGAZ', 39),
(651, 1, 'MERKEZ', 39),
(652, 1, 'PEHLİVANKÖY', 39),
(653, 1, 'PINARHİSAR', 39),
(654, 1, 'VİZE', 39),
(655, 0, 'KIRŞEHİR', 40),
(656, 1, 'AKÇAKENT', 40),
(657, 1, 'AKPINAR', 40),
(658, 1, 'BOZTEPE', 40),
(659, 1, 'ÇİÇEKDAĞI', 40),
(660, 1, 'KAMAN', 40),
(661, 1, 'MERKEZ', 40),
(662, 1, 'MUCUR', 40),
(663, 0, 'KOCAELİ', 41),
(664, 1, 'BAŞİSKELE', 41),
(665, 1, 'ÇAYIROVA', 41),
(666, 1, 'DARICA', 41),
(667, 1, 'DERİNCE', 41),
(668, 1, 'DİLOVASI', 41),
(669, 1, 'GEBZE', 41),
(670, 1, 'GÖLCÜK', 41),
(671, 1, 'İZMİT', 41),
(672, 1, 'KANDIRA', 41),
(673, 1, 'KARAMÜRSEL', 41),
(674, 1, 'KARTEPE', 41),
(675, 1, 'KÖRFEZ', 41),
(676, 0, 'KONYA', 42),
(677, 1, 'AHIRLI', 42),
(678, 1, 'AKÖREN', 42),
(679, 1, 'AKŞEHİR', 42),
(680, 1, 'ALTINEKİN', 42),
(681, 1, 'BEYŞEHİR', 42),
(682, 1, 'BOZKIR', 42),
(683, 1, 'ÇELTİK', 42),
(684, 1, 'CİHANBEYLİ', 42),
(685, 1, 'ÇUMRA', 42),
(686, 1, 'DERBENT', 42),
(687, 1, 'DEREBUCAK', 42),
(688, 1, 'DOĞANHİSAR', 42),
(689, 1, 'EMİRGAZİ', 42),
(690, 1, 'EREĞLİ', 42),
(691, 1, 'GÜNEYSINIR', 42),
(692, 1, 'HADİM', 42),
(693, 1, 'HALKAPINAR', 42),
(694, 1, 'HÜYÜK', 42),
(695, 1, 'ILGIN', 42),
(696, 1, 'KADINHANI', 42),
(697, 1, 'KARAPINAR', 42),
(698, 1, 'KARATAY', 42),
(699, 1, 'KULU', 42),
(700, 1, 'MERAM', 42),
(701, 1, 'SARAYÖNÜ', 42),
(702, 1, 'SELÇUKLU', 42),
(703, 1, 'SEYDİŞEHİR', 42),
(704, 1, 'TAŞKENT', 42),
(705, 1, 'TUZLUKÇU', 42),
(706, 1, 'YALIHÜYÜK', 42),
(707, 1, 'YUNAK', 42),
(708, 0, 'KÜTAHYA', 43),
(709, 1, 'ALTINTAŞ', 43),
(710, 1, 'ASLANAPA', 43),
(711, 1, 'ÇAVDARHİSAR', 43),
(712, 1, 'DOMANİÇ', 43),
(713, 1, 'DUMLUPINAR', 43),
(714, 1, 'EMET', 43),
(715, 1, 'GEDİZ', 43),
(716, 1, 'HİSARCIK', 43),
(717, 1, 'MERKEZ', 43),
(718, 1, 'PAZARLAR', 43),
(719, 1, 'ŞAPHANE', 43),
(720, 1, 'SİMAV', 43),
(721, 1, 'TAVŞANLI', 43),
(722, 0, 'MALATYA', 44),
(723, 1, 'AKÇADAĞ', 44),
(724, 1, 'ARAPGİR', 44),
(725, 1, 'ARGUVAN', 44),
(726, 1, 'BATTALGAZİ', 44),
(727, 1, 'DARENDE', 44),
(728, 1, 'DOĞANŞEHİR', 44),
(729, 1, 'DOĞANYOL', 44),
(730, 1, 'HEKİMHAN', 44),
(731, 1, 'KALE', 44),
(732, 1, 'KULUNCAK', 44),
(733, 1, 'PÜTÜRGE', 44),
(734, 1, 'YAZIHAN', 44),
(735, 1, 'YEŞİLYURT', 44),
(736, 0, 'MANİSA', 45),
(737, 1, 'AHMETLİ', 45),
(738, 1, 'AKHİSAR', 45),
(739, 1, 'ALAŞEHİR', 45),
(740, 1, 'DEMİRCİ', 45),
(741, 1, 'GÖLMARMARA', 45),
(742, 1, 'GÖRDES', 45),
(743, 1, 'KIRKAĞAÇ', 45),
(744, 1, 'KÖPRÜBAŞI', 45),
(745, 1, 'KULA', 45),
(746, 1, 'SALİHLİ', 45),
(747, 1, 'SARIGÖL', 45),
(748, 1, 'SARUHANLI', 45),
(749, 1, 'ŞEHZADELER', 45),
(750, 1, 'SELENDİ', 45),
(751, 1, 'SOMA', 45),
(752, 1, 'TURGUTLU', 45),
(753, 1, 'YUNUSEMRE', 45),
(754, 0, 'MARDİN', 47),
(755, 1, 'ARTUKLU', 47),
(756, 1, 'DARGEÇİT', 47),
(757, 1, 'DERİK', 47),
(758, 1, 'KIZILTEPE', 47),
(759, 1, 'MAZIDAĞI', 47),
(760, 1, 'MİDYAT', 47),
(761, 1, 'NUSAYBİN', 47),
(762, 1, 'ÖMERLİ', 47),
(763, 1, 'SAVUR', 47),
(764, 1, 'YEŞİLLİ', 47),
(765, 0, 'MERSİN', 33),
(766, 1, 'AKDENİZ', 33),
(767, 1, 'ANAMUR', 33),
(768, 1, 'AYDINCIK', 33),
(769, 1, 'BOZYAZI', 33),
(770, 1, 'ÇAMLIYAYLA', 33),
(771, 1, 'ERDEMLİ', 33),
(772, 1, 'GÜLNAR', 33),
(773, 1, 'MEZİTLİ', 33),
(774, 1, 'MUT', 33),
(775, 1, 'SİLİFKE', 33),
(776, 1, 'TARSUS', 33),
(777, 1, 'TOROSLAR', 33),
(778, 1, 'YENİŞEHİR', 33),
(779, 0, 'MUĞLA', 48),
(780, 1, 'BODRUM', 48),
(781, 1, 'DALAMAN', 48),
(782, 1, 'DATÇA', 48),
(783, 1, 'FETHİYE', 48),
(784, 1, 'KAVAKLIDERE', 48),
(785, 1, 'KÖYCEĞİZ', 48),
(786, 1, 'MARMARİS', 48),
(787, 1, 'MENTEŞE', 48),
(788, 1, 'MİLAS', 48),
(789, 1, 'ORTACA', 48),
(790, 1, 'SEYDİKEMER', 48),
(791, 1, 'ULA', 48),
(792, 1, 'YATAĞAN', 48),
(793, 0, 'MUŞ', 49),
(794, 1, 'BULANIK', 49),
(795, 1, 'HASKÖY', 49),
(796, 1, 'KORKUT', 49),
(797, 1, 'MALAZGİRT', 49),
(798, 1, 'MERKEZ', 49),
(799, 1, 'VARTO', 49),
(800, 0, 'NEVŞEHİR', 50),
(801, 1, 'ACIGÖL', 50),
(802, 1, 'AVANOS', 50),
(803, 1, 'DERİNKUYU', 50),
(804, 1, 'GÜLŞEHİR', 50),
(805, 1, 'HACIBEKTAŞ', 50),
(806, 1, 'KOZAKLI', 50),
(807, 1, 'MERKEZ', 50),
(808, 1, 'ÜRGÜP', 50),
(809, 0, 'NİĞDE', 51),
(810, 1, 'ALTUNHİSAR', 51),
(811, 1, 'BOR', 51),
(812, 1, 'ÇAMARDI', 51),
(813, 1, 'ÇİFTLİK', 51),
(814, 1, 'MERKEZ', 51),
(815, 1, 'ULUKIŞLA', 51),
(816, 0, 'ORDU', 52),
(817, 1, 'AKKUŞ', 52),
(818, 1, 'ALTINORDU', 52),
(819, 1, 'AYBASTI', 52),
(820, 1, 'ÇAMAŞ', 52),
(821, 1, 'ÇATALPINAR', 52),
(822, 1, 'ÇAYBAŞI', 52),
(823, 1, 'FATSA', 52),
(824, 1, 'GÖLKÖY', 52),
(825, 1, 'GÜLYALI', 52),
(826, 1, 'GÜRGENTEPE', 52),
(827, 1, 'İKİZCE', 52),
(828, 1, 'KABADÜZ', 52),
(829, 1, 'KABATAŞ', 52),
(830, 1, 'KORGAN', 52),
(831, 1, 'KUMRU', 52),
(832, 1, 'MESUDİYE', 52),
(833, 1, 'PERŞEMBE', 52),
(834, 1, 'ULUBEY', 52),
(835, 1, 'ÜNYE', 52),
(836, 0, 'OSMANİYE', 80),
(837, 1, 'BAHÇE', 80),
(838, 1, 'DÜZİÇİ', 80),
(839, 1, 'HASANBEYLİ', 80),
(840, 1, 'KADİRLİ', 80),
(841, 1, 'MERKEZ', 80),
(842, 1, 'SUMBAS', 80),
(843, 1, 'TOPRAKKALE', 80),
(844, 0, 'RİZE', 53),
(845, 1, 'ARDEŞEN', 53),
(846, 1, 'ÇAMLIHEMŞİN', 53),
(847, 1, 'ÇAYELİ', 53),
(848, 1, 'DEREPAZARI', 53),
(849, 1, 'FINDIKLI', 53),
(850, 1, 'GÜNEYSU', 53),
(851, 1, 'HEMŞİN', 53),
(852, 1, 'İKİZDERE', 53),
(853, 1, 'İYİDERE', 53),
(854, 1, 'KALKANDERE', 53),
(855, 1, 'MERKEZ', 53),
(856, 1, 'PAZAR', 53),
(857, 0, 'SAKARYA', 54),
(858, 1, 'ADAPAZARI', 54),
(859, 1, 'AKYAZI', 54),
(860, 1, 'ARİFİYE', 54),
(861, 1, 'ERENLER', 54),
(862, 1, 'FERİZLİ', 54),
(863, 1, 'GEYVE', 54),
(864, 1, 'HENDEK', 54),
(865, 1, 'KARAPÜRÇEK', 54),
(866, 1, 'KARASU', 54),
(867, 1, 'KAYNARCA', 54),
(868, 1, 'KOCAALİ', 54),
(869, 1, 'PAMUKOVA', 54),
(870, 1, 'SAPANCA', 54),
(871, 1, 'SERDİVAN', 54),
(872, 1, 'SÖĞÜTLÜ', 54),
(873, 1, 'TARAKLI', 54),
(874, 0, 'SAMSUN', 55),
(875, 1, '19MAYIS', 55),
(876, 1, 'ALAÇAM', 55),
(877, 1, 'ASARCIK', 55),
(878, 1, 'ATAKUM', 55),
(879, 1, 'AYVACIK', 55),
(880, 1, 'BAFRA', 55),
(881, 1, 'CANİK', 55),
(882, 1, 'ÇARŞAMBA', 55),
(883, 1, 'HAVZA', 55),
(884, 1, 'İLKADIM', 55),
(885, 1, 'KAVAK', 55),
(886, 1, 'LADİK', 55),
(887, 1, 'SALIPAZARI', 55),
(888, 1, 'TEKKEKÖY', 55),
(889, 1, 'TERME', 55),
(890, 1, 'VEZİRKÖPRÜ', 55),
(891, 1, 'YAKAKENT', 55),
(892, 0, 'ŞANLIURFA', 63),
(893, 1, 'AKÇAKALE', 63),
(894, 1, 'BİRECİK', 63),
(895, 1, 'BOZOVA', 63),
(896, 1, 'CEYLANPINAR', 63),
(897, 1, 'EYYÜBİYE', 63),
(898, 1, 'HALFETİ', 63),
(899, 1, 'HALİLİYE', 63),
(900, 1, 'HARRAN', 63),
(901, 1, 'HİLVAN', 63),
(902, 1, 'KARAKÖPRÜ', 63),
(903, 1, 'SİVEREK', 63),
(904, 1, 'SURUÇ', 63),
(905, 1, 'VİRANŞEHİR', 63),
(906, 0, 'SİİRT', 56),
(907, 1, 'BAYKAN', 56),
(908, 1, 'ERUH', 56),
(909, 1, 'KURTALAN', 56),
(910, 1, 'MERKEZ', 56),
(911, 1, 'PERVARİ', 56),
(912, 1, 'ŞİRVAN', 56),
(913, 1, 'TİLLO', 56),
(914, 0, 'SİNOP', 57),
(915, 1, 'AYANCIK', 57),
(916, 1, 'BOYABAT', 57),
(917, 1, 'DİKMEN', 57),
(918, 1, 'DURAĞAN', 57),
(919, 1, 'ERFELEK', 57),
(920, 1, 'GERZE', 57),
(921, 1, 'MERKEZ', 57),
(922, 1, 'SARAYDÜZÜ', 57),
(923, 1, 'TÜRKELİ', 57),
(924, 0, 'ŞIRNAK', 73),
(925, 1, 'BEYTÜŞŞEBAP', 73),
(926, 1, 'CİZRE', 73),
(927, 1, 'GÜÇLÜKONAK', 73),
(928, 1, 'İDİL', 73),
(929, 1, 'MERKEZ', 73),
(930, 1, 'SİLOPİ', 73),
(931, 1, 'ULUDERE', 73),
(932, 0, 'SİVAS', 58),
(933, 1, 'AKINCILAR', 58),
(934, 1, 'ALTINYAYLA', 58),
(935, 1, 'DİVRİĞİ', 58),
(936, 1, 'DOĞANŞAR', 58),
(937, 1, 'GEMEREK', 58),
(938, 1, 'GÖLOVA', 58),
(939, 1, 'GÜRÜN', 58),
(940, 1, 'HAFİK', 58),
(941, 1, 'İMRANLI', 58),
(942, 1, 'KANGAL', 58),
(943, 1, 'KOYULHİSAR', 58),
(944, 1, 'MERKEZ', 58),
(945, 1, 'ŞARKIŞLA', 58),
(946, 1, 'SUŞEHRİ', 58),
(947, 1, 'ULAŞ', 58),
(948, 1, 'YILDIZELİ', 58),
(949, 1, 'ZARA', 58),
(950, 0, 'TEKİRDAĞ', 59),
(951, 1, 'ÇERKEZKÖY', 59),
(952, 1, 'ÇORLU', 59),
(953, 1, 'ERGENE', 59),
(954, 1, 'HAYRABOLU', 59),
(955, 1, 'KAPAKLI', 59),
(956, 1, 'MALKARA', 59),
(957, 1, 'MARMARAEREĞLİSİ', 59),
(958, 1, 'MURATLI', 59),
(959, 1, 'SARAY', 59),
(960, 1, 'ŞARKÖY', 59),
(961, 1, 'SÜLEYMANPAŞA', 59),
(962, 0, 'TOKAT', 60),
(963, 1, 'ALMUS', 60),
(964, 1, 'ARTOVA', 60),
(965, 1, 'BAŞÇİFTLİK', 60),
(966, 1, 'ERBAA', 60),
(967, 1, 'MERKEZ', 60),
(968, 1, 'NİKSAR', 60),
(969, 1, 'PAZAR', 60),
(970, 1, 'REŞADİYE', 60),
(971, 1, 'SULUSARAY', 60),
(972, 1, 'TURHAL', 60),
(973, 1, 'YEŞİLYURT', 60),
(974, 1, 'ZİLE', 60),
(975, 0, 'TRABZON', 61),
(976, 1, 'AKÇAABAT', 61),
(977, 1, 'ARAKLI', 61),
(978, 1, 'ARSİN', 61),
(979, 1, 'BEŞİKDÜZÜ', 61),
(980, 1, 'ÇARŞIBAŞI', 61),
(981, 1, 'ÇAYKARA', 61),
(982, 1, 'DERNEKPAZARI', 61),
(983, 1, 'DÜZKÖY', 61),
(984, 1, 'HAYRAT', 61),
(985, 1, 'KÖPRÜBAŞI', 61),
(986, 1, 'MAÇKA', 61),
(987, 1, 'OF', 61),
(988, 1, 'ORTAHİSAR', 61),
(989, 1, 'ŞALPAZARI', 61),
(990, 1, 'SÜRMENE', 61),
(991, 1, 'TONYA', 61),
(992, 1, 'VAKFIKEBİR', 61),
(993, 1, 'YOMRA', 61),
(994, 0, 'TUNCELİ', 62),
(995, 1, 'ÇEMİŞGEZEK', 62),
(996, 1, 'HOZAT', 62),
(997, 1, 'MAZGİRT', 62),
(998, 1, 'MERKEZ', 62),
(999, 1, 'NAZIMİYE', 62),
(1000, 1, 'OVACIK', 62),
(1001, 1, 'PERTEK', 62),
(1002, 1, 'PÜLÜMÜR', 62),
(1003, 0, 'UŞAK', 64),
(1004, 1, 'BANAZ', 64),
(1005, 1, 'EŞME', 64),
(1006, 1, 'KARAHALLI', 64),
(1007, 1, 'MERKEZ', 64),
(1008, 1, 'SİVASLI', 64),
(1009, 1, 'ULUBEY', 64),
(1010, 0, 'VAN', 65),
(1011, 1, 'BAHÇESARAY', 65),
(1012, 1, 'BAŞKALE', 65),
(1013, 1, 'ÇALDIRAN', 65),
(1014, 1, 'ÇATAK', 65),
(1015, 1, 'EDREMİT', 65),
(1016, 1, 'ERCİŞ', 65),
(1017, 1, 'GEVAŞ', 65),
(1018, 1, 'GÜRPINAR', 65),
(1019, 1, 'İPEKYOLU', 65),
(1020, 1, 'MURADİYE', 65),
(1021, 1, 'ÖZALP', 65),
(1022, 1, 'SARAY', 65),
(1023, 1, 'TUŞBA', 65),
(1024, 0, 'YALOVA', 77),
(1025, 1, 'ALTINOVA', 77),
(1026, 1, 'ARMUTLU', 77),
(1027, 1, 'ÇİFTLİKKÖY', 77),
(1028, 1, 'ÇINARCIK', 77),
(1029, 1, 'MERKEZ', 77),
(1030, 1, 'TERMAL', 77),
(1031, 0, 'YOZGAT', 66),
(1032, 1, 'AKDAĞMADENİ', 66),
(1033, 1, 'AYDINCIK', 66),
(1034, 1, 'BOĞAZLIYAN', 66),
(1035, 1, 'ÇANDIR', 66),
(1036, 1, 'ÇAYIRALAN', 66),
(1037, 1, 'ÇEKEREK', 66),
(1038, 1, 'KADIŞEHRİ', 66),
(1039, 1, 'MERKEZ', 66),
(1040, 1, 'SARAYKENT', 66),
(1041, 1, 'SARIKAYA', 66),
(1042, 1, 'ŞEFAATLİ', 66),
(1043, 1, 'SORGUN', 66),
(1044, 1, 'YENİFAKILI', 66),
(1045, 1, 'YERKÖY', 66),
(1046, 0, 'ZONGULDAK', 67),
(1047, 1, 'ALAPLI', 67),
(1048, 1, 'ÇAYCUMA', 67),
(1049, 1, 'DEVREK', 67),
(1050, 1, 'EREĞLİ', 67),
(1051, 1, 'GÖKÇEBEY', 67),
(1052, 1, 'KİLİMLİ', 67),
(1053, 1, 'KOZLU', 67),
(1054, 1, 'MERKEZ', 67);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `operation_type` varchar(255) NOT NULL,
  `operation_detail` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(4, '0001_01_01_000003_create_locations_table', 1),
(5, '0001_01_01_000004_create_badges_table', 1),
(6, '0001_01_01_000005_create_user_badges_table', 1),
(7, '0001_01_01_000006_create_cvs_table', 1),
(8, '0001_01_01_000007_create_abilities_table', 1),
(9, '0001_01_01_000008_create_experiences_table', 1),
(10, '0001_01_01_000009_create_educations_table', 1),
(11, '0001_01_01_000010_create_languages_table', 1),
(12, '0001_01_01_000011_create_certificates_table', 1),
(13, '0001_01_01_000012_create_certificate_educations_table', 1),
(14, '0001_01_01_000013_create_user_certificates_table', 1),
(15, '0001_01_01_000014_create_logs_table', 1);

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('woman','man','other') NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `birth_date` date NOT NULL,
  `gsm` varchar(255) NOT NULL,
  `point` varchar(255) NOT NULL,
  `location_id` varchar(255) NOT NULL,
  `district_id` varchar(255) NOT NULL,
  `contact_info` tinyint(1) NOT NULL DEFAULT 1,
  `profile_photo_url` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `email_verified_at`, `password`, `gender`, `role`, `birth_date`, `gsm`, `point`, `location_id`, `district_id`, `contact_info`, `profile_photo_url`, `is_active`, `deleted_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', 'admin@kariyer.com', '2025-10-12 17:05:30', '$2y$12$cE.oxRqd1ihWcUb8qhpOLebhtUR1IdTtgkDpPi1Ozxr24FSuA3XrG', 'man', 'admin', '1990-01-15', '05551234567', '0', '1', '1', 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuA-AWicWCmp5DD_BDRcV7DOhqw0M1RpXUg-zNjAExRWK1NsNUu5LwjwYYg2lyriudBCqxmCBUak9yXwg4MlpYHgnC35wvqYtEbIK58IVpIx5wsd531ZXjNxs_-HOn5SSsJBJoHvBB_GjtFbffKAdG3v8vvc3pI3mrCQTcQuSu-O0ZVvMpQQSBpgXzMRx9uVHwEo8mJsACCSeBZQroBhC9yB2pq44ENUoZ8tGNofb4Pr1VkshyRpvPxUPVbB4vBnwKaWHmGFF4v4d58', 1, NULL, NULL, '2025-10-12 17:05:30', '2025-10-12 17:05:30'),
(2, 'Test', 'User', 'test@kariyer.com', '2025-10-12 17:05:30', '$2y$12$2f/sZevigkvWosaonFG0DuqFpgx.UoQV6goUN3iBQ4zliLqB7gYHm', 'woman', 'user', '1995-05-20', '05559876543', '0', '2', '2', 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuDpNDfV4gTAlcZeA_jy11IcrVcXO6eEMN43tBSoFJW6DVMuIhnkBoAGtPUry6YuudrPyGQwRgxhJbdyskpIsjSHGGV7E0pQA5ew8vOEiHgnWGVOhGCb_yWyBs3YQ98M2T6QTAC713wospvj8BGUwCqbe0bXvQgVyWCVrzUlvMesIWpjDQUgQHK0IGGWitrMri0tg8kI38x31dKBr99IGyxPoemeMQBYkCY2uH1ucVZ8kAi7dm9yKE77lv6KqNhkFmyShCINvMaN0bY', 1, NULL, NULL, '2025-10-12 17:05:30', '2025-10-12 17:05:30'),
(3, 'Demo', 'Kullanıcı', 'demo@kariyer.com', '2025-10-12 17:05:30', '$2y$12$nIN0ueZvcMKwdd0/1AOsTeYjCvM4y7I/6kScq5qdiTPYjI7XL4e2W', 'other', 'user', '1988-12-10', '05555555555', '0', '3', '3', 0, 'https://lh3.googleusercontent.com/aida-public/AB6AXuCoZKaVu3h9OLYhvfs_ziBQUK7yDvWH6dquhU3RLFdlf0mUbR1qOca6kn8M4IF80o7STpw8mOJrbe42X7CDFnNXtkh8BBD3peBQijFiQoNjD_c4FRqT_2Y3eeWCJFjgatIHrvc07BDFa8q0EbJC6QUYwHYyB-N2Xygk-fNPvs8wXdntLvdXrtJoMepD8ftpYKt7-3ULXHLpb-yykuCaMMyNGKKGP_mgX9nIAHgw5GWeUJzx4w1Js8qF3GoM6YQS1riAngqYTb_ee4U', 0, NULL, NULL, '2025-10-12 17:05:31', '2025-10-12 17:05:31'),
(4, 'Developer', 'Dev', 'dev@kariyer.com', '2025-10-12 17:05:31', '$2y$12$xJAAF.pNuW5N7iIAI0Jxjeffwsd5n2rCBZI8t4GTEQkpu.Zg7oZMG', 'man', 'admin', '1992-08-25', '05551111111', '0', '1', '1', 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuBTIc5c0DSBehmD3OBQorQ5--xC4lmEgbgeWMEnx1lwnPcWye3yFyacsK80y9Pirm33T73OtBWQGrRdA_9OtoQbNmSRyEIH6rGb1bixANq2UpTNoMTuC7kz0FZujY8w5sknn_V8ipq_3gML_j5PdO0QaJtCGTovBt9BMlUEtVdRK3yDMTgyivXb_QezKNWttz7lrGNKFzcIju9GpDPMI-kfBJOFTIrFx-ILfRpgiZRnTSr2IbrfwPthrrobrK1JXKi_2q_fvbGycv0', 1, NULL, NULL, '2025-10-12 17:05:31', '2025-10-12 17:05:31'),
(5, 'Manager', 'Manager', 'manager@kariyer.com', '2025-10-12 17:05:31', '$2y$12$JbG.zWlot3yizhXZzq0tierxdg.RH6n7Mhvr1td9LGP63H5NFNzWq', 'woman', 'admin', '1985-03-18', '05552222222', '0', '2', '2', 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuD-sJ7HsgrLwrclUBjxKigDAERUcpLBb9IolzMNCVVG-T_pD2c4LNjvIr06_Uj92vxW1NhCWaIK4k2kjkl64uLJvUfV0VL_q7u2SqLeEQWgHucoB-BWWMuneRxk03TvL3NsYV5flS7vEL04f-3x0uHLy3pe_uB7WTBp0gLDY-mlaJzj7aU2sfePuc7DajHjGwFIiCARETI3UpOvGJEIUYuzta1Gs1AKunJCeiWe7Cz87hXjtjg-OexlG8mrZF2Ios74dNJSfGB9_9U', 1, NULL, NULL, '2025-10-12 17:05:31', '2025-10-12 17:05:31'),
(6, 'Zeynep', 'Yılmaz', 'zeynep.yilmaz@kariyer.com', '2025-10-12 17:05:31', '$2y$12$rP8//udlFM52f.03w2W2MOCbrtGyWr2Vbf9PDUefeqnQvWth1sEES', 'woman', 'user', '1998-03-15', '05551234567', '0', '1', '1', 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuDpNDfV4gTAlcZeA_jy11IcrVcXO6eEMN43tBSoFJW6DVMuIhnkBoAGtPUry6YuudrPyGQwRgxhJbdyskpIsjSHGGV7E0pQA5ew8vOEiHgnWGVOhGCb_yWyBs3YQ98M2T6QTAC713wospvj8BGUwCqbe0bXvQgVyWCVrzUlvMesIWpjDQUgQHK0IGGWitrMri0tg8kI38x31dKBr99IGyxPoemeMQBYkCY2uH1ucVZ8kAi7dm9yKE77lv6KqNhkFmyShCINvMaN0bY', 1, NULL, NULL, '2025-10-12 17:05:31', '2025-10-12 17:05:31'),
(7, 'Emir', 'Demir', 'emir.demir@kariyer.com', '2025-10-12 17:05:31', '$2y$12$0C1/ULgrHoLHa6q5DgyVluMtkdNw5m/WgcgW9a6a3US/Lt4T0zqHS', 'man', 'user', '1999-07-22', '05559876543', '0', '2', '2', 0, 'https://lh3.googleusercontent.com/aida-public/AB6AXuCoZKaVu3h9OLYhvfs_ziBQUK7yDvWH6dquhU3RLFdlf0mUbR1qOca6kn8M4IF80o7STpw8mOJrbe42X7CDFnNXtkh8BBD3peBQijFiQoNjD_c4FRqT_2Y3eeWCJFjgatIHrvc07BDFa8q0EbJC6QUYwHYyB-N2Xygk-fNPvs8wXdntLvdXrtJoMepD8ftpYKt7-3ULXHLpb-yykuCaMMyNGKKGP_mgX9nIAHgw5GWeUJzx4w1Js8qF3GoM6YQS1riAngqYTb_ee4U', 0, NULL, NULL, '2025-10-12 17:05:31', '2025-10-12 17:05:31'),
(8, 'Elif', 'Çelik', 'elif.celik@kariyer.com', '2025-10-12 17:05:31', '$2y$12$uMe.CWdembuh3mO9SyQaP.cQ14RjtN4x3WvfDn4TjmTkqlfg/yYeW', 'woman', 'user', '2000-11-08', '05555555555', '0', '3', '3', 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuBTIc5c0DSBehmD3OBQorQ5--xC4lmEgbgeWMEnx1lwnPcWye3yFyacsK80y9Pirm33T73OtBWQGrRdA_9OtoQbNmSRyEIH6rGb1bixANq2UpTNoMTuC7kz0FZujY8w5sknn_V8ipq_3gML_j5PdO0QaJtCGTovBt9BMlUEtVdRK3yDMTgyivXb_QezKNWttz7lrGNKFzcIju9GpDPMI-kfBJOFTIrFx-ILfRpgiZRnTSr2IbrfwPthrrobrK1JXKi_2q_fvbGycv0', 1, NULL, NULL, '2025-10-12 17:05:32', '2025-10-12 17:05:32'),
(9, 'Yusuf', 'Şahin', 'yusuf.sahin@kariyer.com', '2025-10-12 17:05:32', '$2y$12$9MJxho9F3/58q039d31jou3AcSfnJfxbUkq8c0U.f1wbV3uM6NHGe', 'man', 'user', '1997-04-12', '05551111111', '0', '1', '1', 0, 'https://lh3.googleusercontent.com/aida-public/AB6AXuAiZy7_0sBZJFkpCWmTTb5dP7ArsSrprUhyLd5DdumZpJOXRfCpKxdIz7Fxsf8uUbLN6hcq_eyj6TSntrOWFM2O8xaDvH1hBK03bMxGqMfWql8o1ISCFBUTRLPPj7p_rSQCZOiudfWqW32IRURann1NVkDoOlJLe62wCT9KFkyTIMxXWYburwaPhsyFWhkEmHJZzAzhGThP9z3ACJYm2mVsCi6FL3-D1uCtlto_zBwQP7bS0-nInWp6CybUUZgYdFJEjkGtt35y1q0', 0, NULL, NULL, '2025-10-12 17:05:32', '2025-10-12 17:05:32'),
(10, 'Fatma', 'Aydın', 'fatma.aydin@kariyer.com', '2025-10-12 17:05:32', '$2y$12$abhk5FYiqkkEi19nc1pmx.znlW0Gg5qgzsKwsykvyKyOlMXaMfy3O', 'woman', 'user', '1996-09-30', '05552222222', '0', '2', '2', 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuD-sJ7HsgrLwrclUBjxKigDAERUcpLBb9IolzMNCVVG-T_pD2c4LNjvIr06_Uj92vxW1NhCWaIK4k2kjkl64uLJvUfV0VL_q7u2SqLeEQWgHucoB-BWWMuneRxk03TvL3NsYV5flS7vEL04f-3x0uHLy3pe_uB7WTBp0gLDY-mlaJzj7aU2sfePuc7DajHjGwFIiCARETI3UpOvGJEIUYuzta1Gs1AKunJCeiWe7Cz87hXjtjg-OexlG8mrZF2Ios74dNJSfGB9_9U', 1, NULL, NULL, '2025-10-12 17:05:32', '2025-10-12 17:05:32'),
(11, 'Ahmet', 'Kaya', 'ahmet.kaya@kariyer.com', '2025-10-12 17:05:32', '$2y$12$uIxXo4VAf2pLAZODHcVvBuNMwBHc4MlnAEAiHoCrVWXtDj/dRJ/sO', 'man', 'user', '1999-01-18', '05553333333', '0', '3', '3', 0, 'https://lh3.googleusercontent.com/aida-public/AB6AXuAiZy7_0sBZJFkpCWmTTb5dP7ArsSrprUhyLd5DdumZpJOXRfCpKxdIz7Fxsf8uUbLN6hcq_eyj6TSntrOWFM2O8xaDvH1hBK03bMxGqMfWql8o1ISCFBUTRLPPj7p_rSQCZOiudfWqW32IRURann1NVkDoOlJLe62wCT9KFkyTIMxXWYburwaPhsyFWhkEmHJZzAzhGThP9z3ACJYm2mVsCi6FL3-D1uCtlto_zBwQP7bS0-nInWp6CybUUZgYdFJEjkGtt35y1q0', 0, NULL, NULL, '2025-10-12 17:05:32', '2025-10-12 17:05:32'),
(12, 'Ayşe', 'Özkan', 'ayse.ozkan@kariyer.com', '2025-10-12 17:05:32', '$2y$12$lmk3VK0D3GjXePvVzOazxebqaSPRP4GXlxEOuZjEemCC1j65KRQfa', 'woman', 'user', '1998-06-25', '05554444444', '0', '1', '1', 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuDpNDfV4gTAlcZeA_jy11IcrVcXO6eEMN43tBSoFJW6DVMuIhnkBoAGtPUry6YuudrPyGQwRgxhJbdyskpIsjSHGGV7E0pQA5ew8vOEiHgnWGVOhGCb_yWyBs3YQ98M2T6QTAC713wospvj8BGUwCqbe0bXvQgVyWCVrzUlvMesIWpjDQUgQHK0IGGWitrMri0tg8kI38x31dKBr99IGyxPoemeMQBYkCY2uH1ucVZ8kAi7dm9yKE77lv6KqNhkFmyShCINvMaN0bY', 1, NULL, NULL, '2025-10-12 17:05:32', '2025-10-12 17:05:32'),
(13, 'Mehmet', 'Yıldız', 'mehmet.yildiz@kariyer.com', '2025-10-12 17:05:32', '$2y$12$uMtW6BIfXmBChdAzBaPQY.AAjw/BnBuOHgiJUbINCXMka1g6rq5yC', 'man', 'user', '1997-12-03', '05555555555', '0', '2', '2', 0, 'https://lh3.googleusercontent.com/aida-public/AB6AXuCoZKaVu3h9OLYhvfs_ziBQUK7yDvWH6dquhU3RLFdlf0mUbR1qOca6kn8M4IF80o7STpw8mOJrbe42X7CDFnNXtkh8BBD3peBQijFiQoNjD_c4FRqT_2Y3eeWCJFjgatIHrvc07BDFa8q0EbJC6QUYwHYyB-N2Xygk-fNPvs8wXdntLvdXrtJoMepD8ftpYKt7-3ULXHLpb-yykuCaMMyNGKKGP_mgX9nIAHgw5GWeUJzx4w1Js8qF3GoM6YQS1riAngqYTb_ee4U', 0, NULL, NULL, '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(14, 'Selin', 'Arslan', 'selin.arslan@kariyer.com', '2025-10-12 17:05:33', '$2y$12$1yyHpxtrpfC.6GVFHR.aCu7iJC2O8fHNMTNO9LzjSoGwqIF7iHkq2', 'woman', 'user', '2000-08-14', '05556666666', '0', '3', '3', 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuBTIc5c0DSBehmD3OBQorQ5--xC4lmEgbgeWMEnx1lwnPcWye3yFyacsK80y9Pirm33T73OtBWQGrRdA_9OtoQbNmSRyEIH6rGb1bixANq2UpTNoMTuC7kz0FZujY8w5sknn_V8ipq_3gML_j5PdO0QaJtCGTovBt9BMlUEtVdRK3yDMTgyivXb_QezKNWttz7lrGNKFzcIju9GpDPMI-kfBJOFTIrFx-ILfRpgiZRnTSr2IbrfwPthrrobrK1JXKi_2q_fvbGycv0', 1, NULL, NULL, '2025-10-12 17:05:33', '2025-10-12 17:05:33'),
(15, 'Can', 'Doğan', 'can.dogan@kariyer.com', '2025-10-12 17:05:33', '$2y$12$xMwvXUIRrZ9dx0ymM5LsFeKF2XvrzbPXuWBc4VZH8ZMgbjh23pbHe', 'man', 'user', '1999-05-07', '05557777777', '0', '1', '1', 1, 'https://lh3.googleusercontent.com/aida-public/AB6AXuAiZy7_0sBZJFkpCWmTTb5dP7ArsSrprUhyLd5DdumZpJOXRfCpKxdIz7Fxsf8uUbLN6hcq_eyj6TSntrOWFM2O8xaDvH1hBK03bMxGqMfWql8o1ISCFBUTRLPPj7p_rSQCZOiudfWqW32IRURann1NVkDoOlJLe62wCT9KFkyTIMxXWYburwaPhsyFWhkEmHJZzAzhGThP9z3ACJYm2mVsCi6FL3-D1uCtlto_zBwQP7bS0-nInWp6CybUUZgYdFJEjkGtt35y1q0', 1, NULL, NULL, '2025-10-12 17:05:33', '2025-10-12 17:05:33');

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `badge_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_certificates`
--

CREATE TABLE `user_certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certificate_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `certificate_code` varchar(255) NOT NULL,
  `achievement_score` int(11) NOT NULL,
  `issuing_institution` varchar(255) NOT NULL,
  `acquisition_date` date NOT NULL,
  `validity_period` varchar(255) DEFAULT NULL,
  `success_score` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abilities`
--
ALTER TABLE `abilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `abilities_cv_id_foreign` (`cv_id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificate_educations`
--
ALTER TABLE `certificate_educations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificate_educations_certificate_id_foreign` (`certificate_id`);

--
-- Indexes for table `cvs`
--
ALTER TABLE `cvs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cvs_user_id_foreign` (`user_id`);

--
-- Indexes for table `educations`
--
ALTER TABLE `educations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `educations_cv_id_foreign` (`cv_id`);

--
-- Indexes for table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `experiences_cv_id_foreign` (`cv_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `languages_cv_id_foreign` (`cv_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_badges_user_id_foreign` (`user_id`),
  ADD KEY `user_badges_badge_id_foreign` (`badge_id`);

--
-- Indexes for table `user_certificates`
--
ALTER TABLE `user_certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_certificates_certificate_id_foreign` (`certificate_id`),
  ADD KEY `user_certificates_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abilities`
--
ALTER TABLE `abilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificate_educations`
--
ALTER TABLE `certificate_educations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cvs`
--
ALTER TABLE `cvs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `educations`
--
ALTER TABLE `educations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1055;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_certificates`
--
ALTER TABLE `user_certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `abilities`
--
ALTER TABLE `abilities`
  ADD CONSTRAINT `abilities_cv_id_foreign` FOREIGN KEY (`cv_id`) REFERENCES `cvs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `certificate_educations`
--
ALTER TABLE `certificate_educations`
  ADD CONSTRAINT `certificate_educations_certificate_id_foreign` FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cvs`
--
ALTER TABLE `cvs`
  ADD CONSTRAINT `cvs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `educations`
--
ALTER TABLE `educations`
  ADD CONSTRAINT `educations_cv_id_foreign` FOREIGN KEY (`cv_id`) REFERENCES `cvs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `experiences`
--
ALTER TABLE `experiences`
  ADD CONSTRAINT `experiences_cv_id_foreign` FOREIGN KEY (`cv_id`) REFERENCES `cvs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `languages`
--
ALTER TABLE `languages`
  ADD CONSTRAINT `languages_cv_id_foreign` FOREIGN KEY (`cv_id`) REFERENCES `cvs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_badge_id_foreign` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_certificates`
--
ALTER TABLE `user_certificates`
  ADD CONSTRAINT `user_certificates_certificate_id_foreign` FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
