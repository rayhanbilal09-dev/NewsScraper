<?php

namespace App\Console\Commands;

use App\Services\NewsScraperService;
use Illuminate\Console\Command;

class RunNewsScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-news-scraper {--source= : Specific source name or URL to scrape}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute the news web scraper, extract headlines, perform Sastrawi NLP & TextRank keyword ranking, and update trending topics';

    /**
     * Execute the console command.
     */
    public function handle(NewsScraperService $scraperService): int
    {
        $this->info("=================================================");
        $this->info(" Starting Scheduled Indonesian News Web Scraper ");
        $this->info("=================================================");
        $this->line("Time: " . now()->toDateTimeString());

        $this->line("Fetching configured sources & initiating HTTP crawler...");
        $startTime = microtime(true);

        $result = $scraperService->scrapeAll();

        $elapsed = round(microtime(true) - $startTime, 2);
        $this->newLine();
        $this->info("Scraping execution completed in {$elapsed}s");

        // Display results table
        $rows = [];
        $succeededCount = 0;
        $failedCount = 0;

        foreach ($result['source_results'] as $res) {
            $isSuccess = $res['status'] === 'success';
            if ($isSuccess) {
                $succeededCount++;
            } else {
                $failedCount++;
            }

            $rows[] = [
                $res['source'],
                mb_strimwidth($res['url'], 0, 40, '...'),
                $isSuccess ? '<info>SUCCESS</info>' : '<error>FAILED</error>',
                $res['duration'] . 's',
                $res['records_count'] . ' recs',
                $isSuccess ? '-' : mb_strimwidth($res['error'] ?? 'Unknown error', 0, 30, '...'),
            ];
        }

        $this->table(
            ['Source', 'URL', 'Status', 'Duration', 'Records', 'Error Details'],
            $rows
        );

        $this->newLine();
        $this->info("--- Top 5 Trending Topics (NLP & TextRank) ---");
        
        $topicRows = [];
        foreach ($result['trending_topics'] as $index => $topic) {
            $topicRows[] = [
                '#' . ($index + 1),
                $topic->topic_name,
                $topic->category,
                $topic->score_or_count,
                $topic->last_successful_update?->format('Y-m-d H:i:s'),
            ];
        }

        $this->table(
            ['Rank', 'Topic', 'Category', 'Score', 'Last Updated'],
            $topicRows
        );

        $this->info("Summary: {$succeededCount} succeeded, {$failedCount} failed, {$result['total_articles']} total articles processed.");

        return Command::SUCCESS;
    }
}
