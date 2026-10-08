<?php

use App\Http\Controllers\ScrapingLogController;
use App\Http\Controllers\TrendingTopicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root URL and Trending Topics Page
Route::get('/', [TrendingTopicController::class, 'index'])->name('home');
Route::get('/trending-topics', [TrendingTopicController::class, 'index'])->name('trending-topics.index');

// Page 2: Web Scraper Monitor
Route::get('/scraping-logs', [ScrapingLogController::class, 'index'])->name('scraping-logs.index');
Route::get('/scraper-monitor', [ScrapingLogController::class, 'index'])->name('scraper-monitor.index');

// Scraper Action & Log Details
Route::post('/run-scraper', [ScrapingLogController::class, 'trigger'])->name('scraper.trigger');
Route::get('/scraping-logs/{log}', [ScrapingLogController::class, 'show'])->name('scraping-logs.show');
