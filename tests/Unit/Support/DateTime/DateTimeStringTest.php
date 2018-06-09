<?php

namespace Tests\Unit\Support\DateTime;

use App\Support\DateTime\DateTimeString;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DateTimeStringTest extends TestCase
{
    /** @test */
    function it_casts_back_to_string()
    {
        $dateTime = new DateTimeString('1993-05-07', '12:10:59');

        $this->assertSame('1993-05-07 12:10:59', (string) $dateTime);
    }

    /** @test */
    function it_can_tell_if_the_datetime_is_in_the_past()
    {
        Carbon::setTestNow('2018-03-28 12:00:00');

        $this->assertDateTimeIsInThePast('2018-03-28',  '11:59:59');
        $this->assertDateTimeNotInThePast('2018-03-28', '12:00:00');
        $this->assertDateTimeNotInThePast('2018-03-28', '12:00:01');

        $this->assertDateTimeIsInThePast('2018-03-27',  '12:00:00');
        $this->assertDateTimeNotInThePast('2018-03-28', '12:00:00');
        $this->assertDateTimeNotInThePast('2018-03-29', '12:00:00');

        $this->assertDateTimeIsInThePast('2018-03-28',  '17:59:59', 'Asia/Shanghai');
        $this->assertDateTimeNotInThePast('2018-03-28', '18:00:00', 'Asia/Shanghai');
        $this->assertDateTimeNotInThePast('2018-03-28', '18:00:01', 'Asia/Shanghai');

        $this->assertDateTimeNotInThePast('2019-01-01', '08:00:00');
    }

    private function assertDateTimeIsInThePast($date, $time, $timezone = null)
    {
        $dateTime = new DateTimeString($date, $time);

        $this->assertTrue(
            $dateTime->isInThePast($timezone),
            "DateTime is in the past: \n       {$date} {$time} (tz: {$timezone})\n  now: ".now()
        );
    }

    private function assertDateTimeNotInThePast($date, $time, $timezone = null)
    {
        $dateTime = new DateTimeString($date, $time);

        $this->assertFalse(
            $dateTime->isInThePast($timezone),
            "DateTime is not in the past: \n       {$date} {$time} (tz: {$timezone})\n  now: ".now()
        );
    }
}
