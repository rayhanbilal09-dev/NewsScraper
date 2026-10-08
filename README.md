# Scheduled News Web Scraper with Topic Extraction and Scraping Monitoring System

A production-ready Laravel application built with **PHP 8.2+**, **Laravel 12**, **Sastrawi** (Indonesian Stemmer & Stopwords), **PHP-Science-TextRank** (PageRank Keyword Extraction), **GuzzleHTTP**, **Symfony DomCrawler**, and **Tailwind CSS**.

---

## 🛠 Tech Stack & Packages

1. **Framework**: Laravel 12 (PHP 8.2 / 8.x)
2. **Indonesian NLP & Stemming**: [`sastrawi/sastrawi`](https://github.com/sastrawi/sastrawi) (`^1.2`)
3. **Keyword Ranking & Extraction**: [`php-science/textrank`](https://github.com/DavidBelicza/PHP-Science-TextRank) (`^1.2`)
4. **HTTP Client & HTML Parser**: [`guzzlehttp/guzzle`](https://github.com/guzzle/guzzle) & [`symfony/dom-crawler`](https://symfony.com/doc/current/components/dom_crawler.html)
5. **Frontend**: Blade Templating + Tailwind CSS via CDN

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
└── migrations/
    ├── 2026_10_08_061655_create_scraping_logs_table.php
    └── 2026_10_08_061656_create_trending_topics_table.php

resources/views/
├── layouts/
│   └── app.blade.php                    # Master layout with Tailwind CDN & navbar
└── pages/
    ├── trending-topics.blade.php        # Page 1: Responsive 4-col card grid
    └── scraper-monitor.blade.php        # Page 2: 4 metrics + logs table & modal
```

---

## 📊 Database Schema

### 1. `scraping_logs` Table
- `id` (Primary Key, unsignedBigInteger)
- `source_name` (string: Kompas, Tempo, Antara, CNN Indonesia)
- `url` (text: Target URL / RSS Feed)
- `status` (enum: 'success', 'failed')
- `start_time` (timestamp)
- `end_time` (timestamp)
- `error_message` (text, nullable: Captured exception trace or HTTP failure)
- `duration_seconds` (decimal 8,2: Elapsed execution duration in seconds)
- `records_count` (unsignedInteger: Extracted items/articles count)
- `timestamps()` (`created_at`, `updated_at`)

### 2. `trending_topics` Table
- `id` (Primary Key, unsignedBigInteger)
- `topic_name` (string: Format "Topik: #Category - Topic Name")
- `category` (string, nullable: #Politik, #Tekno, #Ekonomi, #Pendidikan, etc.)
- `score_or_count` (double: Aggregated TextRank score)
- `last_successful_update` (timestamp)
- `timestamps()` (`created_at`, `updated_at`)

---

## 🧠 Scraping & NLP Pipeline (`NewsScraperService`)

1. **HTTP Extraction**:
   - Guzzle HTTP client with customized User-Agent and timeouts.
   - Graceful try-catch error handling: Failed requests are captured and logged to `scraping_logs` with status `'failed'`, timestamps, and error messages.
2. **HTML / XML Parsing**:
   - `Symfony\Component\DomCrawler\Crawler` handles both RSS/Atom XML feeds and direct HTML web pages.
   - Extracts titles, body content, and metadata cleanly while stripping HTML noise.
3. **Indonesian Text Preprocessing (`Sastrawi`)**:
   - **Stopword Removal**: Removes Indonesian stop words (e.g., `yang`, `di`, `dari`, `untuk`, `adalah`) using `Sastrawi\StopWordRemover\StopWordRemoverFactory`.
   - **Stemming**: Converts words to root lemmas using `Sastrawi\Stemmer\StemmerFactory` (Nazief-Adriani algorithm).
4. **Keyword Ranking (`PHP-Science-TextRank`)**:
   - Ingests stemmed content into `PhpScience\TextRank\TextRankFacade::getOnlyKeyWords()`.
   - Computes graph-based co-occurrence matrix and PageRank salience scores.
5. **Topic Aggregation & Storage**:
   - Aggregates scores across feeds.
   - Classifies categories and formats titles (e.g. `Topik: #Politik - Pilkada Serentak`).
   - Updates the top 5 trending topics in `trending_topics` with `last_successful_update`.

---

## 🚀 Running the Application

### 1. Execute Manual Scraping via Console
```bash
php artisan app:run-news-scraper
```

### 2. Schedule Execution
The scraper is scheduled in `routes/console.php`:
```php
Schedule::command('app:run-news-scraper')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();
```
To run the Laravel schedule worker:
```bash
php artisan schedule:work
```

### 3. Run Development Server
```bash
php artisan serve --port=8000
```
- **Trending Topics Page**: [http://127.0.0.1:8000/trending-topics](http://127.0.0.1:8000/trending-topics)
- **Web Scraper Monitor**: [http://127.0.0.1:8000/scraping-logs](http://127.0.0.1:8000/scraping-logs)

### 4. API Endpoints
- `GET /api/trending-topics`: Returns top 5 trending topics and `last_updated_at`.
- `GET /api/scraping-logs`: Returns recent logs with `succeeded_jobs_count`, `failed_jobs_count`, `active_scrapers_count`, and `proxy_health`.
- `GET /api/scraping-logs/{id}`: Detailed inspection of a specific log entry.
- `POST /api/run-scraper`: Programmatically trigger a scrape.

### 5. Running Automated Tests
```bash
php artisan test
```
All 8 Feature and Unit tests pass (36 assertions).
