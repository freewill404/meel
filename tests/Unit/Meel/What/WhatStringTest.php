<?php

namespace Tests\Unit\Meel\What;

use App\Meel\What\WhatString;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatStringTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_applies_what_formats()
    {
        $user = factory(User::class)->create();

        $schedule = $user->schedules()->create([
            'what'         => 'NONE',
            'when'         => 'in 1 hour',
            'times_sent'   => 2,
            'last_sent_at' => '2018-03-28 11:00:00', // an hour ago
        ]);

        $this->assertWhatFormat('Do the dishes', 'Do the dishes', $schedule);

        $this->assertWhatFormat('times sent: 3 (13)', 'times sent: %t (%f[W])', $schedule);

        $this->assertWhatFormat('times sent: 3 (3)', 'times sent: %t (%f[%\t])', $schedule);

        $this->assertWhatFormat('4 - 2 - 3 - 2018', '%t+1 - %d+2 - %a+3 - %f[Y]', $schedule);
    }

    /** @test */
    function it_preserves_urls_with_percent_signs()
    {
        $user = factory(User::class)->create();

        $schedule = $user->schedules()->create([
            'what' => 'https://example.com/aa%fest https://example.com/aa%dest %t https://example.com/%fest',
            'when' => 'in 1 hour',
        ]);

        $this->assertSame(
            'https://example.com/aa%fest https://example.com/aa%dest 1 https://example.com/%fest',
            WhatString::format($schedule)
        );
    }

    /** @test */
    function it_preserves_urls_with_already_encoded_percent_signs()
    {
        $user = factory(User::class)->create();

        $schedule = $user->schedules()->create([
            'what' => 'http://example.com/aa%25fest',
            'when' => 'in 1 hour',
        ]);

        $this->assertSame(
            'http://example.com/aa%25fest',
            WhatString::format($schedule)
        );
    }

    private function assertWhatFormat($expected, $what, Schedule $schedule)
    {
        $schedule->update(['what' => $what]);

        $this->assertSame(
            $expected,
            WhatString::format($schedule)
        );
    }
}
