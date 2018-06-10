<?php

namespace Tests\Unit\WhenFormats\Recurring;

use App\Support\DateTime\SecondlessTimeString;
use App\Meel\WhenFormats\Recurring\RecurringWhenFormat;
use App\Meel\WhenString;
use Tests\TestCase;

abstract class RecurringWhenFormatTestCase extends TestCase
{
    /** @var $whenFormat RecurringWhenFormat */
    protected $whenFormat;

    protected $shouldMatch = [];

    protected $shouldNotMatch = [];

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
        if (count($this->shouldNotMatch) === 0) {
            $this->assertTrue(true);

            return;
        }

        foreach ($this->shouldNotMatch as $string) {
            $this->assertWhenFormatDoesNotMatch($string);
        }
    }

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

    protected function assertNextDate($expected, $string, $setTime = null, $timezone = null)
    {
        $this->assertWhenFormatMatches($string);

        $preparedString = WhenString::prepare($string);

        /** @var $format RecurringWhenFormat */
        $format = new $this->whenFormat($preparedString);

        $setTime = new SecondlessTimeString($setTime ?? now($timezone));

        $this->assertSame(
            $expected,
            $actual = (string) $format->getNextDate($setTime, $timezone),
            class_basename($this->whenFormat)." wrong next date, expected: '{$expected}', actual: '{$actual}'\n".
            "Original string: {$string}\n".
            "Prepared string: {$preparedString}"
        );
    }

    protected function assertIntervalDescription($expected, $string)
    {
        $this->assertWhenFormatMatches($string);

        $whenFormat = new $this->whenFormat(
            WhenString::prepare($string)
        );

        $this->assertSame(
            $expected, $whenFormat->intervalDescription()
        );
    }

    protected function getTimeStrings()
    {
        return [
            new SecondlessTimeString(now()->subHours(1)),
            new SecondlessTimeString(now()),
            new SecondlessTimeString(now()->addHours(1)),
        ];
    }

}
