<?php

namespace App\Console;

use App\Jobs\Feeds\SkipFeedPollsForUsersWithoutEmailsJob;
use App\Jobs\GenerateSitemapJob;
use App\Jobs\QueueDueEmailsJob;
use App\Jobs\Feeds\QueuePollFeedUrlJobsJob;
use App\Jobs\Diagnostic\SendAdminAlertJob;
use App\Jobs\Diagnostic\SendAdminDigestJob;
use App\Models\SiteStats;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Schedules
        $schedule->job(QueueDueEmailsJob::class)->everyMinute();

        // Feeds
        $schedule->job(QueuePollFeedUrlJobsJob::class              )->everyFifteenMinutes();
        $schedule->job(SkipFeedPollsForUsersWithoutEmailsJob::class)->everyFifteenMinutes();

        // Diagnostic
        $schedule->job(SendAdminDigestJob::class)->monthlyOn(1, '07:00');
        $schedule->job(SendAdminAlertJob::class )->dailyAt('17:30');

        // Other
        $schedule->job(GenerateSitemapJob::class   )->dailyAt('2:05');
        $schedule->call([SiteStats::class, 'today'])->twiceDaily(); // Ensure every day has a SiteStats model
    }
}
