<?php

namespace Tests\Unit\Meel\What\Formats;

use App\Meel\What\Formats\DaysSinceCreation;

class DaysSinceCreationTest extends WhatFormatTestCase
{
    protected $whatFormat = DaysSinceCreation::class;

    /** @test */
    function it_only_changes_if_the_string_contains_the_format()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertNoFormatApplied('No formats', $schedule);

        $this->assertNoFormatApplied('%t+2', $schedule);
    }

    /** @test */
    function it_adds_the_days_since_a_schedule_was_created()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertFormattedWhen('created 0 days ago', 'created %a days ago', $schedule);

        $this->assertFormattedWhen('0', '%a', $schedule);

        $this->progressTimeInDays(1);

        $this->assertFormattedWhen('1', '%a', $schedule);

        $this->progressTimeInDays(6);

        $this->assertFormattedWhen('7', '%a', $schedule);
    }

    /** @test */
    function it_can_offset_the_days_ago()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->progressTimeInDays(1);

        $this->assertFormattedWhen('times sent: 3', 'times sent: %a+2', $schedule);

        $this->assertFormattedWhen('-1', '%a-2', $schedule);

        $this->assertFormattedWhen('0', '%a-1', $schedule);

        $this->assertFormattedWhen('1', '%a-0', $schedule);
    }

    /** @test */
    function it_handles_incorrect_formats()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertFormattedWhen('days ago: 1e2', 'days ago: %a+1e2', $schedule);

        $this->assertFormattedWhen('0-', '%a-', $schedule);

        $this->assertFormattedWhen('0+-2', '%a+-2', $schedule);

        $this->assertFormattedWhen('02', '%a2', $schedule);
    }

    /** @test */
    function it_respects_the_users_timezone()
    {
        [$chineseUser, $chineseSchedule] = $this->createUserAndSchedule('Asia/Shanghai');
        [$dutchUser,   $dutchSchedule]   = $this->createUserAndSchedule('Europe/Amsterdam');

        $this->assertFormattedWhen('0', '%a', $dutchSchedule);
        $this->assertFormattedWhen('0', '%a', $chineseSchedule);

        $this->progressTimeInHours(6);

        $this->assertSame(28, now('Europe/Amsterdam')->day);
        $this->assertSame(29, now('Asia/Shanghai')->day);

        $this->assertFormattedWhen('0', '%a', $dutchSchedule);
        $this->assertFormattedWhen('1', '%a', $chineseSchedule);
    }
}
