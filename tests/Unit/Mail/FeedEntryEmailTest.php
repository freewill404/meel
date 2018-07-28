<?php

namespace Tests\Unit\Mail;

use App\Mail\FeedEntryEmail;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WorksWithFeedEntries;

class FeedEntryEmailTest extends TestCase
{
    use RefreshDatabase, WorksWithFeedEntries;

    protected $mailFake = false;

    /** @test */
    function it_renders_the_email()
    {
        $user = factory(User::class)->create();

        $user->feeds()->save(
            $feed = factory(Feed::class)->make(['url' => 'https://www.sjorsottjes.com/feed'])
        );

        $feedEntry = $this->getFeedEntry(0);

        $email = new FeedEntryEmail($feed, $feedEntry);

        $this->assertMatchesSnapshot(
            $email->render()
        );

        $this->assertSame('New sjorsottjes.com entry: Star City', $email->subject);
    }

    /** @test */
    function it_uses_the_users_timezone()
    {
        $user = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);

        $user->feeds()->save(
            $feed = factory(Feed::class)->make()
        );

        $feedEntry = $this->getFeedEntry(0);

        // Server time
        $this->assertSame('2003-06-03 11:39:21', (string) $feedEntry->publishedAt);

        $email = new FeedEntryEmail($feed, $feedEntry);

        $view = $email->render();

        // China time
        $this->assertContains('2003-06-03 17:39:21', $view);
    }
}
