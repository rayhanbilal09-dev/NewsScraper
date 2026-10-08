<?php

namespace Tests\Feature;

use App\Models\ScrapingLog;
use App\Models\TrendingTopic;
use App\Services\NewsScraperService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsScraperTest extends TestCase
{
    use RefreshDatabase;

    public function test_trending_topics_web_page_renders_successfully(): void
    {
        TrendingTopic::create([
            'topic_name' => 'Topik: #Politik - Pilkada Serentak',
            'category' => '#Politik',
            'score_or_count' => 15.5,
            'last_successful_update' => Carbon::now(),
        ]);

        $response = $this->get('/trending-topics');

        $response->assertStatus(200);
        $response->assertSee('Trending Topics');
        $response->assertSee('Last updated at:');
        $response->assertSee('Topik: #Politik - Pilkada Serentak');
        $response->assertSee('#Politik');
    }

    public function test_scraper_monitor_web_page_renders_successfully(): void
    {
        ScrapingLog::create([
            'source_name' => 'Tempo Nasional',
            'url' => 'https://rss.tempo.co/nasional',
            'status' => 'success',
            'start_time' => Carbon::now()->subSeconds(2),
            'end_time' => Carbon::now(),
            'duration_seconds' => 1.8,
            'records_count' => 45,
        ]);

        ScrapingLog::create([
            'source_name' => 'Failed Feed',
            'url' => 'https://invalid-news.example.com/rss',
            'status' => 'failed',
            'start_time' => Carbon::now()->subSeconds(1),
            'end_time' => Carbon::now(),
            'error_message' => 'Connection refused',
            'duration_seconds' => 0.5,
            'records_count' => 0,
        ]);

        $response = $this->get('/scraping-logs');

        $response->assertStatus(200);
        $response->assertSee('Web Scraper Monitor');
        $response->assertSee('Jobs Succeeded');
        $response->assertSee('Active Scrapers');
        $response->assertSee('Failed Jobs');
        $response->assertSee('Proxy Health');
        $response->assertSee('Recent Scraping Jobs');
        $response->assertSee('Tempo Nasional');
        $response->assertSee('Failed Feed');
        $response->assertSee('View Logs');
    }

    public function test_api_trending_topics_returns_json(): void
    {
        TrendingTopic::create([
            'topic_name' => 'Topik: #Tekno - Transformasi Digital',
            'category' => '#Tekno',
            'score_or_count' => 12.3,
            'last_successful_update' => Carbon::now(),
        ]);

        $response = $this->getJson('/api/trending-topics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'last_updated_at',
                'data',
            ])
            ->assertJsonFragment([
                'topic_name' => 'Topik: #Tekno - Transformasi Digital',
                'category' => '#Tekno',
            ]);
    }

    public function test_api_scraping_logs_returns_json_with_summary_stats(): void
    {
        ScrapingLog::create([
            'source_name' => 'Kompas Pol',
            'url' => 'https://kompas.com',
            'status' => 'success',
            'start_time' => Carbon::now()->subSeconds(2),
            'end_time' => Carbon::now(),
            'duration_seconds' => 2.0,
            'records_count' => 50,
        ]);

        $response = $this->getJson('/api/scraping-logs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'summary_stats' => [
                    'succeeded_jobs_count',
                    'failed_jobs_count',
                    'active_scrapers_count',
                    'proxy_health',
                ],
                'logs',
            ]);
    }

    public function test_nlp_pipeline_with_sastrawi_and_textrank(): void
    {
        $service = app(NewsScraperService::class);

        $sampleArticles = [
            [
                'title' => 'Pemerintah Mempercepat Digitalisasi Pendidikan dan Riset Teknologi di Indonesia',
                'body' => 'Kementerian Komunikasi dan Digital terus mengembangkan infrastruktur internet sekolah di seluruh nusantara. Transformasi digital pendidikan menjadi prioritas utama demi kemajuan bangsa.',
                'source' => 'Antara News',
                'category_hint' => 'Tekno',
            ]
        ];

        $topics = $service->processAndSaveTrendingTopics($sampleArticles);

        $this->assertNotEmpty($topics);
        $this->assertDatabaseHas('trending_topics', [
            'category' => '#Tekno',
        ]);
    }

    public function test_artisan_command_app_run_news_scraper(): void
    {
        $this->artisan('app:run-news-scraper')
            ->assertSuccessful();

        $this->assertGreaterThan(0, ScrapingLog::count());
        $this->assertGreaterThan(0, TrendingTopic::count());
    }
}
