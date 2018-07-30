<?php

namespace Tests\Unit\Meel\Feeds;

use App\Meel\Feeds\FeedEntryCollection;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\WorksWithFeedEntries;

class FeedEntriesCollectionTest extends TestCase
{
    use WorksWithFeedEntries;

    /** @test */
    function it_loads_rss_2_feeds()
    {
        $rss2Feed = new FeedEntryCollection(
            file_get_contents($this->testFilePath.'feeds/rss-2.0-001.txt')
        );

        $entries = $rss2Feed->entries();

        $this->assertCount(4, $entries);

        $this->assertTrue($entries[0]->publishedAt instanceof Carbon);

        $this->assertMatchesJsonSnapshot(
            $rss2Feed->toJson()
        );
    }

    /** @test */
    function it_loads_rss_1_feeds()
    {
        $rss1Feed = new FeedEntryCollection(
            file_get_contents($this->testFilePath.'feeds/rss-1.0-001.txt')
        );

        $entries = $rss1Feed->entries();

        $this->assertCount(15, $entries);

        $this->assertTrue($entries[0]->publishedAt instanceof Carbon);

        $this->assertMatchesJsonSnapshot(
            $rss1Feed->toJson()
        );
    }

    /** @test */
    function it_loads_atom_feeds()
    {
        $atomFeed = new FeedEntryCollection(
            file_get_contents($this->testFilePath.'feeds/atom-001.txt')
        );

        $entries = $atomFeed->entries();

        $this->assertCount(1, $entries);

        $this->assertTrue($entries[0]->publishedAt instanceof Carbon);

        $this->assertMatchesJsonSnapshot(
            $atomFeed->toJson()
        );
    }

    /** @test */
    function it_converts_the_feed_entry_timezone_to_the_servers_timezone()
    {
        $entry = $this->getFeedEntry(2);

        $carbon = $entry->publishedAt;

        // This feed item has the following publish date:
        //   <pubDate>Tue, 27 May 2003 08:37:32 GMT</pubDate>
        //
        // GMT is 2 hours earlier than "Europe/Amsterdam"
        $this->assertSame('2003-05-27 10:37:32', (string) $carbon);

        $this->assertSame('Europe/Amsterdam', $carbon->timezoneName);
    }

    /** @test */
    function it_gets_items_since_a_specific_datetime()
    {
        $feedEntryCollection = new FeedEntryCollection(
            file_get_contents($this->testFilePath.'feeds/rss-2.0-001.txt')
        );

        $entries = $feedEntryCollection->entriesSince('2003-05-28 12:00:00');

        $this->assertCount(2, $entries);

        $this->assertSame('2003-06-03 11:39:21', (string) $entries[0]->publishedAt);
        $this->assertSame('2003-05-30 13:06:42', (string) $entries[1]->publishedAt);
    }

    /** @test */
    function items_since_only_returns_items_after_the_given_datetime()
    {
        $feedEntryCollection = new FeedEntryCollection(
            file_get_contents($this->testFilePath.'feeds/rss-2.0-001.txt')
        );

        // This is one second after one of the items was published.
        $entries = $feedEntryCollection->entriesSince('2003-05-27 10:37:33');

        $this->assertCount(2, $entries);
        $this->assertSame('2003-06-03 11:39:21', (string) $entries[0]->publishedAt);
        $this->assertSame('2003-05-30 13:06:42', (string) $entries[1]->publishedAt);

        // This is the exact moment one of the entries was published.
        $entries = $feedEntryCollection->entriesSince('2003-05-27 10:37:32');

        $this->assertCount(2, $entries);
        $this->assertSame('2003-06-03 11:39:21', (string) $entries[0]->publishedAt);
        $this->assertSame('2003-05-30 13:06:42', (string) $entries[1]->publishedAt);


        // This is one second before one of the items was published.
        $entries = $feedEntryCollection->entriesSince('2003-05-27 10:37:31');

        $this->assertCount(3, $entries);
        $this->assertSame('2003-06-03 11:39:21', (string) $entries[0]->publishedAt);
        $this->assertSame('2003-05-30 13:06:42', (string) $entries[1]->publishedAt);
        $this->assertSame('2003-05-27 10:37:32', (string) $entries[2]->publishedAt);
    }
}
