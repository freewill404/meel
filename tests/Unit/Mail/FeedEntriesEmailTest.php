<?php

namespace Tests\Unit\Mail;

use App\Mail\FeedEntriesEmail;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WorksWithFeedItems;

class FeedEntriesEmailTest extends TestCase
{
    use RefreshDatabase, WorksWithFeedItems;

    protected $mailFake = false;

    /** @test */
    function it_renders_the_email()
    {
        $user = factory(User::class)->create();

        $user->feeds()->save(
            $feed = factory(Feed::class)->make(['url' => 'https://www.sjorsottjes.com/feed'])
        );

        $feedEntries = $this->getFeedEntries(2);

        $email = new FeedEntriesEmail($feed, $feedEntries);

        $this->assertMatchesSnapshot(
            $email->render()
        );

        $this->assertSame('2 new sjorsottjes.com entries', $email->subject);
    }

    /** @test */
    function it_uses_the_users_timezone()
    {
        $user = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);

        $user->feeds()->save(
            $feed = factory(Feed::class)->make()
        );

        $feedEntries = $this->getFeedEntries(2);

        // Server time
        $this->assertSame('2003-06-03 11:39:21', (string) $feedEntries[0]->publishedAt);

        $email = new FeedEntriesEmail($feed, $feedEntries);

        $view = $email->render();

        // China time
        $this->assertContains('2003-06-03 17:39:21', $view);
    }
}
