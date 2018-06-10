<?php

namespace App\Console;

use App\Jobs\QueueDueEmailsJob;
use App\Models\SiteStats;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(QueueDueEmailsJob::class)->everyMinute();

        // $schedule->command('sitemap:generate')->dailyAt('2:00');

        // Make sure every date has a SiteStats model.
        $schedule->call(function () {
            SiteStats::today();
        })->dailyAt('00:05');
    }
}
