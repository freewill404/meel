<?php

namespace Tests\Unit\Support\Enums;

use App\Support\Enums\Timezones;
use Tests\TestCase;

class TimezonesTest extends TestCase
{
    /** @test */
    function all_timezones_are_valid()
    {
        $values = Timezones::selectValues();

        collect($values)
            ->values()
            ->map(function ($array) {
                return array_flip($array);
            })
            ->flatten()
            ->tap(function ($timezones) {
                $this->assertCount(411, $timezones);
            })
            ->each(function ($timezone) {
                now($timezone); // invalid timezones will throw an exception.
            })
            ->filter(function ($timezone) {
                return ! Timezones::has($timezone);
            })
            ->tap(function ($timezones) {
               $this->assertCount(0, $timezones);
            });
    }
}
