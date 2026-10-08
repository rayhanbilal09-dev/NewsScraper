<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Schedule the news web scraper to run periodically (e.g. every hour or every 30 minutes)
 */
Schedule::command('app:run-news-scraper')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();
