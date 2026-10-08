<?php

namespace App\Http\Controllers;

use App\Models\ScrapingLog;
use App\Services\NewsScraperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScrapingLogController extends Controller
{
    /**
     * Display scraper monitoring dashboard or return JSON logs & summary statistics.
     * GET /scraping-logs
     */
    public function index(Request $request, NewsScraperService $scraperService): View|JsonResponse
    {
        $logs = ScrapingLog::latest()->take(50)->get();

        $succeededCount = ScrapingLog::where('status', 'success')->count();
        $failedCount = ScrapingLog::where('status', 'failed')->count();
        $totalCount = $succeededCount + $failedCount;

        // Active scrapers: count of configured sources in service
        $activeScrapersCount = count($scraperService->getSources());

        // Proxy Health: success rate percentage
        $healthPercentage = $totalCount > 0 ? round(($succeededCount / $totalCount) * 100, 1) : 100.0;
        $proxyHealth = "{$healthPercentage}%";

        $summaryStats = [
            'succeeded_jobs_count' => $succeededCount,
            'failed_jobs_count' => $failedCount,
            'active_scrapers_count' => $activeScrapersCount,
            'proxy_health' => $proxyHealth,
            'total_jobs_count' => $totalCount,
        ];

        if ($request->wantsJson() || $request->is('api/*')) {
            $formattedLogs = $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'source_name' => $log->source_name,
                    'url' => $log->url,
                    'status' => $log->status,
                    'start_time' => $log->start_time?->toIso8601String(),
                    'end_time' => $log->end_time?->toIso8601String(),
                    'duration' => $log->duration_formatted,
                    'error_message' => $log->error_message,
                    'created_at' => $log->created_at?->toIso8601String(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'summary_stats' => $summaryStats,
                'succeeded_jobs_count' => $succeededCount,
                'failed_jobs_count' => $failedCount,
                'active_scrapers_count' => $activeScrapersCount,
                'proxy_health' => $proxyHealth,
                'logs' => $formattedLogs,
            ]);
        }

        return view('pages.scraper-monitor', [
            'logs' => $logs,
            'stats' => $summaryStats,
        ]);
    }

    /**
     * Dedicated API endpoint for scraping logs and summary stats.
     * GET /api/scraping-logs
     */
    public function apiIndex(NewsScraperService $scraperService): JsonResponse
    {
        $logs = ScrapingLog::latest()->take(50)->get();

        $succeededCount = ScrapingLog::where('status', 'success')->count();
        $failedCount = ScrapingLog::where('status', 'failed')->count();
        $activeScrapersCount = count($scraperService->getSources());
        $totalCount = $succeededCount + $failedCount;
        $healthPercentage = $totalCount > 0 ? round(($succeededCount / $totalCount) * 100, 1) : 100.0;
        $proxyHealth = "{$healthPercentage}%";

        $summaryStats = [
            'succeeded_jobs_count' => $succeededCount,
            'failed_jobs_count' => $failedCount,
            'active_scrapers_count' => $activeScrapersCount,
            'proxy_health' => $proxyHealth,
            'total_jobs_count' => $totalCount,
        ];

        $formattedLogs = $logs->map(function ($log) {
            return [
                'id' => $log->id,
                'source_name' => $log->source_name,
                'url' => $log->url,
                'status' => $log->status,
                'start_time' => $log->start_time?->toIso8601String(),
                'end_time' => $log->end_time?->toIso8601String(),
                'duration' => $log->duration_formatted,
                'error_message' => $log->error_message,
                'created_at' => $log->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'summary_stats' => $summaryStats,
            'succeeded_jobs_count' => $succeededCount,
            'failed_jobs_count' => $failedCount,
            'active_scrapers_count' => $activeScrapersCount,
            'proxy_health' => $proxyHealth,
            'logs' => $formattedLogs,
        ]);
    }

    /**
     * Trigger scraping manually from the Web UI or API.
     * POST /run-scraper
     */
    public function trigger(Request $request, NewsScraperService $scraperService): RedirectResponse|JsonResponse
    {
        $result = $scraperService->scrapeAll();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Scraping and topic extraction completed successfully.',
                'result' => $result,
            ]);
        }

        return redirect()->back()->with('success', 'Scraper job executed successfully! Topics and logs have been updated.');
    }

    /**
     * Retrieve single log details for modal viewer.
     * GET /scraping-logs/{log}
     */
    public function show(ScrapingLog $log): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $log->id,
                'source_name' => $log->source_name,
                'url' => $log->url,
                'status' => $log->status,
                'start_time' => $log->start_time?->format('Y-m-d H:i:s'),
                'end_time' => $log->end_time?->format('Y-m-d H:i:s'),
                'duration' => $log->duration_formatted,
                'error_message' => $log->error_message,
                'created_at' => $log->created_at?->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
