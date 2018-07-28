<?php

namespace App\Events\Feeds;

use App\Events\BaseEvent;
use App\Models\Feed;

class FeedPollFailed extends BaseEvent
{
    public $feed;

    public $errorMessage;

    public function __construct(Feed $feed, string $errorMessage)
    {
        $this->feed = $feed;

        $this->errorMessage = $errorMessage;
    }
}
