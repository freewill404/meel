<?php

namespace App\Meel\Feeds;

use Carbon\Carbon;
use Zend\Feed\Reader\Entry\EntryInterface;

class FeedItem
{
    public $title;

    public $url;

    /** @var Carbon $publishedAt */
    public $publishedAt;

    public function __construct(EntryInterface $item)
    {
        $this->title = $item->getTitle();

        $this->url = $item->getPermalink();

        $this->publishedAt = Carbon::instance(
            $item->getDateCreated()
        )->setTimezone('Europe/Amsterdam');
    }

    public function toArray()
    {
        return [
            'title'       => $this->title,
            'url'         => $this->url,
            'publishedAt' => (string) $this->publishedAt,
        ];
    }
}
