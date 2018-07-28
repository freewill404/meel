<?php

namespace Tests\Unit\Listeners\Feeds;

use App\Events\Feeds\FeedCreating;
use App\Events\Feeds\FeedNotPolled;
use App\Events\Feeds\FeedPollFailed;
use App\Listeners\Feeds\SetNextPollAt;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetNextPollAtTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        // Disable observers and events
        Feed::flushEventListeners();
        $this->withoutEvents();
    }

    /** @test */
    function it_fills_when_the_feed_is_creating()
    {
        $user = factory(User::class)->create();
        $feed = factory(Feed::class)->make(['user_id' => $user->id]);

        (new SetNextPollAt)->handle(
            new FeedCreating($feed)
        );

        $this->assertFalse($feed->exists());

        $this->assertSame((string) $feed->getNextPollDate(), (string) $feed->next_poll_at);
        $this->assertSame((string) secondless_now(),         (string) $feed->last_polled_at);
        $this->assertNull($feed->last_poll_error);
    }

    /** @test */
    function it_clears_the_last_poll_error_if_there_was_none()
    {
        [$user, $feed] = $this->createUserAndFeed(['last_poll_error' => 'ERROR!']);

        $this->assertSame('ERROR!', $feed->refresh()->last_poll_error);

        (new SetNextPollAt)->handle(
            new FeedNotPolled($feed)
        );

        $this->assertNull($feed->refresh()->last_poll_error);
    }

    /** @test */
    function it_sets_the_last_poll_error_if_there_is_one()
    {
        [$user, $feed] = $this->createUserAndFeed();

        $this->assertNull($feed->refresh()->last_poll_error);

        (new SetNextPollAt)->handle(
            new FeedPollFailed($feed, 'ERROR!')
        );

        $this->assertSame('ERROR!', $feed->refresh()->last_poll_error);
    }

    private function createUserAndFeed(array $feedAttributes = [])
    {
        $user = factory(User::class)->create();

        $user->feeds()->save(
            $feed = factory(Feed::class)->make($feedAttributes + [
                'next_poll_at'   => secondless_now()->addMinutes(15),
                'last_polled_at' => secondless_now(),
            ])
        );

        return [$user, $feed];
    }
}
