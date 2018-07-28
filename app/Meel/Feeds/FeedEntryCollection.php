<?php

namespace App\Meel\Feeds;

use Zend\Feed\Reader\Reader;

class FeedEntryCollection
{
    /**
     * @var array|FeedEntry[]
     */
    protected $entries = [];

    public function __construct(string $feedString)
    {
        $feed = Reader::importString($feedString);

        foreach ($feed as $entry) {
            $this->entries[] = new FeedEntry($entry);
        }
    }

    public function entries()
    {
        return $this->entries;
    }

    /**
     * @param $sinceDateTime
     *
     * @return array|FeedEntry[]
     */
    public function entriesSince($sinceDateTime)
    {
        return collect($this->entries)
            ->filter(function (FeedEntry $entry) use ($sinceDateTime) {
                return $entry->publishedAt->greaterThan($sinceDateTime);
            })
            ->values()
            ->all();
    }

    /**
     * This method is used for making snapshot assertions.
     *
     * @return string
     */
    public function toJson()
    {
        $array = array_map(function (FeedEntry $entry) {
            return $entry->toArray();
        }, $this->entries);

        return json_encode($array);
    }
}
