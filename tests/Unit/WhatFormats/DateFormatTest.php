<?php

namespace Tests\Unit\WhatFormats;

use App\Meel\WhatFormats\DateFormat;

class DateFormatTest extends WhatFormatTestCase
{
    protected $whatFormat = DateFormat::class;

    /** @test */
    function it_only_changes_if_the_string_contains_the_format()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertNoFormatApplied('No formats', $schedule);

        $this->assertNoFormatApplied('%d+2', $schedule);
    }

    /** @test */
    function it_formats_the_current_time()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertFormattedWhen('cool: 12:00:15', 'cool: %f[h:i:s]', $schedule);

        $this->assertFormattedWhen('week: 13', 'week: %f[W]', $schedule);
    }

    /** @test */
    function it_handles_incorrect_formats()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->assertFormattedWhen('%f', '%f', $schedule);
        $this->assertFormattedWhen('%f[]', '%f[]', $schedule);
        $this->assertFormattedWhen('%f]', '%f]', $schedule);
        $this->assertFormattedWhen('%f[', '%f[', $schedule);

        $this->assertFormattedWhen('[-]', '%f[[]-]', $schedule);

        $this->assertFormattedWhen('%f[2018]', '%f[%f[Y]]', $schedule);
    }

    /** @test */
    function it_respects_the_users_timezone()
    {
        [$user, $schedule] = $this->createUserAndSchedule('Asia/Shanghai');

        $this->assertFormattedWhen('cool: 06:00:15', 'cool: %f[h:i:s]', $schedule);
    }
}
