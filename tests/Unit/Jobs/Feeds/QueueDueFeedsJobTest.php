<?php

namespace Tests\Unit\Jobs\Feeds;

use App\Jobs\Feeds\PollFeedJob;
use App\Jobs\Feeds\QueueDueFeedsJob;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueDueFeedsJobTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Queue::fake();
    }

    /** @test */
    function it_dispatches_jobs_for_polling_feeds()
    {
        $user1 = factory(User::class)->create();
        $user1->feeds()->save(
            $feed1 = factory(Feed::class)->make(['when' => 'every 2 days'])
        );

        $user2 = factory(User::class)->create();
        $user2->feeds()->save(
            $feed2 = factory(Feed::class)->make(['when' => 'every 3 days'])
        );

        $this->progressTimeInDays(2);

        (new QueueDueFeedsJob)->handle();

        Queue::assertPushedTimes(PollFeedJob::class, 1);

        $this->assertJobQueued($feed1);
    }

    /** @test */
    function it_dispatches_jobs_for_feeds_that_should_have_been_polled_already()
    {
        $user1 = factory(User::class)->create();
        $user1->feeds()->save(
            $feed1 = factory(Feed::class)->make(['when' => 'every day'])
        );

        $this->progressTimeInDays(7);

        (new QueueDueFeedsJob)->handle();

        Queue::assertPushedTimes(PollFeedJob::class, 1);

        $this->assertJobQueued($feed1);
    }

    private function assertJobQueued(Feed $feed)
    {
        Queue::assertPushed(PollFeedJob::class, function (PollFeedJob $job) use ($feed) {
            return $job->feed->id === $feed->id;
        });

        return $this;
    }
}
