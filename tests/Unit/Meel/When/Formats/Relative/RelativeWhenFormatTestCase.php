<?php

namespace Tests\Unit\Meel\When\Formats\Relative;

use App\Meel\When\Formats\Relative\RelativeWhenFormat;
use App\Meel\When\WhenString;
use Tests\TestCase;

abstract class RelativeWhenFormatTestCase extends TestCase
{
    /** @var $whenFormat RelativeWhenFormat */
    protected $whenFormat;

    protected $shouldMatch = [];

    protected $shouldNotMatch = [];

    protected function assertWhenFormatMatches($string)
    {
        $preparedString = WhenString::prepare($string);

        $this->assertTrue(
            $this->whenFormat::matches($preparedString),
            class_basename($this->whenFormat)." did not match prepared string: '{$preparedString}'\n".
            "Original string: ".$string."\n".
            "Prepared string: ".$preparedString
        );
    }

    protected function assertWhenFormatDoesNotMatch($string)
    {
        $preparedString = WhenString::prepare($string);

        $this->assertFalse(
            $this->whenFormat::matches($preparedString),
            class_basename($this->whenFormat)." matches prepared string: '{$preparedString}'\n".
            "Original string: ".$string."\n".
            "Prepared string: ".$preparedString
        );
    }

    protected function assertTransformedNow($expected, $string)
    {
        $preparedString = WhenString::prepare($string);

        $transformedNow = now();

        $relativeWhen = new $this->whenFormat($preparedString);

        $relativeWhen->transformNow($transformedNow);

        $this->assertSame(
            $expected,
            $actual = $transformedNow->toDateTimeString(),
            class_basename($this->whenFormat)." incorrectly transformed now: \n".
            "Original string: ".$string."\n".
            "Prepared string: ".$preparedString."\n".
            "Now            : ".now()->toDateTimeString()."\n".
            "Expected       : ".$expected."\n".
            "Actual         : ".$actual."\n"
        );
    }

    /** @test */
    function it_should_match()
    {
        foreach ($this->shouldMatch as $string) {
            $this->assertWhenFormatMatches($string);
        }
    }

    /** @test */
    function it_should_not_match()
    {
        foreach ($this->shouldNotMatch as $string) {
            $this->assertWhenFormatDoesNotMatch($string);
        }
    }
}
