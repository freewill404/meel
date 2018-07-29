<?php

namespace Tests\Unit\Listeners\Observers;

use App\Listeners\Observers\FeedObserver;
use App\Models\Feed;
use App\Models\SiteStats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedObserverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_increments_feeds_created()
    {
        $user = factory(User::class)->create();

        $this->assertSame(0, SiteStats::today()->feeds_created);
        $this->assertSame(0, $user->refresh()->feeds_created);

        (new FeedObserver)->created(
            factory(Feed::class)->make(['user_id' => $user->id])
        );

        $this->assertSame(1, SiteStats::today()->feeds_created);
        $this->assertSame(1, $user->refresh()->feeds_created);
    }
}
