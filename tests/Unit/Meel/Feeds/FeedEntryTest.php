<?php

namespace Tests\Unit\Meel\Feeds;

use App\Meel\Feeds\FeedEntry;
use Exception;
use Tests\TestCase;
use Zend\Feed\Reader\Reader;

class FeedEntryTest extends TestCase
{
    /** @test */
    function it_loads_a_feed_entry()
    {
        $feedEntry = $this->createFeedEntry();

        $this->assertSame('Star City', $feedEntry->title);
        $this->assertSame('http://liftoff.msfc.nasa.gov/news/2003/news-starcity.asp', $feedEntry->url);
        $this->assertSame('2003-06-03 11:39:21', (string) $feedEntry->publishedAt);
    }

    /** @test */
    function it_limits_long_titles()
    {
        $feedEntry = $this->createFeedEntry([
            'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi in consequat enim, non efficitur risus. Etiam id dapibus metus. Donec in metus lobortis, mattis est vitae, pulvinar mauris. Nullam sed molestie leo. Fusce interdum ultrices ante, et porta lacus. Curabitur velit metus, ultrices non malesuada ut, hendrerit id erat. Maecenas posuere congue velit porttitor facilisis. Donec id commodo justo. Curabitur cursus lorem sed mattis sodales',
        ]);

        $this->assertSame('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi in consequat enim, non efficitur risu...', $feedEntry->title);
    }

    /** @test */
    function it_has_a_fallback_for_published_date()
    {
        $feedEntry = $this->createFeedEntry([
            'pubDate' => '',
        ]);

        $this->assertNull($feedEntry->publishedAt);
    }

    private function createFeedEntry($attributes = [])
    {
        $string = file_get_contents($this->testFilePath.'feeds/rss-2.0-002.txt');

        $string = strtr($string, [
            '_TITLE_'       => $attributes['title']       ?? 'Star City',
            '_LINK_'        => $attributes['link']        ?? 'http://liftoff.msfc.nasa.gov/news/2003/news-starcity.asp',
            '_DESCRIPTION_' => $attributes['description'] ?? 'How do Americans get ready to work with Russians aboard the International Space Station? They take a crash course in culture, language and protocol at Russia\'s &lt;a href="http://howe.iki.rssi.ru/GCTC/gctc_e.htm"&gt;Star City&lt;/a&gt;.',
            '_PUBDATE_'     => $attributes['pubDate']     ?? 'Tue, 03 Jun 2003 09:39:21 GMT',
            '_GUID_'        => $attributes['guid']        ?? 'http://liftoff.msfc.nasa.gov/2003/06/03.html#item573',
        ]);

        $string = strtr($string, [
            '<title></title>'             => '',
            '<link></link>'               => '',
            '<description></description>' => '',
            '<pubDate></pubDate>'         => '',
            '<guid></guid>'               => '',
        ]);

        foreach (Reader::importString($string) as $entry) {
            return new FeedEntry($entry);
        }

        throw new Exception('???');
    }
}
