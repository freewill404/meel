<?php

namespace App\Events\Feeds;

use App\Events\BaseEvent;
use App\Meel\Feeds\FeedItems;
use App\Models\Feed;

class FeedPolled extends BaseEvent
{
    public $feed;

    public $feedItems;

    public function __construct(Feed $feed, FeedItems $feedItems)
    {
        $this->feed = $feed;

        $this->feedItems = $feedItems;
    }
}
