<?php

namespace App\Listeners\Feeds;

use App\Events\Feeds\FeedEmailNotSent;
use App\Events\Feeds\FeedEmailSent;
use App\Models\SiteStats;

class UpdateFeedStats
{
    /**
     * @param $event FeedEmailSent|FeedEmailNotSent
     */
    public function handle($event)
    {
        $event instanceof FeedEmailSent
            ? $this->sent($event)
            : $this->notSent($event);
    }

    protected function sent(FeedEmailSent $event)
    {
        SiteStats::incrementFeedEmailsSent();

        $event->feed->user->increment('feed_emails_sent');

        $event->feed->increment('emails_sent');
    }

    protected function notSent(FeedEmailNotSent $event)
    {
        SiteStats::incrementFeedEmailsNotSent();

        $event->feed->user->increment('feed_emails_not_sent');
    }
}
