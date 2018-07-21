<?php

namespace App\Jobs\Feeds;

use App\Jobs\BaseJob;
use App\Models\Feed;
use App\Models\User;

class QueuePollFeedUrlJobsJob extends BaseJob
{
    public function handle()
    {
        $usersWithEmailsLeft = User::where('emails_left', '!=', 0)->pluck('id');

        // To avoid polling feeds that won't result in an email being sent,
        // we only poll feeds from users that still have emails left.
        Feed::whereIn('user_id', $usersWithEmailsLeft)
            ->pluck('url')
            ->unique()
            ->each(function (string $url) {
                PollFeedUrlJob::dispatch($url);
            });
    }
}
