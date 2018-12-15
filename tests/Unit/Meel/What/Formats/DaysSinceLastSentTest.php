<?php

namespace Tests\Unit\Meel\What\Formats;

use App\Meel\What\Formats\DaysSinceLastSent;
use App\Models\Schedule;

class DaysSinceLastSentTest extends WhatFormatTestCase
{
    protected $whatFormat = DaysSinceLastSent::class;

    /** @test */
    function it_only_changes_if_the_string_contains_the_format()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertNoFormatApplied('No formats', $schedule);

        $this->assertNoFormatApplied('%t+2', $schedule);
    }

    /** @test */
    function it_has_a_default_if_the_schedule_has_not_been_sent_yet()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertFormattedWhen('last sent n/a days ago', 'last sent %d days ago', $schedule);

        $this->assertFormattedWhen('n/a', '%d+1', $schedule);
    }

    /** @test */
    function it_adds_the_days_since_a_schedule_was_last_sent()
    {
        /** @var Schedule $schedule */
        [$user, $schedule] = $this->createUserAndSchedule();

        $schedule->update(['last_sent_at' => secondless_now()]);

        $this->assertFormattedWhen('0', '%d', $schedule);

        $this->progressTimeInDays(1);

        $this->assertFormattedWhen('1', '%d', $schedule);

        $this->progressTimeInDays(6);

        $this->assertFormattedWhen('7', '%d', $schedule);

        $schedule->update(['last_sent_at' => secondless_now()]);

        $schedule->refresh();

        $this->assertFormattedWhen('0', '%d', $schedule);
    }

    /** @test */
    function it_can_offset_the_days_ago()
    {
        /** @var Schedule $schedule */
        [$user, $schedule] = $this->createUserAndSchedule();

        $schedule->update(['last_sent_at' => secondless_now()]);

        $this->progressTimeInDays(1);

        $this->assertFormattedWhen('days since last sent: 3', 'days since last sent: %d+2', $schedule);

        $this->assertFormattedWhen('-1', '%d-2', $schedule);

        $this->assertFormattedWhen('0', '%d-1', $schedule);

        $this->assertFormattedWhen('1', '%d-0', $schedule);
    }

    /** @test */
    function it_handles_incorrect_formats()
    {
        /** @var Schedule $schedule */
        [$user, $schedule] = $this->createUserAndSchedule();

        $schedule->update(['last_sent_at' => secondless_now()]);

        $this->assertFormattedWhen('days ago: 1e2', 'days ago: %d+1e2', $schedule);

        $this->assertFormattedWhen('0-', '%d-', $schedule);

        $this->assertFormattedWhen('0+-2', '%d+-2', $schedule);

        $this->assertFormattedWhen('02', '%d2', $schedule);
    }

    /** @test */
    function it_respects_the_users_timezone()
    {
        /** @var Schedule $chineseSchedule */
        /** @var Schedule $dutchSchedule */
        [$chineseUser, $chineseSchedule] = $this->createUserAndSchedule('Asia/Shanghai');
        [$dutchUser,   $dutchSchedule]   = $this->createUserAndSchedule('Europe/Amsterdam');

        // Both emails were sent at "2018-03-28 12:00:00" server time,
        // which is "2018-03-28 18:00:00" in Chinese time.
        $chineseSchedule->update(['last_sent_at' => secondless_now()]);
        $dutchSchedule->update(['last_sent_at' => secondless_now()]);

        $this->assertFormattedWhen('0', '%d', $dutchSchedule);
        $this->assertFormattedWhen('0', '%d', $chineseSchedule);

        // It is now tomorrow in China, but not in Europe.
        $this->progressTimeInHours(6);

        $this->assertSame(28, now('Europe/Amsterdam')->day);
        $this->assertSame(29, now('Asia/Shanghai')->day);

        $this->assertFormattedWhen('0', '%d', $dutchSchedule);
        $this->assertFormattedWhen('1', '%d', $chineseSchedule);
    }
}
