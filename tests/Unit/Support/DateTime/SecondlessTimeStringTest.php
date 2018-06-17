<?php

namespace Tests\Unit\Support\DateTime;

use App\Support\DateTime\SecondlessTimeString;
use RuntimeException;
use Tests\TestCase;

class SecondlessTimeStringTest extends TestCase
{
    /** @test */
    function it_rejects_invalid_time_strings()
    {
        $this->assertInvalidTimeString('wow!');
        $this->assertInvalidTimeString('00:000:00');

        $this->assertInvalidTimeString('24:00:00');
        $this->assertInvalidTimeString('25:00:00');
        $this->assertInvalidTimeString('01:60:00');
        $this->assertInvalidTimeString('01:61:00');
    }

    /** @test */
    function it_casts_back_to_string()
    {
        $timeString = new SecondlessTimeString('12:30:45');

        $this->assertSame('12:30:00', (string) $timeString);
    }

    /** @test */
    function it_compares_later_than()
    {
        $a = new SecondlessTimeString('01:30:00');

        $b = new SecondlessTimeString('01:00:00');

        $this->assertTrue($a->laterThan($b));

        $this->assertFalse($b->laterThan($a));

        $this->assertFalse($a->laterThan($a));
    }

    /** @test */
    function it_compares_earlier_than()
    {
        $a = new SecondlessTimeString('01:30:00');

        $b = new SecondlessTimeString('01:00:00');

        $this->assertFalse($a->earlierThan($b));

        $this->assertTrue($b->earlierThan($a));

        $this->assertFalse($a->earlierThan($a));
    }

    /** @test */
    function it_compares_same_as()
    {
        $a = new SecondlessTimeString('01:30:00');

        $b = new SecondlessTimeString('01:00:00');

        $this->assertFalse($a->sameAs($b));

        $this->assertTrue($a->sameAs($a));
    }

    /** @test */
    function it_converts_to_seconds()
    {
        $a = new SecondlessTimeString('01:30:45');

        // With seconds it is "5445".
        $this->assertSame(5400, $a->toSeconds());
    }

    private function assertInvalidTimeString($string)
    {
        try {
            new SecondlessTimeString($string);
        } catch (RuntimeException $e) {
            $this->assertStringStartsWith('Invalid SecondlessTimeString input:', $e->getMessage());

            return;
        }

        $this->fail('SecondlessTimeString was not invalid: '.$string);
    }
}
