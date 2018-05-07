<?php

namespace App\Console\Commands;

use App\Jobs\SendScheduledEmailJob;
use App\Models\EmailSchedule;
use Illuminate\Console\Command;

class QueueScheduledEmails extends Command
{
    protected $signature = 'meel:queue-scheduled-emails';

    protected $description = 'Dispatch jobs for emails that should be sent this minute';

    public function handle()
    {


        // User Ids Per Timezone

        // Foreach Timezone
        //      where user_id in UserIdsPerTimezone && next occurrence === now($timezone)

        // ->each(function (EmailSchedule $schedule) {
        //     SendScheduledEmailJob::dispatch($schedule);
        // });
    }
}
