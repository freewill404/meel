<?php

namespace App\Listeners\Feeds;

use App\Events\Feeds\FeedPolled;
use App\Events\Feeds\FeedPollFailed;
use App\Models\SiteStats;

class UpdateFeedPollStats
{
    /**
     * @param $event FeedPolled|FeedPollFailed
     */
    public function handle($event)
    {
        SiteStats::incrementFeedPolls();
    }
}
