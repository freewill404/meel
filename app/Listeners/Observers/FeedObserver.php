<?php

namespace App\Listeners\Observers;

use App\Models\Feed;
use App\Models\SiteStats;

class FeedObserver
{
    public function created(Feed $feed)
    {
        SiteStats::incrementFeedsCreated();

        $feed->user->increment('feeds_created');
    }
}
