<?php

namespace Tests\Unit\DateTime;

use App\Meel\DateTime\TimeString;
use RuntimeException;
use Tests\TestCase;

class TimeStringTest extends TestCase
{
    /** @test */
    function it_rejects_invalid_time_strings()
    {
        $this->assertInvalidTimeString('wow!');
        $this->assertInvalidTimeString('00:00');
        $this->assertInvalidTimeString('00:000:00');

        $this->assertInvalidTimeString('24:00:00');
        $this->assertInvalidTimeString('25:00:00');
        $this->assertInvalidTimeString('01:60:00');
        $this->assertInvalidTimeString('01:61:00');
        $this->assertInvalidTimeString('01:01:60');
        $this->assertInvalidTimeString('01:01:61');
    }

    /** @test */
    function it_casts_back_to_string()
    {
        $timeString = new TimeString('12:30:45');

        $this->assertSame('12:30:45', (string) $timeString);
    }

    /** @test */
    function it_compares_later_than()
    {
        $a = new TimeString('01:30:00');

        $b = new TimeString('01:00:00');

        $this->assertTrue($a->laterThan($b));

        $this->assertFalse($b->laterThan($a));

        $this->assertFalse($a->laterThan($a));
    }

    /** @test */
    function it_compares_earlier_than()
    {
        $a = new TimeString('01:30:00');

        $b = new TimeString('01:00:00');

        $this->assertFalse($a->earlierThan($b));

        $this->assertTrue($b->earlierThan($a));

        $this->assertFalse($a->earlierThan($a));
    }

    /** @test */
    function it_compares_same_as()
    {
        $a = new TimeString('01:30:00');

        $b = new TimeString('01:00:00');

        $this->assertFalse($a->sameAs($b));

        $this->assertTrue($a->sameAs($a));
    }

    /** @test */
    function it_converts_to_seconds()
    {
        $a = new TimeString('01:30:45');

        $this->assertSame(5445, $a->toSeconds());
    }

    private function assertInvalidTimeString($string)
    {
        try {
            new TimeString($string);

            $this->fail('TimeString was not invalid: '.$string);
        } catch (RuntimeException $e) {
            $this->assertTrue(true);
        }
    }
}
