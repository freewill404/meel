<?php

namespace App\Meel\Feeds;

use Zend\Feed\Reader\Reader;

class FeedItems
{
    /**
     * @var array|FeedItem[]
     */
    protected $items = [];

    public function __construct(string $feedString)
    {
        $feed = Reader::importString($feedString);

        foreach ($feed as $item) {
            $this->items[] = new FeedItem($item);
        }
    }

    /**
     * @param $sinceDateTime
     *
     * @return array|FeedItem[]
     */
    public function itemsSince($sinceDateTime)
    {
        return collect($this->items)
            ->filter(function (FeedItem $item) use ($sinceDateTime) {
                return $item->publishedAt->greaterThan($sinceDateTime);
            })
            ->values()
            ->all();
    }

    public function items()
    {
        return $this->items;
    }
}
