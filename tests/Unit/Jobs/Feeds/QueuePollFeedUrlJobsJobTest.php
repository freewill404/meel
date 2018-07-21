<?php

namespace Tests\Unit\Jobs\Feeds;

use App\Jobs\Feeds\PollFeedUrlJob;
use App\Jobs\Feeds\QueuePollFeedUrlJobsJob;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueuePollFeedUrlJobsJobTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Queue::fake();
    }

    /** @test */
    function it_dispatches_jobs_for_polling_feed_urls()
    {
        $user1 = factory(User::class)->create();
        $user1->feeds()->save(
            $feed1 = factory(Feed::class)->make()
        );

        $user2 = factory(User::class)->create();
        $user2->feeds()->save(
            $feed2 = factory(Feed::class)->make()
        );

        (new QueuePollFeedUrlJobsJob)->handle();

        Queue::assertPushedTimes(PollFeedUrlJob::class, 2);

        $this->assertJobQueued($feed1);

        $this->assertJobQueued($feed2);
    }

    /** @test */
    function it_creates_one_job_per_unique_url()
    {
        $user1 = factory(User::class)->create();
        $user1->feeds()->save(
            $feed1 = factory(Feed::class)->make()
        );

        $user2 = factory(User::class)->create();
        $user2->feeds()->save(
            factory(Feed::class)->make(['url' => $feed1->url])
        );

        (new QueuePollFeedUrlJobsJob)->handle();

        $this->assertSame(2, Feed::count());

        Queue::assertPushedTimes(PollFeedUrlJob::class, 1);

        $this->assertJobQueued($feed1);
    }

    /** @test */
    function it_only_queues_jobs_for_users_with_emails_left()
    {
        $user = factory(User::class)->create([
            'emails_left' => 0,
        ]);

        $user->feeds()->save(
            $feed = factory(Feed::class)->make()
        );

        (new QueuePollFeedUrlJobsJob)->handle();

        Queue::assertNothingPushed();
    }

    private function assertJobQueued($url)
    {
        Queue::assertPushed(PollFeedUrlJob::class, function (PollFeedUrlJob $job) use ($url) {
            return $job->url === ($url instanceof Feed ? $url->url : $url);
        });

        return $this;
    }
}
