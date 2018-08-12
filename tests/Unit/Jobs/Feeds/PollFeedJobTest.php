<?php

namespace Tests\Unit\Jobs\Feeds;

use App\Events\Feeds\FeedNotPolled;
use App\Events\Feeds\FeedPolled;
use App\Events\Feeds\FeedPollFailed;
use App\Jobs\Feeds\PollFeedJob;
use App\Mail\FeedEntriesEmail;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use SjorsO\Gobble\Facades\Gobble as Guzzle;
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
    }

    /** @test */
    function it_actually_makes_http_requests()
    {
        Guzzle::unfake();

        $this->feed->update([
            'url'            => 'https://seths.blog/feed/',
            'last_polled_at' => now()->subYears(50),
        ]);

        PollFeedJob::dispatchNow($this->feed);

        Mail::assertQueued(FeedEntriesEmail::class, 1);

        $this->assertNull($this->feed->last_poll_error);
    }

    /** @test */
    function it_does_not_poll_if_the_user_has_no_emails_left()
    {
        Event::fake();

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
        Event::fake();

        Guzzle::pushFile($this->testFilePath.'feeds/rss-2.0-001.txt');

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
        Guzzle::pushString('error', 404);

        $this->assertPollFails('meel.feed-http-error-404');
    }

    /** @test */
    function it_dispatches_an_event_when_a_feed_cant_be_parsed()
    {
        Guzzle::pushString('valid page, but not a feed', 200);

        $this->assertPollFails('meel.feed-parse-error');
    }

    private function assertPollFails($message)
    {
        Event::fake();

        PollFeedJob::dispatchNow($this->feed);

        Event::assertDispatchedTimes(FeedPollFailed::class, 1);

        Event::assertDispatched(FeedPollFailed::class, function (FeedPollFailed $event) use ($message) {
            return $event->feed->id === $this->feed->id && $event->errorMessage === $message;
        });
    }
}
