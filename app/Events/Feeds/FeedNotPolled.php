<?php

namespace App\Events\Feeds;

use App\Events\BaseEvent;
use App\Models\Feed;

class FeedNotPolled extends BaseEvent
{
    public $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }
}
