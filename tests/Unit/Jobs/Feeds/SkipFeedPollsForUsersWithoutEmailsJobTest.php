<?php

namespace Tests\Unit\Jobs\Feeds;

use App\Jobs\Feeds\SkipFeedPollsForUsersWithoutEmailsJob;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkipFeedPollsForUsersWithoutEmailsJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_updates_feeds_last_polled_at_datetime_for_users_without_emails()
    {
        $user1 = factory(User::class)->create();
        $user1->feeds()->save(
            $feed1 = factory(Feed::class)->make()
        );

        $user2 = factory(User::class)->create(['emails_left' => 0]);
        $user2->feeds()->save(
            $feed2 = factory(Feed::class)->make()
        );

        $this->assertSame('2018-03-28 12:00:15', (string) $feed1->last_polled_at);
        $this->assertSame('2018-03-28 12:00:15', (string) $feed2->last_polled_at);

        $this->progressTimeInDays(1);

        (new SkipFeedPollsForUsersWithoutEmailsJob)->handle();

        $this->assertSame('2018-03-28 12:00:15', (string) $feed1->refresh()->last_polled_at);
        $this->assertSame('2018-03-29 12:00:15', (string) $feed2->refresh()->last_polled_at);
    }
}
