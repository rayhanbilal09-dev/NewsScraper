<?php

use App\Http\Controllers\ScrapingLogController;
use App\Http\Controllers\TrendingTopicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// GET /trending-topics : Returns the top 5 trending topics for today and last_updated_at
Route::get('/trending-topics', [TrendingTopicController::class, 'apiIndex']);

// GET /scraping-logs : Returns a list of recent scraping logs and summary stats
Route::get('/scraping-logs', [ScrapingLogController::class, 'apiIndex']);
Route::get('/scraping-logs/{log}', [ScrapingLogController::class, 'show']);

// Trigger scraper via API
Route::post('/run-scraper', [ScrapingLogController::class, 'trigger']);
