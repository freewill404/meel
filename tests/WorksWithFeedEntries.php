<?php

namespace Tests;

use App\Meel\Feeds\FeedEntryCollection;

trait WorksWithFeedEntries
{
    protected function getFeedEntry($index)
    {
        static $feedEntries = null;

        if (is_null($feedEntries )) {
            $feedEntryCollection = new FeedEntryCollection(
                file_get_contents($this->testFilePath.'feeds/001-rss-2.0.txt')
            );

            $feedEntries = $feedEntryCollection->entries();
        }

        return $feedEntries[$index];
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
