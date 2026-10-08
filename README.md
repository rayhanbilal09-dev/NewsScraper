# Scheduled News Web Scraper with Topic Extraction and Scraping Monitoring System

A production-ready Laravel application built with **PHP 8.2+**, **Laravel 12**, **Sastrawi** (Indonesian Stemmer & Stopwords), **PHP-Science-TextRank** (PageRank Keyword Extraction), **GuzzleHTTP**, **Symfony DomCrawler**, and **Tailwind CSS**.

---

## 🛠 Tech Stack & Packages

1. **Framework**: Laravel 12 (PHP 8.2 / 8.x)
2. **Indonesian NLP & Stemming**: [`sastrawi/sastrawi`](https://github.com/sastrawi/sastrawi) (`^1.2`)
3. **Keyword Ranking & Extraction**: [`php-science/textrank`](https://github.com/DavidBelicza/PHP-Science-TextRank) (`^1.2`)
4. **HTTP Client & HTML Parser**: [`guzzlehttp/guzzle`](https://github.com/guzzle/guzzle) & [`symfony/dom-crawler`](https://symfony.com/doc/current/components/dom_crawler.html)
5. **Frontend**: Blade Templating + Tailwind CSS (via CDN) + Alpine.js (via CDN)

---

## 📂 Architecture Overview

```
app/
├── Console/
│   └── Commands/
│       └── RunNewsScraper.php           # Artisan command (app:run-news-scraper)
├── Http/
│   └── Controllers/
│       ├── ScrapingLogController.php    # Scraper telemetry & dashboard controller
│       └── TrendingTopicController.php  # Trending topics web & API controller
├── Models/
│   ├── ScrapingLog.php                  # Eloquent model for scraping telemetry
│   └── TrendingTopic.php                # Eloquent model for extracted topics
└── Services/
    └── NewsScraperService.php           # Scraping, Sastrawi NLP & TextRank pipeline

database/
├── migrations/
│   ├── 2026_10_08_061655_create_scraping_logs_table.php
│   └── 2026_10_08_061656_create_trending_topics_table.php
├── seeders/
│   └── DatabaseSeeder.php               # Comprehensive dummy data seeder
└── newsscraper.sql                      # Ready-to-import SQL database dump

resources/views/
├── layouts/
│   └── app.blade.php                    # Master layout with collapsible sidebar & modal
└── pages/
    ├── trending-topics.blade.php        # Responsive 5-col cards with NLP pipeline steps
    └── scraper-monitor.blade.php        # 4 Telemetry metric cards + logs table & modal
```

---

## 📊 Database Schema (Strict Schema Specification)

### 1. `scraping_logs` Table
- `id` (Primary Key, unsignedBigInteger)
- `source_name` (string: Kompas, Tempo, Antara, CNN Indonesia, etc.)
- `url` (text: Target URL / RSS Feed)
- `status` (enum: `'success'`, `'failed'`)
- `start_time` (timestamp, nullable)
- `end_time` (timestamp, nullable)
- `error_message` (text, nullable: Captured exception trace or HTTP failure)
- `timestamps()` (`created_at`, `updated_at`)

### 2. `trending_topics` Table
- `id` (Primary Key, unsignedBigInteger)
- `topic_name` (string: Format "Topik: #Category - Topic Name")
- `category` (string, nullable: `#Politik`, `#Tekno`, `#Ekonomi`, `#Pendidikan`, `#Kesehatan`)
- `score_or_count` (double: Aggregated TextRank salience score)
- `last_successful_update` (timestamp, nullable)

---

## 🚀 Panduan Setup Database untuk yang Meng-clone dari GitHub

Bagi yang menyalin atau meng-clone repository ini, ikuti langkah-langkah mudah berikut:

### 1. Clone Repositori & Install Dependensi
```bash
git clone https://github.com/rayhanbilal09-dev/NewsScraper.git
cd NewsScraper
composer install
```

### 2. Salin Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database (Pilih salah satu cara)

- **Cara A (Migrasi & Seed Otomatis - Rekomendasi):**
  Sesuaikan konfigurasi database Anda di file `.env`, lalu jalankan:
  ```bash
  php artisan migrate --seed
  ```
  Atau untuk reset dan fresh seeding:
  ```bash
  php artisan migrate:fresh --seed
  ```

- **Cara B (SQLite - Langsung Pakai Tanpa Setup MySQL):**
  File database `database/database.sqlite` sudah disertakan di repositori:
  ```ini
  DB_CONNECTION=sqlite
  ```
  Langsung jalankan `php artisan serve`!

- **Cara C (MySQL / MariaDB via SQL Dump):**
  Tersedia file SQL dump [`database/newsscraper.sql`](database/newsscraper.sql). Anda dapat mengimpornya via phpMyAdmin atau terminal:
  ```bash
  mysql -u root -p newsscraper < database/newsscraper.sql
  ```
  Lalu sesuaikan `.env`:
  ```ini
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=newsscraper
  DB_USERNAME=root
  DB_PASSWORD=
  ```

---

## 💻 Menjalankan Aplikasi

### 1. Jalankan Local Web Server
```bash
php artisan serve
```
Akses halaman dashboard di browser:
- **Trending Topics**: [http://127.0.0.1:8000/trending-topics](http://127.0.0.1:8000/trending-topics)
- **Scraping Monitor**: [http://127.0.0.1:8000/scraping-logs](http://127.0.0.1:8000/scraping-logs)

### 2. Menjalankan Scraper Secara Manual
Untuk melakukan crawling berita aktual, stemming bahasa Indonesia, dan perangkingan topik:
```bash
php artisan app:run-news-scraper
```
Atau klik tombol **"Run Scraper"** / **"Run Pipeline"** langsung dari antarmuka Web UI.

### 3. Menjalankan Task Scheduler
Scraper telah didaftarkan pada scheduler Laravel (`routes/console.php`) agar berjalan setiap jam:
```bash
php artisan schedule:work
```

---

## 🧪 Menjalankan Automated Tests
Semua endpoint, render UI, pipeline NLP Sastrawi & TextRank, serta Artisan Command telah diverifikasi dengan PHPUnit test:
```bash
php artisan test
```
Hasil: `8 passed (36 assertions)`.
