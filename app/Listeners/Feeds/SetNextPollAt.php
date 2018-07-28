<?php

namespace App\Listeners\Feeds;

use App\Events\Feeds\FeedCreating;
use App\Events\Feeds\FeedNotPolled;
use App\Events\Feeds\FeedPolled;
use App\Events\Feeds\FeedPollFailed;

class SetNextPollAt
{
    /**
     * @param $event FeedCreating|FeedPolled|FeedNotPolled|FeedPollFailed
     */
    public function handle($event)
    {
        $method = $event instanceof FeedCreating ? 'fill' : 'update';

        $event->feed->{$method}([
            'next_poll_at'    => (string) $event->feed->getNextPollDate(),
            'last_polled_at'  => secondless_now(),
            'last_poll_error' => $event->errorMessage ?? null,
        ]);
    }
}
