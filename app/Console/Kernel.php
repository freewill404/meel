<?php

namespace App\Console;

use App\Jobs\GenerateSitemapJob;
use App\Jobs\QueueDueEmailsJob;
use App\Jobs\Feeds\QueueDueFeedsJob;
use App\Jobs\Diagnostic\SendAdminAlertJob;
use App\Jobs\Diagnostic\SendAdminDigestJob;
use App\Models\SiteStats;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(QueueDueEmailsJob::class)->everyMinute();
        $schedule->job(QueueDueFeedsJob::class)->everyMinute();

        // Diagnostic
        $schedule->job(SendAdminDigestJob::class)->monthlyOn(1, '07:00');
        $schedule->job(SendAdminAlertJob::class)->dailyAt('17:30');

        // Other
        $schedule->job(GenerateSitemapJob::class)->dailyAt('2:05');
        $schedule->call([SiteStats::class, 'today'])->twiceDaily(); // Ensure every day has a SiteStats model

        $schedule->command('backup:run-configless --disable-notifications --only-db --set-destination-disks=dropbox')->dailyAt('2:10');
    }
}
