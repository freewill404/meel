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

    public function updating(Feed $feed)
    {
        // Because Feed schedules must be recurring and therefor can't be
        // relative, updating the "next_poll_at" date will always return
        // the same value, even if the schedule was not changed.
        $feed->next_poll_at = (string) $feed->getNextPollDate();
    }
}
