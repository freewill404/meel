<?php

namespace Tests;

use App\Meel\Feeds\FeedItems;

trait WorksWithFeedItems
{
    protected function getFeedEntry($index)
    {
        static $feedItems = null;

        if (is_null($feedItems )) {
            $feed = new FeedItems(
                file_get_contents($this->testFilePath.'feeds/001-rss-2.0.txt')
            );

            $feedItems = $feed->items();
        }

        return $feedItems[$index];
    }

    protected function getFeedEntries($count)
    {
        $feedEntries = [];

        for ($i = 0; $i < $count; $i++) {
            $feedEntries[] = $this->getFeedEntry($i);
        }

        return $feedEntries;
    }
}
