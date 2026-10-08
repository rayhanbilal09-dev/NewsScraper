<?php

use App\Http\Controllers\ScrapingLogController;
use App\Http\Controllers\TrendingTopicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home & Page 1: Trending Topics
Route::get('/', [TrendingTopicController::class, 'index'])->name('home');
Route::get('/trending-topics', [TrendingTopicController::class, 'index'])->name('trending-topics.index');

// Page 2: Web Scraper Monitor / Logs
Route::get('/scraping-logs', [ScrapingLogController::class, 'index'])->name('scraping-logs.index');
Route::get('/scraper-monitor', [ScrapingLogController::class, 'index'])->name('scraper-monitor.index');

// Manual Trigger & Log Detail Modal Endpoint
Route::post('/run-scraper', [ScrapingLogController::class, 'trigger'])->name('scraper.trigger');
Route::get('/scraping-logs/{log}', [ScrapingLogController::class, 'show'])->name('scraping-logs.show');
