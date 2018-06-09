<?php

namespace Tests\Unit\Support\DateTime;

use App\Support\DateTime\SecondlessDateTimeString;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SecondlessDateTimeStringTest extends TestCase
{
    /** @test */
    function it_casts_back_to_string()
    {
        $dateTime = new SecondlessDateTimeString('1993-05-07', '12:10:30');

        $this->assertSame('1993-05-07 12:10:00', (string) $dateTime);
    }

    /** @test */
    function it_can_tell_if_the_datetime_is_in_the_past()
    {
        Carbon::setTestNow('2018-03-28 12:00:15');

        // This is not in the past because seconds are ignored.
        $this->assertDateTimeNotInThePast('2018-03-28', '12:00:14');

        $this->assertDateTimeIsInThePast('2018-03-28', '11:59:59');

        $this->assertDateTimeNotInThePast('2018-03-28', '12:00:15');
        $this->assertDateTimeNotInThePast('2018-03-28', '12:00:16');

        $this->assertDateTimeIsInThePast('2018-03-27',  '12:00:15');
        $this->assertDateTimeNotInThePast('2018-03-28', '12:00:15');
        $this->assertDateTimeNotInThePast('2018-03-29', '12:00:15');

        $this->assertDateTimeIsInThePast('2018-03-28',  '17:59:14', 'Asia/Shanghai');
        $this->assertDateTimeNotInThePast('2018-03-28', '18:00:15', 'Asia/Shanghai');
        $this->assertDateTimeNotInThePast('2018-03-28', '18:00:16', 'Asia/Shanghai');
    }

    private function assertDateTimeIsInThePast($date, $time, $timezone = null)
    {
        $dateTime = new SecondlessDateTimeString($date, $time);

        $this->assertTrue(
            $dateTime->isInThePast($timezone),
            "DateTime is in the past: \n       {$date} {$time} (tz: {$timezone})\n  now: ".now()
        );
    }

    private function assertDateTimeNotInThePast($date, $time, $timezone = null)
    {
        $dateTime = new SecondlessDateTimeString($date, $time);

        $this->assertFalse(
            $dateTime->isInThePast($timezone),
            "DateTime is not in the past: \n       {$date} {$time} (tz: {$timezone})\n  now: ".now()
        );
    }
}
