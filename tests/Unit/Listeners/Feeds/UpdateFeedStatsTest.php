<?php

namespace Tests\Unit\Listeners\Feeds;

use App\Events\Feeds\FeedEmailNotSent;
use App\Events\Feeds\FeedEmailSent;
use App\Listeners\Feeds\UpdateFeedStats;
use App\Models\Feed;
use App\Models\SiteStats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailable;
use Tests\TestCase;

class UpdateFeedStatsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_updates_feed_stats_after_a_feed_email_is_sent()
    {
        $user = factory(User::class)->create();

        /** @var Feed $feed */
        $feed = $user->feeds()->save(
            factory(Feed::class)->make()
        );

        $this->assertSame(0, SiteStats::today()->feed_emails_sent);
        $this->assertSame(0, $user->feed_emails_sent);
        $this->assertSame(0, $feed->emails_sent);

        (new UpdateFeedStats)->handle(
            new FeedEmailSent($feed, new Mailable)
        );

        $this->assertSame(1, SiteStats::today()->feed_emails_sent);
        $this->assertSame(1, $user->refresh()->feed_emails_sent);
        $this->assertSame(1, $feed->refresh()->emails_sent);
    }

    /** @test */
    function it_updates_feed_stats_after_a_feed_email_is_not_sent()
    {
        $user = factory(User::class)->create();

        /** @var Feed $feed */
        $feed = $user->feeds()->save(
            factory(Feed::class)->make()
        );

        $this->assertSame(0, SiteStats::today()->feed_emails_not_sent);
        $this->assertSame(0, $user->feed_emails_not_sent);
        $this->assertSame(0, $feed->emails_sent);

        (new UpdateFeedStats)->handle(
            new FeedEmailNotSent($feed, new Mailable)
        );

        $this->assertSame(1, SiteStats::today()->feed_emails_not_sent);
        $this->assertSame(1, $user->refresh()->feed_emails_not_sent);
        $this->assertSame(0, $feed->refresh()->emails_sent);
    }
}
