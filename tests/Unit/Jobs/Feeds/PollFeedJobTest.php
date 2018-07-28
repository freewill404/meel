<?php

namespace Tests\Unit\Jobs\Feeds;

use App\Events\Feeds\FeedNotPolled;
use App\Events\Feeds\FeedPolled;
use App\Events\Feeds\FeedPollFailed;
use App\Jobs\Feeds\PollFeedJob;
use App\Models\Feed;
use App\Models\User;
use App\Support\Facades\Guzzler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PollFeedJobTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $feed;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->user->feeds()->save(
            $this->feed = factory(Feed::class)->make()
        );

        Event::fake();
    }

    /** @test */
    function it_does_not_poll_if_the_user_has_no_emails_left()
    {
        $this->feed->user->update(['emails_left' => 0]);

        PollFeedJob::dispatchNow($this->feed);

        Event::assertDispatchedTimes(FeedNotPolled::class, 1);

        Event::assertDispatched(FeedNotPolled::class, function (FeedNotPolled $event) {
            return $event->feed->id === $this->feed->id;
        });
    }

    /** @test */
    function it_polls_the_feed_url()
    {
        Guzzler::pushFile($this->testFilePath.'feeds/001-rss-2.0.txt');

        PollFeedJob::dispatchNow($this->feed);

        Event::assertDispatchedTimes(FeedPolled::class, 1);

        Event::assertDispatched(FeedPolled::class, function (FeedPolled $event) {
            if ($event->feed->id === $this->feed->id) {
                $this->assertMatchesJsonSnapshot(
                    $event->feedEntries->toJson()
                );

                return true;
            }

            return false;
        });
    }

    /** @test */
    function it_dispatches_an_event_on_non_200_status_codes()
    {
        Guzzler::pushString('error', 404);

        $this->assertPollFails('meel.feed-http-error-404');
    }

    /** @test */
    function it_dispatches_an_event_when_a_feed_cant_be_parsed()
    {
        Guzzler::pushString('valid page, but not a feed', 200);

        $this->assertPollFails('meel.feed-parse-error');
    }

    private function assertPollFails($message)
    {
        PollFeedJob::dispatchNow($this->feed);

        Event::assertDispatchedTimes(FeedPollFailed::class, 1);

        Event::assertDispatched(FeedPollFailed::class, function (FeedPollFailed $event) use ($message) {
            return $event->feed->id === $this->feed->id && $event->errorMessage === $message;
        });
    }
}
