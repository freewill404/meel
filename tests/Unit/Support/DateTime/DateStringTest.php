<?php

namespace Tests\Unit\Support\DateTime;

use App\Support\DateTime\DateString;
use Carbon\Carbon;
use RuntimeException;
use Tests\TestCase;

class DateStringTest extends TestCase
{
    /** @test */
    public function it_rejects_invalid_date_strings()
    {
        $this->assertInvalidDateString('wow!');
        $this->assertInvalidDateString('2000:01:01');
        $this->assertInvalidDateString('2000-00-01');
        $this->assertInvalidDateString('2000-01-00');
        $this->assertInvalidDateString('2000-01-32');
        $this->assertInvalidDateString('2000-13-01');
        $this->assertInvalidDateString('2018-06-31'); // June has 30 days
    }

    /** @test */
    function it_casts_back_to_string()
    {
        $dateString = new DateString('1993-05-07');

        $this->assertSame('1993-05-07', (string) $dateString);
    }

    /** @test */
    function it_can_tell_if_a_date_is_after_today()
    {
        Carbon::setTestNow('2018-03-28 12:00:15');

        $dateString = new DateString('2018-03-29');

        $this->assertTrue($dateString->isAfterToday());

        $this->assertFalse($dateString->isToday());

        $this->assertFalse($dateString->isBeforeToday());
    }

    /** @test */
    function it_can_tell_if_a_date_is_today()
    {
        Carbon::setTestNow('2018-03-28 12:00:15');

        $dateString = new DateString('2018-03-28');

        $this->assertFalse($dateString->isAfterToday());

        $this->assertTrue($dateString->isToday());

        $this->assertFalse($dateString->isBeforeToday());
    }

    /** @test */
    function it_can_tell_if_a_date_before_today()
    {
        Carbon::setTestNow('2018-03-28 12:00:15');

        $dateString = new DateString('2018-03-27');

        $this->assertFalse($dateString->isAfterToday());

        $this->assertFalse($dateString->isToday());

        $this->assertTrue($dateString->isBeforeToday());
    }

    private function assertInvalidDateString($string)
    {
        try {
            new DateString($string);

            $this->fail('DateString was not invalid: '.$string);
        } catch (RuntimeException $e) {

        }

        $this->assertTrue(true);
    }
}
