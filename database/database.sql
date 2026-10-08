-- =====================================================================
-- Database SQL Dump: NewsScraper
-- Project: Scheduled News Web Scraper with Topic Extraction & Monitoring
-- Framework: Laravel (PHP 8.x / MySQL / MariaDB / phpMyAdmin Compatible)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `newsscraper` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `newsscraper`;

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ---------------------------------------------------------------------
-- Table structure for `users`
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table structure for `trending_topics`
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `trending_topics`;
CREATE TABLE `trending_topics` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `topic_name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `score_or_count` double NOT NULL DEFAULT 0,
  `last_successful_update` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Dumping data for table `trending_topics`
-- ---------------------------------------------------------------------
INSERT INTO `trending_topics` (`id`, `topic_name`, `category`, `score_or_count`, `last_successful_update`, `created_at`, `updated_at`) VALUES
(1, 'Topik: #Politik - Kesiapan Logistik Pilkada Serentak & Pengawasan KPU', '#Politik', 214.85, NOW(), NOW(), NOW()),
(2, 'Topik: #Tekno - Percepatan Transformasi Digital & Ekosistem AI Nasional', '#Tekno', 189.40, NOW(), NOW(), NOW()),
(3, 'Topik: #Ekonomi - Penguatan Nilai Tukar Rupiah & Stabilitas Pasar Finansial', '#Ekonomi', 165.95, NOW(), NOW(), NOW()),
(4, 'Topik: #Pendidikan - Revitalisasi Kurikulum Kampus & Beasiswa Riset Sains', '#Pendidikan', 142.70, NOW(), NOW(), NOW()),
(5, 'Topik: #Hukum - Vonis Sidang Tipikor & Transparansi Penegakan Hukum', '#Hukum', 128.50, NOW(), NOW(), NOW());

-- ---------------------------------------------------------------------
-- Table structure for `scraping_logs`
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `scraping_logs`;
CREATE TABLE `scraping_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `source_name` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `status` enum('success','failed') NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `duration_seconds` decimal(8,2) NOT NULL DEFAULT 0.00,
  `records_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Dumping data for table `scraping_logs`
-- ---------------------------------------------------------------------
INSERT INTO `scraping_logs` (`id`, `source_name`, `url`, `status`, `start_time`, `end_time`, `error_message`, `duration_seconds`, `records_count`, `created_at`, `updated_at`) VALUES
(1, 'Antara News Terkini', 'https://www.antaranews.com/rss/terkini.xml', 'success', DATE_SUB(NOW(), INTERVAL 12 MINUTE), DATE_SUB(NOW(), INTERVAL 12 MINUTE), NULL, 0.35, 50, DATE_SUB(NOW(), INTERVAL 12 MINUTE), DATE_SUB(NOW(), INTERVAL 12 MINUTE)),
(2, 'Tempo Nasional & Politik', 'https://rss.tempo.co/nasional', 'success', DATE_SUB(NOW(), INTERVAL 11 MINUTE), DATE_SUB(NOW(), INTERVAL 11 MINUTE), NULL, 0.18, 50, DATE_SUB(NOW(), INTERVAL 11 MINUTE), DATE_SUB(NOW(), INTERVAL 11 MINUTE)),
(3, 'Tempo Bisnis & Ekonomi', 'https://rss.tempo.co/bisnis', 'success', DATE_SUB(NOW(), INTERVAL 10 MINUTE), DATE_SUB(NOW(), INTERVAL 10 MINUTE), NULL, 0.12, 50, DATE_SUB(NOW(), INTERVAL 10 MINUTE), DATE_SUB(NOW(), INTERVAL 10 MINUTE)),
(4, 'Tempo Teknologi & Sains', 'https://rss.tempo.co/tekno', 'failed', DATE_SUB(NOW(), INTERVAL 9 MINUTE), DATE_SUB(NOW(), INTERVAL 9 MINUTE), 'Client error: `GET https://rss.tempo.co/tekno` resulted in a `403 Forbidden` response (Cloudflare bot protection)', 0.04, 0, DATE_SUB(NOW(), INTERVAL 9 MINUTE), DATE_SUB(NOW(), INTERVAL 9 MINUTE)),
(5, 'CNN Indonesia Nasional', 'https://www.cnnindonesia.com/nasional/rss', 'success', DATE_SUB(NOW(), INTERVAL 8 MINUTE), DATE_SUB(NOW(), INTERVAL 8 MINUTE), NULL, 0.33, 100, DATE_SUB(NOW(), INTERVAL 8 MINUTE), DATE_SUB(NOW(), INTERVAL 8 MINUTE)),
(6, 'Kompas Edukasi & Pendidikan', 'https://edukasi.kompas.com', 'success', DATE_SUB(NOW(), INTERVAL 7 MINUTE), DATE_SUB(NOW(), INTERVAL 7 MINUTE), NULL, 0.85, 12, DATE_SUB(NOW(), INTERVAL 7 MINUTE), DATE_SUB(NOW(), INTERVAL 7 MINUTE));

COMMIT;
SET FOREIGN_KEY_CHECKS=1;
