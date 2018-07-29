<?php

namespace App\Events\Feeds;

use App\Events\BaseEvent;
use App\Models\Feed;
use Illuminate\Mail\Mailable;

class FeedEmailSent extends BaseEvent
{
    public $feed;

    public $email;

    public function __construct(Feed $feed, Mailable $mailable)
    {
        $this->feed = $feed;

        $this->email = $mailable;
    }
}
