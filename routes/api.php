<?php

use App\Http\Controllers\ScrapingLogController;
use App\Http\Controllers\TrendingTopicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Top 5 trending topics for today and last_updated_at
Route::get('/trending-topics', [TrendingTopicController::class, 'apiIndex']);

// Recent scraping logs with summary stats (succeeded_jobs_count, failed_jobs_count, active_scrapers_count)
Route::get('/scraping-logs', [ScrapingLogController::class, 'index']);
Route::get('/scraping-logs/{log}', [ScrapingLogController::class, 'show']);

// Trigger scraper pipeline via API
Route::post('/run-scraper', [ScrapingLogController::class, 'trigger']);
