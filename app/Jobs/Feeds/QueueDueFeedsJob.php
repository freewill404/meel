<?php

namespace App\Jobs\Feeds;

use App\Jobs\BaseJob;
use App\Models\Feed;

class QueueDueFeedsJob extends BaseJob
{
    public function handle()
    {
        Feed::shouldBePolledNow()->each(function (Feed $feed) {
            PollFeedJob::dispatch($feed);
        });
    }
}
