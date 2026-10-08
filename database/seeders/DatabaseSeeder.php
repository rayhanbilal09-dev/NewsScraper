<?php

namespace Database\Seeders;

use App\Models\ScrapingLog;
use App\Models\TrendingTopic;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial news scraper data and trending topics.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Seed Initial Trending Topics
        TrendingTopic::truncate();

        $topics = [
            [
                'topic_name' => 'Topik: #Politik - Kesiapan Logistik Pilkada Serentak & Pengawasan KPU',
                'category' => '#Politik',
                'score_or_count' => 214.85,
                'last_successful_update' => $now,
            ],
            [
                'topic_name' => 'Topik: #Tekno - Percepatan Transformasi Digital & Ekosistem AI Nasional',
                'category' => '#Tekno',
                'score_or_count' => 189.40,
                'last_successful_update' => $now,
            ],
            [
                'topic_name' => 'Topik: #Ekonomi - Penguatan Nilai Tukar Rupiah & Stabilitas Pasar Finansial',
                'category' => '#Ekonomi',
                'score_or_count' => 165.95,
                'last_successful_update' => $now,
            ],
            [
                'topic_name' => 'Topik: #Pendidikan - Revitalisasi Kurikulum Kampus & Beasiswa Riset Sains',
                'category' => '#Pendidikan',
                'score_or_count' => 142.70,
                'last_successful_update' => $now,
            ],
            [
                'topic_name' => 'Topik: #Hukum - Vonis Sidang Tipikor & Transparansi Penegakan Hukum',
                'category' => '#Hukum',
                'score_or_count' => 128.50,
                'last_successful_update' => $now,
            ],
        ];

        foreach ($topics as $topic) {
            TrendingTopic::create($topic);
        }

        // 2. Seed Initial Scraping Logs
        ScrapingLog::truncate();

        $logs = [
            [
                'source_name' => 'Antara News Terkini',
                'url' => 'https://www.antaranews.com/rss/terkini.xml',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(12)->subSeconds(3),
                'end_time' => $now->copy()->subMinutes(12),
                'duration_seconds' => 0.35,
                'records_count' => 50,
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(12),
                'updated_at' => $now->copy()->subMinutes(12),
            ],
            [
                'source_name' => 'Tempo Nasional & Politik',
                'url' => 'https://rss.tempo.co/nasional',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(11)->subSeconds(2),
                'end_time' => $now->copy()->subMinutes(11),
                'duration_seconds' => 0.18,
                'records_count' => 50,
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(11),
                'updated_at' => $now->copy()->subMinutes(11),
            ],
            [
                'source_name' => 'Tempo Bisnis & Ekonomi',
                'url' => 'https://rss.tempo.co/bisnis',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(10)->subSeconds(1),
                'end_time' => $now->copy()->subMinutes(10),
                'duration_seconds' => 0.12,
                'records_count' => 50,
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(10),
                'updated_at' => $now->copy()->subMinutes(10),
            ],
            [
                'source_name' => 'Tempo Teknologi & Sains',
                'url' => 'https://rss.tempo.co/tekno',
                'status' => 'failed',
                'start_time' => $now->copy()->subMinutes(9)->subSeconds(1),
                'end_time' => $now->copy()->subMinutes(9),
                'duration_seconds' => 0.04,
                'records_count' => 0,
                'error_message' => 'Client error: `GET https://rss.tempo.co/tekno` resulted in a `403 Forbidden` response (Cloudflare bot protection)',
                'created_at' => $now->copy()->subMinutes(9),
                'updated_at' => $now->copy()->subMinutes(9),
            ],
            [
                'source_name' => 'CNN Indonesia Nasional',
                'url' => 'https://www.cnnindonesia.com/nasional/rss',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(8)->subSeconds(3),
                'end_time' => $now->copy()->subMinutes(8),
                'duration_seconds' => 0.33,
                'records_count' => 100,
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(8),
                'updated_at' => $now->copy()->subMinutes(8),
            ],
            [
                'source_name' => 'Kompas Edukasi & Pendidikan',
                'url' => 'https://edukasi.kompas.com',
                'status' => 'success',
                'start_time' => $now->copy()->subMinutes(7)->subSeconds(5),
                'end_time' => $now->copy()->subMinutes(7),
                'duration_seconds' => 0.85,
                'records_count' => 12,
                'error_message' => null,
                'created_at' => $now->copy()->subMinutes(7),
                'updated_at' => $now->copy()->subMinutes(7),
            ],
        ];

        foreach ($logs as $log) {
            ScrapingLog::create($log);
        }
    }
}
