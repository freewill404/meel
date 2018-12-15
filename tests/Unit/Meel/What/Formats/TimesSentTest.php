<?php

namespace Tests\Unit\Meel\What\Formats;

use App\Meel\What\Formats\TimesSent;

class TimesSentTest extends WhatFormatTestCase
{
    protected $whatFormat = TimesSent::class;

    /** @test */
    function it_only_changes_if_the_string_contains_the_format()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertNoFormatApplied('No formats', $schedule);

        $this->assertNoFormatApplied('%d+2', $schedule);
    }

    /** @test */
    function it_adds_the_times_an_email_was_sent()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertFormattedWhen('times sent: 1', 'times sent: %t', $schedule);

        $this->assertFormattedWhen('1', '%t', $schedule);
    }

    /** @test */
    function it_can_offset_the_times_sent()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertFormattedWhen('times sent: 3', 'times sent: %t+2', $schedule);

        $this->assertFormattedWhen('-1', '%t-2', $schedule);

        $this->assertFormattedWhen('1', '%t-0', $schedule);
    }

    /** @test */
    function it_handles_incorrect_formats()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertFormattedWhen('times sent: 2e2', 'times sent: %t+1e2', $schedule);

        $this->assertFormattedWhen('1-', '%t-', $schedule);

        $this->assertFormattedWhen('1+-2', '%t+-2', $schedule);

        $this->assertFormattedWhen('12', '%t2', $schedule);
    }
}
