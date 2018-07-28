<?php

namespace App\Events\Feeds;

use App\Events\BaseEvent;
use App\Meel\Feeds\FeedEntryCollection;
use App\Models\Feed;

class FeedPolled extends BaseEvent
{
    public $feed;

    public $feedEntries;

    public function __construct(Feed $feed, FeedEntryCollection $feedEntries)
    {
        $this->feed = $feed;

        $this->feedEntries = $feedEntries;
    }
}
