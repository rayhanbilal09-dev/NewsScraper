-- =====================================================================
-- NewsScraper Database Dump
-- Scheduled News Web Scraper with Indonesian Topic Extraction & Telemetry
-- Strict schema compatibility with Laravel Migrations
-- Compatible with MySQL 5.7+, MariaDB 10+, and standard SQL RDBMS
-- =====================================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Dumping data for table `trending_topics`
-- ---------------------------------------------------------------------
INSERT INTO `trending_topics` (`id`, `topic_name`, `category`, `score_or_count`, `last_successful_update`) VALUES
(1, 'Topik: #Politik - Kesiapan Logistik Pilkada Serentak & Pengawasan KPU di Seluruh Provinsi', '#Politik', 248.85, DATE_SUB(NOW(), INTERVAL 8 MINUTE)),
(2, 'Topik: #Tekno - Percepatan Transformasi Digital, Pusat Data & Ekosistem AI Nasional', '#Tekno', 212.40, DATE_SUB(NOW(), INTERVAL 14 MINUTE)),
(3, 'Topik: #Ekonomi - Penguatan Nilai Tukar Rupiah & Stabilitas Likuiditas Pasar Finansial', '#Ekonomi', 189.95, DATE_SUB(NOW(), INTERVAL 21 MINUTE)),
(4, 'Topik: #Pendidikan - Revitalisasi Kurikulum Kampus Merdeka & Alokasi Beasiswa Riset', '#Pendidikan', 156.70, DATE_SUB(NOW(), INTERVAL 35 MINUTE)),
(5, 'Topik: #Kesehatan - Peningkatan Fasilitas Layanan Medis Daerah & Digitalisasi BPJS', '#Kesehatan', 134.20, DATE_SUB(NOW(), INTERVAL 42 MINUTE));

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Dumping data for table `scraping_logs`
-- ---------------------------------------------------------------------
INSERT INTO `scraping_logs` (`id`, `source_name`, `url`, `status`, `start_time`, `end_time`, `error_message`, `created_at`, `updated_at`) VALUES
(1, 'Antara News Terkini', 'https://www.antaranews.com/rss/terkini.xml', 'success', DATE_SUB(NOW(), INTERVAL 3 MINUTE), DATE_SUB(NOW(), INTERVAL 3 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 3 MINUTE), DATE_SUB(NOW(), INTERVAL 3 MINUTE)),
(2, 'Tempo Nasional & Politik', 'https://rss.tempo.co/nasional', 'success', DATE_SUB(NOW(), INTERVAL 8 MINUTE), DATE_SUB(NOW(), INTERVAL 8 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 8 MINUTE), DATE_SUB(NOW(), INTERVAL 8 MINUTE)),
(3, 'Tempo Bisnis & Ekonomi', 'https://rss.tempo.co/bisnis', 'success', DATE_SUB(NOW(), INTERVAL 12 MINUTE), DATE_SUB(NOW(), INTERVAL 12 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 12 MINUTE), DATE_SUB(NOW(), INTERVAL 12 MINUTE)),
(4, 'Tempo Teknologi & Sains', 'https://rss.tempo.co/tekno', 'failed', DATE_SUB(NOW(), INTERVAL 15 MINUTE), DATE_SUB(NOW(), INTERVAL 15 MINUTE), 'Client error: `GET https://rss.tempo.co/tekno` resulted in a `403 Forbidden` response (Cloudflare bot challenge detected - User-Agent rate limited)', DATE_SUB(NOW(), INTERVAL 15 MINUTE), DATE_SUB(NOW(), INTERVAL 15 MINUTE)),
(5, 'CNN Indonesia Nasional', 'https://www.cnnindonesia.com/nasional/rss', 'success', DATE_SUB(NOW(), INTERVAL 18 MINUTE), DATE_SUB(NOW(), INTERVAL 18 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 18 MINUTE), DATE_SUB(NOW(), INTERVAL 18 MINUTE)),
(6, 'CNN Indonesia Teknologi', 'https://www.cnnindonesia.com/teknologi/rss', 'success', DATE_SUB(NOW(), INTERVAL 22 MINUTE), DATE_SUB(NOW(), INTERVAL 22 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 22 MINUTE), DATE_SUB(NOW(), INTERVAL 22 MINUTE)),
(7, 'Kompas Edukasi & Pendidikan', 'https://edukasi.kompas.com', 'success', DATE_SUB(NOW(), INTERVAL 28 MINUTE), DATE_SUB(NOW(), INTERVAL 28 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 28 MINUTE), DATE_SUB(NOW(), INTERVAL 28 MINUTE)),
(8, 'Kompas Sains & Teknologi', 'https://sains.kompas.com', 'success', DATE_SUB(NOW(), INTERVAL 34 MINUTE), DATE_SUB(NOW(), INTERVAL 34 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 34 MINUTE), DATE_SUB(NOW(), INTERVAL 34 MINUTE)),
(9, 'Detik News Terpopuler', 'https://news.detik.com/berita/rss', 'failed', DATE_SUB(NOW(), INTERVAL 41 MINUTE), DATE_SUB(NOW(), INTERVAL 41 MINUTE), 'Client error: `GET https://news.detik.com/berita/rss` resulted in a `404 Not Found` response (Feed endpoint relocated)', DATE_SUB(NOW(), INTERVAL 41 MINUTE), DATE_SUB(NOW(), INTERVAL 41 MINUTE)),
(10, 'Republika Terkini', 'https://republika.co.id/rss', 'success', DATE_SUB(NOW(), INTERVAL 49 MINUTE), DATE_SUB(NOW(), INTERVAL 49 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 49 MINUTE), DATE_SUB(NOW(), INTERVAL 49 MINUTE)),
(11, 'Bisnis Indonesia Market', 'https://market.bisnis.com/rss', 'success', DATE_SUB(NOW(), INTERVAL 58 MINUTE), DATE_SUB(NOW(), INTERVAL 58 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 58 MINUTE), DATE_SUB(NOW(), INTERVAL 58 MINUTE)),
(12, 'Liputan6 News Feed', 'https://www.liputan6.com/feed/news', 'failed', DATE_SUB(NOW(), INTERVAL 72 MINUTE), DATE_SUB(NOW(), INTERVAL 72 MINUTE), 'cURL error 28: Operation timed out after 5001 milliseconds with 0 bytes received (Gateway read timeout)', DATE_SUB(NOW(), INTERVAL 72 MINUTE), DATE_SUB(NOW(), INTERVAL 72 MINUTE)),
(13, 'Antara Ekonomi & Keuangan', 'https://www.antaranews.com/rss/ekonomi.xml', 'success', DATE_SUB(NOW(), INTERVAL 85 MINUTE), DATE_SUB(NOW(), INTERVAL 85 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 85 MINUTE), DATE_SUB(NOW(), INTERVAL 85 MINUTE)),
(14, 'Antara Politik & Hukum', 'https://www.antaranews.com/rss/politik.xml', 'success', DATE_SUB(NOW(), INTERVAL 100 MINUTE), DATE_SUB(NOW(), INTERVAL 100 MINUTE), NULL, DATE_SUB(NOW(), INTERVAL 100 MINUTE), DATE_SUB(NOW(), INTERVAL 100 MINUTE));

COMMIT;
SET FOREIGN_KEY_CHECKS=1;
