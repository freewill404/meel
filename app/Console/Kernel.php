<?php

namespace App\Console;

use App\Jobs\QueueDueEmailsJob;
use App\Models\SiteStats;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Sitemap\SitemapGenerator;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(QueueDueEmailsJob::class)->everyMinute();

        $schedule->call(function () {
            $applicationUrl = config('app.url');

            $sitemapFilePath = public_path('sitemap.xml');

            SitemapGenerator::create($applicationUrl)->writeToFile($sitemapFilePath);
        })->dailyAt('2:05');

        // Make sure every date has a SiteStats model.
        $schedule->call(function () {
            SiteStats::today();
        })->dailyAt('00:05');
    }
}
