<?php

namespace App\Events\Feeds;

use App\Events\BaseEvent;
use App\Models\Feed;

class FeedCreating extends BaseEvent
{
    public $feed;

    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }
}
