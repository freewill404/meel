<?php

namespace App\Mail;

use App\Meel\Feeds\FeedEntry;
use App\Models\Feed;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedEntryEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $feed;

    public $feedEntry;

    public function __construct(Feed $feed, FeedEntry $feedEntry)
    {
        $this->feed = $feed;

        $this->feedEntry = $feedEntry;
    }

    public function build()
    {
        $domainName = str_ireplace('www.', '', parse_url($this->feed->url, PHP_URL_HOST));

        return $this->subject('New '.$domainName.' entry: '.$this->feedEntry->title)
                    ->view('email.new-feed-entry');
    }
}
