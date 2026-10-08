<?php

namespace Database\Seeders;

use App\Models\ScrapingLog;
use App\Models\TrendingTopic;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with comprehensive dummy data
     * for developers and reviewers cloning from GitHub.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // ═══════════════════════════════════════════════════════════════════
        // 1. DUMMY TRENDING TOPICS (Strict Schema: id, topic_name, category, score_or_count, last_successful_update)
        // ═══════════════════════════════════════════════════════════════════
        TrendingTopic::truncate();

        $topics = [
            [
                'topic_name' => 'Topik: #Politik - Kesiapan Logistik Pilkada Serentak & Pengawasan KPU di Seluruh Provinsi',
                'category' => '#Politik',
                'score_or_count' => 248.85,
                'last_successful_update' => $now->copy()->subMinutes(8),
            ],
            [
                'topic_name' => 'Topik: #Tekno - Percepatan Transformasi Digital, Pusat Data & Ekosistem AI Nasional',
                'category' => '#Tekno',
                'score_or_count' => 212.40,
                'last_successful_update' => $now->copy()->subMinutes(14),
            ],
            [
                'topic_name' => 'Topik: #Ekonomi - Penguatan Nilai Tukar Rupiah & Stabilitas Likuiditas Pasar Finansial',
                'category' => '#Ekonomi',
                'score_or_count' => 189.95,
                'last_successful_update' => $now->copy()->subMinutes(21),
            ],
            [
                'topic_name' => 'Topik: #Pendidikan - Revitalisasi Kurikulum Kampus Merdeka & Alokasi Beasiswa Riset',
                'category' => '#Pendidikan',
                'score_or_count' => 156.70,
                'last_successful_update' => $now->copy()->subMinutes(35),
            ],
            [
                'topic_name' => 'Topik: #Kesehatan - Peningkatan Fasilitas Layanan Medis Daerah & Digitalisasi BPJS',
                'category' => '#Kesehatan',
                'score_or_count' => 134.20,
                'last_successful_update' => $now->copy()->subMinutes(42),
            ],
        ];

        foreach ($topics as $topic) {
            TrendingTopic::create($topic);
        }

        // ═══════════════════════════════════════════════════════════════════
        // 2. DUMMY SCRAPING LOGS (Strict Schema: id, source_name, url, status, start_time, end_time, error_message, timestamps)
        // ═══════════════════════════════════════════════════════════════════
        ScrapingLog::truncate();

        $logs = [
            [
                'source_name' => 'Antara News Terkini',
                'url' => 'https://www.antaranews.com/rss/terkini.xml',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(3)->subSeconds(4),
                'end_time' => $now->copy()->subMinutes(3),
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(3),
                'updated_at' => $now->copy()->subMinutes(3),
            ],
            [
                'source_name' => 'Tempo Nasional & Politik',
                'url' => 'https://rss.tempo.co/nasional',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(8)->subSeconds(2),
                'end_time' => $now->copy()->subMinutes(8),
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(8),
                'updated_at' => $now->copy()->subMinutes(8),
            ],
            [
                'source_name' => 'Tempo Bisnis & Ekonomi',
                'url' => 'https://rss.tempo.co/bisnis',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(12)->subSeconds(1),
                'end_time' => $now->copy()->subMinutes(12),
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(12),
                'updated_at' => $now->copy()->subMinutes(12),
            ],
            [
                'source_name' => 'Tempo Teknologi & Sains',
                'url' => 'https://rss.tempo.co/tekno',
                'status' => 'failed',
                'start_time' => $now->copy()->subMinutes(15)->subSeconds(1),
                'end_time' => $now->copy()->subMinutes(15),
                'error_message' => 'Client error: `GET https://rss.tempo.co/tekno` resulted in a `403 Forbidden` response (Cloudflare bot challenge detected - User-Agent rate limited)',
                'created_at' => $now->copy()->subMinutes(15),
                'updated_at' => $now->copy()->subMinutes(15),
            ],
            [
                'source_name' => 'CNN Indonesia Nasional',
                'url' => 'https://www.cnnindonesia.com/nasional/rss',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(18)->subSeconds(3),
                'end_time' => $now->copy()->subMinutes(18),
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(18),
                'updated_at' => $now->copy()->subMinutes(18),
            ],
            [
                'source_name' => 'CNN Indonesia Teknologi',
                'url' => 'https://www.cnnindonesia.com/teknologi/rss',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(22)->subSeconds(2),
                'end_time' => $now->copy()->subMinutes(22),
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(22),
                'updated_at' => $now->copy()->subMinutes(22),
            ],
            [
                'source_name' => 'Kompas Edukasi & Pendidikan',
                'url' => 'https://edukasi.kompas.com',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(28)->subSeconds(4),
                'end_time' => $now->copy()->subMinutes(28),
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(28),
                'updated_at' => $now->copy()->subMinutes(28),
            ],
            [
                'source_name' => 'Kompas Sains & Teknologi',
                'url' => 'https://sains.kompas.com',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(34)->subSeconds(3),
                'end_time' => $now->copy()->subMinutes(34),
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(34),
                'updated_at' => $now->copy()->subMinutes(34),
            ],
            [
                'source_name' => 'Detik News Terpopuler',
                'url' => 'https://news.detik.com/berita/rss',
                'status' => 'failed',
                'start_time' => $now->copy()->subMinutes(41)->subSeconds(5),
                'end_time' => $now->copy()->subMinutes(41),
                'error_message' => 'Client error: `GET https://news.detik.com/berita/rss` resulted in a `404 Not Found` response (Feed endpoint relocated)',
                'created_at' => $now->copy()->subMinutes(41),
                'updated_at' => $now->copy()->subMinutes(41),
            ],
            [
                'source_name' => 'Republika Terkini',
                'url' => 'https://republika.co.id/rss',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(49)->subSeconds(2),
                'end_time' => $now->copy()->subMinutes(49),
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(49),
                'updated_at' => $now->copy()->subMinutes(49),
            ],
            [
                'source_name' => 'Bisnis Indonesia Market',
                'url' => 'https://market.bisnis.com/rss',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(58)->subSeconds(3),
                'end_time' => $now->copy()->subMinutes(58),
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(58),
                'updated_at' => $now->copy()->subMinutes(58),
            ],
            [
                'source_name' => 'Liputan6 News Feed',
                'url' => 'https://www.liputan6.com/feed/news',
                'status' => 'failed',
                'start_time' => $now->copy()->subHours(1)->subMinutes(12)->subSeconds(6),
                'end_time' => $now->copy()->subHours(1)->subMinutes(12),
                'error_message' => 'cURL error 28: Operation timed out after 5001 milliseconds with 0 bytes received (Gateway read timeout)',
                'created_at' => $now->copy()->subHours(1)->subMinutes(12),
                'updated_at' => $now->copy()->subHours(1)->subMinutes(12),
            ],
            [
                'source_name' => 'Antara Ekonomi & Keuangan',
                'url' => 'https://www.antaranews.com/rss/ekonomi.xml',
                'status' => 'success',
                'start_time' => $now->copy()->subHours(1)->subMinutes(25)->subSeconds(2),
                'end_time' => $now->copy()->subHours(1)->subMinutes(25),
                'error_message' => null,
                'created_at' => $now->copy()->subHours(1)->subMinutes(25),
                'updated_at' => $now->copy()->subHours(1)->subMinutes(25),
            ],
            [
                'source_name' => 'Antara Politik & Hukum',
                'url' => 'https://www.antaranews.com/rss/politik.xml',
                'status' => 'success',
                'start_time' => $now->copy()->subHours(1)->subMinutes(40)->subSeconds(3),
                'end_time' => $now->copy()->subHours(1)->subMinutes(40),
                'error_message' => null,
                'created_at' => $now->copy()->subHours(1)->subMinutes(40),
                'updated_at' => $now->copy()->subHours(1)->subMinutes(40),
            ],
        ];

        foreach ($logs as $log) {
            ScrapingLog::create($log);
        }
    }
}
