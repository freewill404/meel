<?php

namespace Tests\Unit\Meel\Schedules;

use App\Meel\Schedules\WhatString;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatStringTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_applies_what_formats()
    {
        $this->assertWhatFormat('Do the dishes', 'Do the dishes');

        $this->assertWhatFormat('times sent: 3 (13)', 'times sent: %t (%f[W])');

        $this->assertWhatFormat('times sent: 3 (3)', 'times sent: %t (%f[%\t])');

        $this->assertWhatFormat('4 - 2 - 3 - 2018', '%t+1 - %d+2 - %a+3 - %f[Y]');
    }

    /** @test */
    function it_preserves_urls_with_percent_signs()
    {
        $user = factory(User::class)->create();

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'https://example.com/aa%fest https://example.com/aa%dest %t https://example.com/%fest',
            'when' => 'in 1 hour',
        ]);

        $this->assertSame(
            'https://example.com/aa%fest https://example.com/aa%dest 1 https://example.com/%fest',
            WhatString::format($emailSchedule)
        );
    }

    /** @test */
    function it_preserves_urls_with_already_encoded_percent_signs()
    {
        $user = factory(User::class)->create();

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'http://example.com/aa%25fest',
            'when' => 'in 1 hour',
        ]);

        $this->assertSame(
            'http://example.com/aa%25fest',
            WhatString::format($emailSchedule)
        );
    }

    private function assertWhatFormat($expected, $what, $timezone = 'Europe/Amsterdam')
    {
        $this->rewindTimeInHours(3);

        $user = factory(User::class)->create([
            'timezone' => $timezone,
        ]);

        $emailSchedule = $user->emailSchedules()->create([
            'what' => $what,
            'when' => 'in 1 hour',
        ]);

        $this->progressTimeInHours(1);

        $emailSchedule->emailScheduleHistories()->create([
            'sent_at' => secondless_now($user->timezone),
        ]);

        $this->progressTimeInHours(1);

        $emailSchedule->emailScheduleHistories()->create([
            'sent_at' => secondless_now($user->timezone),
        ]);

        $this->progressTimeInHours(1);

        $emailSchedule->refresh();

        $this->assertSame(
            $expected,
            WhatString::format($emailSchedule)
        );
    }
}
