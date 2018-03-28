<?php

namespace Tests\Unit;

use App\Meel\RelativeNow;
use Tests\TestCase;

class RelativeNowTest extends TestCase
{
    private function assertIsRelativeToNow($input)
    {
        $relativeNow = new RelativeNow($input);

        $this->assertTrue(
            $relativeNow->isRelativeToNow(),
            "RelativeNow did not interpret '{$input}' to be relative to now"
        );
    }

    private function assertIsNotRelativeToNow($input)
    {
        $relativeNow = new RelativeNow($input);

        $this->assertFalse(
            $relativeNow->isRelativeToNow(),
            "RelativeNow interpreted '{$input}' to be relative to now"
        );
    }

    private function assertRelativeNow($expected, $input)
    {
        $relativeNow = new RelativeNow($input);

        if (! $relativeNow->isRelativeToNow()) {
            $this->fail("RelativeNow interpreted '{$input}' as not being relative to now, should be '{$expected}'");
        }

        $this->assertSame(
            $expected,
            $actual = $relativeNow->getTime()->format('Y-m-d h:i:s'),
            "RelativeNow interpreted '{$input}' as '{$actual}', should be '{$expected}'"
        );
    }

    /** @test */
    function it_interprets_when_a_string_is_relative_to_now()
    {
        $this->assertIsRelativeToNow('now');
        $this->assertIsRelativeToNow('right now');
        $this->assertIsRelativeToNow('an hour from now');
        $this->assertIsRelativeToNow('in two hours');
    }

    /** @test */
    function it_interprets_when_a_string_is_not_relative_to_now()
    {
        $this->assertIsNotRelativeToNow('1 hour');
        $this->assertIsNotRelativeToNow('24th of may 2018');
    }

    /** @test */
    function it_interprets_now()
    {
        $now = now()->format('Y-m-d h:i:s');

        $this->assertRelativeNow($now, 'now');

        $this->assertRelativeNow($now, 'right now');
    }
}
