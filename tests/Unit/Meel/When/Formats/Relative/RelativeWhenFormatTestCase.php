<?php

namespace Tests\Unit\Meel\When\Formats\Relative;

use App\Meel\When\Formats\Relative\RelativeWhenFormat;
use App\Meel\When\WhenString;
use Carbon\Carbon;
use Tests\TestCase;

abstract class RelativeWhenFormatTestCase extends TestCase
{
    /** @var $whenFormat RelativeWhenFormat */
    protected $whenFormat;

    protected $shouldMatch = [];

    protected $shouldNotMatch = [];

    protected function assertWhenFormatMatches($now, string $string)
    {
        $preparedString = (new WhenString)->prepare($string);

        /** @var RelativeWhenFormat $relativeWhenFormat */
        $relativeWhenFormat = new $this->whenFormat($now, $preparedString);

        $this->assertTrue(
            $relativeWhenFormat->isUsableMatch(),

            class_basename($this->whenFormat)." did not match prepared string: '{$preparedString}'\n".
            "Original string: ".$string."\n".
            "Prepared string: ".$preparedString
        );
    }

    protected function assertTransformedNow($expected, $now, $string)
    {
        $preparedString = (new WhenString)->prepare($string);

        $transformedNow = Carbon::parse($now);

        /** @var RelativeWhenFormat $relativeWhenFormat */
        $relativeWhenFormat = new $this->whenFormat($now, $preparedString);

        $relativeWhenFormat->transformNow($transformedNow);

        $this->assertSame(
            $expected,
            $actual = $transformedNow->toDateTimeString(),

            class_basename($this->whenFormat)." incorrectly transformed now: \n".
            "Original string: $string\n".
            "Prepared string: $preparedString\n".
            "Now            : $now\n".
            "Expected       : $expected\n".
            "Actual         : $actual\n"
        );
    }

    /** @test */
    function it_should_match()
    {
        foreach ($this->shouldMatch as $now => $values) {
            foreach ($values as $writtenInput => $expected) {
                $preparedString = (new WhenString)->prepare($writtenInput);

                /** @var RelativeWhenFormat $relativeWhenFormat */
                $relativeWhenFormat = new $this->whenFormat($now, $preparedString);

                $this->assertTrue(
                    $relativeWhenFormat->isUsableMatch(),

                    class_basename($this->whenFormat)." did not match prepared string: '{$preparedString}'\n".
                    "Original string: ".$writtenInput."\n".
                    "Prepared string: ".$preparedString
                );

                $transformedNow = Carbon::parse($now);

                $relativeWhenFormat->transformNow($transformedNow);

                $this->assertSame(
                    $expected,
                    (string) $transformedNow,
                    'Transformed date did not match'
                );
            }
        }
    }

    /** @test */
    function it_should_not_match()
    {
        foreach ($this->shouldNotMatch as $now => $values) {
            foreach ($values as $string) {
                $preparedString = (new WhenString)->prepare($string);

                /** @var RelativeWhenFormat $relativeWhenFormat */
                $relativeWhenFormat = new $this->whenFormat($now, $preparedString);

                $this->assertFalse(
                    $relativeWhenFormat->isUsableMatch(),

                    class_basename($this->whenFormat)." matches prepared string: '{$preparedString}'\n".
                    "Original string: ".$string."\n".
                    "Prepared string: ".$preparedString
                );
            }
        }
    }
}
