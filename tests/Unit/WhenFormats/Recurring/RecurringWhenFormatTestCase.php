<?php

namespace Tests\Unit\WhenFormats\Recurring;

use App\Meel\DateTime\TimeString;
use App\Meel\WhenFormats\Recurring\RecurringWhenFormat;
use App\Meel\WhenString;
use Tests\TestCase;

abstract class RecurringWhenFormatTestCase extends TestCase
{
    /** @var $whenFormat RecurringWhenFormat */
    protected $whenFormat;

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
        $preparedString = WhenString::prepare($string);

        /** @var $format RecurringWhenFormat */
        $format = new $this->whenFormat($preparedString);

        $setTime = new TimeString($setTime ?? now($timezone));

        $this->assertSame(
            $expected,
            $actual = (string) $format->getNextDate($setTime, $timezone),
            class_basename($this->whenFormat)." wrong next date, expected: '{$expected}', actual: '{$actual}'\n".
            "Original string: {$string}\n".
            "Prepared string: {$preparedString}"
        );
    }

    protected function getTimeStrings()
    {
        return [
            new TimeString(now()->subHours(1)),
            new TimeString(now()),
            new TimeString(now()->addHours(1)),
        ];
    }

}
