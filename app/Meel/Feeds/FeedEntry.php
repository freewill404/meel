<?php

namespace App\Meel\Feeds;

use Carbon\Carbon;
use Zend\Feed\Reader\Entry\EntryInterface;

class FeedEntry
{
    public $title;

    public $url;

    /** @var Carbon $publishedAt */
    public $publishedAt;

    public function __construct(EntryInterface $entry)
    {
        $this->title = str_limit($entry->getTitle(), 100);

        $this->url = $entry->getPermalink();

        $date = $entry->getDateCreated() ?? $entry->getDateModified() ?? null;

        $this->publishedAt = $date
            ? Carbon::instance($date)->setTimezone('Europe/Amsterdam')
            : null;
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'url' => $this->url,
            'publishedAt' => (string) $this->publishedAt,
        ];
    }
}
