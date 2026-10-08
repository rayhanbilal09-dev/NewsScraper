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
     */
    public function index(Request $request, NewsScraperService $scraperService): View|JsonResponse
    {
        $logs = ScrapingLog::latest()->take(50)->get();

        $succeededCount = ScrapingLog::where('status', 'success')->count();
        $failedCount = ScrapingLog::where('status', 'failed')->count();
        $totalCount = $succeededCount + $failedCount;

        // Active scrapers: count of configured sources in service
        $activeScrapersCount = count($scraperService->getSources());

        // Proxy Health: success percentage or connection status
        $healthPercentage = $totalCount > 0 ? round(($succeededCount / $totalCount) * 100, 1) : 100.0;
        $proxyHealth = $healthPercentage >= 95 ? "{$healthPercentage}% (Optimal)" : ($healthPercentage >= 80 ? "{$healthPercentage}% (Good)" : "{$healthPercentage}% (Attention Needed)");

        $stats = [
            'succeeded_jobs_count' => $succeededCount,
            'failed_jobs_count' => $failedCount,
            'active_scrapers_count' => $activeScrapersCount,
            'proxy_health' => $proxyHealth,
            'total_jobs_count' => $totalCount,
        ];

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'summary_stats' => $stats,
                'logs' => $logs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'source_name' => $log->source_name,
                        'url' => $log->url,
                        'status' => $log->status,
                        'start_time' => $log->start_time?->toIso8601String(),
                        'end_time' => $log->end_time?->toIso8601String(),
                        'duration' => $log->duration_formatted,
                        'records_count' => $log->records_count,
                        'error_message' => $log->error_message,
                    ];
                }),
            ]);
        }

        return view('pages.scraper-monitor', [
            'logs' => $logs,
            'stats' => $stats,
        ]);
    }

    /**
     * Trigger scraping manually from the Web UI or API.
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
                'records_count' => $log->records_count,
                'error_message' => $log->error_message,
                'created_at' => $log->created_at?->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
