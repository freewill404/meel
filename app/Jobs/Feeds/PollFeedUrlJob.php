<?php

namespace App\Jobs\Feeds;

use App\Jobs\BaseJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class PollFeedUrlJob extends BaseJob implements ShouldQueue
{
    public $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function handle()
    {

    }
}
