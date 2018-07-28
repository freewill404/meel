<?php

namespace Tests\Unit\Models;

use App\Models\Feed;
use App\Models\User;
use App\Support\DateTime\SecondlessDateTimeString;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_get_the_next_poll_date_on_a_schedule()
    {
        $this->assertNextPollDate('2018-03-29 08:00:00', 'every day at 8:00');
    }

    /** @test */
    function next_poll_date_respects_timezones()
    {
        $this->assertNextPollDate('2018-03-29 02:00:00', 'every day at 8:00', 'Asia/Shanghai');
    }

    /** @test */
    function it_can_get_the_next_poll_date_without_a_schedule()
    {
        $this->assertNextPollDate('2018-03-28 12:15:00', null);
    }

    /** @test */
    function next_poll_date_throws_an_exception_if_next_schedule_is_not_recurring()
    {
        $this->expectException(RuntimeException::class);

        $this->assertNextPollDate('', 'tomorrow');
    }

    private function assertNextPollDate($expected, $when, $timezone = 'Europe/Amsterdam')
    {
        $user = factory(User::class)->create(['timezone' => $timezone]);

        $user->feeds()->save(
            $feed = factory(Feed::class)->make(['when' => $when])
        );

        $nextPollAt = $feed->getNextPollDate();

        $this->assertInstanceOf(SecondlessDateTimeString::class, $nextPollAt);

        $this->assertSame($expected, (string) $nextPollAt);
    }
}
