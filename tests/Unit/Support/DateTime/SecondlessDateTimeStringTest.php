<?php

namespace Tests\Unit\Support\DateTime;

use App\Support\DateTime\SecondlessDateTimeString;
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
    function it_can_tell_if_a_date_is_before_another_date()
    {
        $dateTime = new SecondlessDateTimeString('1993-05-07', '12:10:30');

        // it ignores seconds
        $this->assertFalse($dateTime->isBefore('1993-05-07 12:10:29'));

        $this->assertTrue($dateTime->isBefore('1993-05-07 12:11:30'));
        $this->assertTrue($dateTime->isBefore('1993-05-08 12:10:30'));

        $this->assertFalse($dateTime->isBefore('1993-05-07 12:10:30'));
        $this->assertFalse($dateTime->isBefore('1993-05-06 12:11:30'));
    }

    /** @test */
    function it_can_change_timezone()
    {
        $dateTime = new SecondlessDateTimeString('2018-03-28', '12:00:15');

        $dateTime->changeTimezone('Asia/Shanghai', 'Europe/Amsterdam');

        $this->assertSame('2018-03-28 06:00:00', (string) $dateTime);
    }

    /** @test */
    function it_can_add_minutes()
    {
        $dateTime = new SecondlessDateTimeString('2018-03-28', '12:00:15');

        $dateTime->addMinutes(15);

        $this->assertSame('2018-03-28 12:15:00', (string) $dateTime);
    }
}
