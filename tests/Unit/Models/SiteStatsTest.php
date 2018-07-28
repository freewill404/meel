<?php

namespace Tests\Unit\Models;

use App\Models\SiteStats;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteStatsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_finds_or_creates_stats_of_today()
    {
        $this->assertSame(0, SiteStats::count());

        $siteStats = SiteStats::today();

        $siteStats->update(['users_registered' => 10]);

        $sameSiteStats = SiteStats::today();

        $this->assertSame($siteStats->id, $sameSiteStats->id);

        $this->assertSame(10, $siteStats->users_registered);
    }

    /** @test */
    function it_has_correct_defaults_after_being_created()
    {
        $this->assertSame(0, SiteStats::count());

        // This is the way the cron calls this method.
        $siteStats = call_user_func([SiteStats::class, 'today']);

        $this->assertSame(1, SiteStats::count());

        $this->assertSame(0, $siteStats->users_registered);
        $this->assertSame(0, $siteStats->schedules_created);
        $this->assertSame(0, $siteStats->emails_sent);
        $this->assertSame(0, $siteStats->emails_not_sent);
        $this->assertSame(0, $siteStats->feeds_created);
        $this->assertSame(0, $siteStats->feed_polls);
        $this->assertSame(0, $siteStats->feed_emails_sent);
    }
}
