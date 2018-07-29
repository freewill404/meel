<?php

namespace Tests\Unit\Listeners\Feeds;

use App\Events\Feeds\FeedPolled;
use App\Events\Feeds\FeedPollFailed;
use App\Listeners\Feeds\UpdateFeedPollStats;
use App\Models\Feed;
use App\Models\SiteStats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WorksWithFeedEntries;

class UpdateFeedPollStatsTest extends TestCase
{
    use RefreshDatabase, WorksWithFeedEntries;

    /** @test */
    function it_increments_feed_polls()
    {
        $user = factory(User::class)->create();

        /** @var Feed $feed */
        $feed = $user->feeds()->save(
            factory(Feed::class)->make()
        );

        $this->assertSame(0, SiteStats::today()->feed_polls);

        $feedEntryCollection = $this->getFeedEntryCollection();

        (new UpdateFeedPollStats)->handle(
            new FeedPolled($feed, $feedEntryCollection)
        );

        $this->assertSame(1, SiteStats::today()->feed_polls);

        (new UpdateFeedPollStats)->handle(
            new FeedPollFailed($feed, 'error')
        );

        $this->assertSame(2, SiteStats::today()->feed_polls);
    }
}
