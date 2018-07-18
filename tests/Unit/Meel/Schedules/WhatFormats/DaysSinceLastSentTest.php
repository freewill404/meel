<?php

namespace Tests\Unit\Meel\Schedules\WhatFormats;

use App\Meel\Schedules\WhatFormats\DaysSinceLastSent;
use App\Models\EmailSchedule;

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
        /** @var EmailSchedule $schedule */
        [$user, $schedule] = $this->createUserAndSchedule();

        $schedule->emailScheduleHistories()->create([
            'sent_at' => secondless_now($user->timezone),
        ]);

        $this->assertFormattedWhen('0', '%d', $schedule);

        $this->progressTimeInDays(1);

        $this->assertFormattedWhen('1', '%d', $schedule);

        $this->progressTimeInDays(6);

        $this->assertFormattedWhen('7', '%d', $schedule);

        $schedule->emailScheduleHistories()->create([
            'sent_at' => secondless_now($user->timezone),
        ]);

        $schedule->refresh();

        $this->assertFormattedWhen('0', '%d', $schedule);
    }

    /** @test */
    function it_can_offset_the_days_ago()
    {
        /** @var EmailSchedule $schedule */
        [$user, $schedule] = $this->createUserAndSchedule();

        $schedule->emailScheduleHistories()->create([
            'sent_at' => secondless_now($user->timezone),
        ]);

        $this->progressTimeInDays(1);

        $this->assertFormattedWhen('days since last sent: 3', 'days since last sent: %d+2', $schedule);

        $this->assertFormattedWhen('-1', '%d-2', $schedule);

        $this->assertFormattedWhen('0', '%d-1', $schedule);

        $this->assertFormattedWhen('1', '%d-0', $schedule);
    }

    /** @test */
    function it_handles_incorrect_formats()
    {
        /** @var EmailSchedule $schedule */
        [$user, $schedule] = $this->createUserAndSchedule();

        $schedule->emailScheduleHistories()->create([
            'sent_at' => secondless_now($user->timezone),
        ]);

        $this->assertFormattedWhen('days ago: 1e2', 'days ago: %d+1e2', $schedule);

        $this->assertFormattedWhen('0-', '%d-', $schedule);

        $this->assertFormattedWhen('0+-2', '%d+-2', $schedule);

        $this->assertFormattedWhen('02', '%d2', $schedule);
    }

    /** @test */
    function it_respects_the_users_timezone()
    {
        /** @var EmailSchedule $chineseSchedule */
        /** @var EmailSchedule $dutchSchedule */
        [$chineseUser, $chineseSchedule] = $this->createUserAndSchedule('Asia/Shanghai');
        [$dutchUser,   $dutchSchedule]   = $this->createUserAndSchedule('Europe/Amsterdam');

        $chineseSchedule->emailScheduleHistories()->create([
            'sent_at' => secondless_now($chineseUser->timezone),
        ]);

        $dutchSchedule->emailScheduleHistories()->create([
            'sent_at' => secondless_now($dutchUser->timezone),
        ]);

        $chineseSchedule->refresh();
        $dutchSchedule->refresh();

        $this->assertFormattedWhen('0', '%d', $dutchSchedule);
        $this->assertFormattedWhen('0', '%d', $chineseSchedule);

        $this->progressTimeInHours(6);

        $this->assertSame(28, now('Europe/Amsterdam')->day);
        $this->assertSame(29, now('Asia/Shanghai')->day);

        $this->assertFormattedWhen('0', '%d', $dutchSchedule);
        $this->assertFormattedWhen('1', '%d', $chineseSchedule);
    }
}
