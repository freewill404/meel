<?php

namespace Tests\Unit\Meel\Feeds;

use App\Meel\Feeds\FeedItems;
use Carbon\Carbon;
use Tests\TestCase;

class FeedItemsTest extends TestCase
{
    /** @test */
    function it_loads_feeds_from_string()
    {
        $feedItems = new FeedItems(
            file_get_contents($this->testFilePath.'feeds/001-rss-2.0.txt')
        );

        $items = $feedItems->items();

        $this->assertCount(4, $items);

        $this->assertSame('The Engine That Does More', $items[2]->title);
        $this->assertSame('http://liftoff.msfc.nasa.gov/news/2003/news-VASIMR.asp', $items[2]->url);
        $this->assertTrue($items[2]->publishedAt instanceof Carbon);
    }

    /** @test */
    function it_converts_the_feed_entry_timezone_to_the_servers_timezone()
    {
        $item = (new FeedItems(
            file_get_contents($this->testFilePath.'feeds/001-rss-2.0.txt')
        ))->items()[2];

        $carbon = $item->publishedAt;

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
        $feedItems = new FeedItems(
            file_get_contents($this->testFilePath.'feeds/001-rss-2.0.txt')
        );

        $items = $feedItems->itemsSince('2003-05-28 12:00:00');

        $this->assertCount(2, $items);

        $this->assertSame('2003-06-03 11:39:21', (string) $items[0]->publishedAt);
        $this->assertSame('2003-05-30 13:06:42', (string) $items[1]->publishedAt);
    }

    /** @test */
    function items_since_only_returns_items_after_the_given_datetime()
    {
        $feedItems = new FeedItems(
            file_get_contents($this->testFilePath.'feeds/001-rss-2.0.txt')
        );

        // This is one second after one of the items was published.
        $items = $feedItems->itemsSince('2003-05-27 10:37:33');

        $this->assertCount(2, $items);
        $this->assertSame('2003-06-03 11:39:21', (string) $items[0]->publishedAt);
        $this->assertSame('2003-05-30 13:06:42', (string) $items[1]->publishedAt);

        // This is the exact moment one of the entries was published.
        $items = $feedItems->itemsSince('2003-05-27 10:37:32');

        $this->assertCount(2, $items);
        $this->assertSame('2003-06-03 11:39:21', (string) $items[0]->publishedAt);
        $this->assertSame('2003-05-30 13:06:42', (string) $items[1]->publishedAt);


        // This is one second before one of the items was published.
        $items = $feedItems->itemsSince('2003-05-27 10:37:31');

        $this->assertCount(3, $items);
        $this->assertSame('2003-06-03 11:39:21', (string) $items[0]->publishedAt);
        $this->assertSame('2003-05-30 13:06:42', (string) $items[1]->publishedAt);
        $this->assertSame('2003-05-27 10:37:32', (string) $items[2]->publishedAt);
    }
}
