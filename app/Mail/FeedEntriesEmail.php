<?php

namespace App\Mail;

use App\Models\Feed;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedEntriesEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $feed;

    public $feedEntries;

    public function __construct(Feed $feed, array $feedEntries)
    {
        $this->feed = $feed;

        $this->feedEntries = $feedEntries;
    }

    public function build()
    {
        $domainName = str_ireplace('www.', '', parse_url($this->feed->url, PHP_URL_HOST));

        return $this->subject(count($this->feedEntries).' new '.$domainName.' entries')
                    ->view('email.new-feed-entries');
    }
}
