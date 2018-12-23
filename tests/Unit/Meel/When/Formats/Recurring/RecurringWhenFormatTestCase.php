<?php

namespace Tests\Unit\Meel\When\Formats\Recurring;

use App\Support\DateTime\SecondlessTimeString;
use App\Meel\When\Formats\Recurring\RecurringWhenFormat;
use App\Meel\When\WhenString;
use Carbon\Carbon;
use Tests\TestCase;

abstract class RecurringWhenFormatTestCase extends TestCase
{
    /** @var $whenFormat RecurringWhenFormat */
    protected $whenFormat;

    protected $shouldMatch = [];

    protected $shouldNotMatch = [];

    protected $testValuesExcludingToday = [];

    protected $testValuesIncludingToday = [];

    protected $testIntervalDescriptions = [];

    /** @test */
    function next_date_excluding_today()
    {
        if (count($this->testValuesExcludingToday) === 0) {
            $this->fail('No tests for "$this->testValuesExcludingToday"');
        }

        // To prevent bugs caused by "=" and "<=", the tests are run twice, once
        // with a "setTime" before the current time, and once with a "setTime"
        // the same as the current time.
        foreach (['11:00:00', '12:00:00'] as $setTimeString) {
            foreach ($this->testValuesExcludingToday as $startingDate => $values) {
                foreach ($values as $writtenInput => $expectedDates) {
                    $dateNow = $startingDate;

                    $preparedWrittenInput = (new WhenString)->prepare($writtenInput);

                    foreach ($expectedDates as $expectedDate) {
                        /** @var $format RecurringWhenFormat */
                        $format = new $this->whenFormat($preparedWrittenInput);

                        $now = Carbon::parse("$dateNow 12:00:00");

                        $setTime = new SecondlessTimeString($setTimeString);

                        $this->assertSame(
                            $expectedDate,
                            $actual = (string) $format->getNextDate($now->copy(), $setTime),
                            class_basename($this->whenFormat)." wrong next date, expected: '{$expectedDate}', actual: '{$actual}'\n".
                            "Now: {$now}     Set time: {$setTime}\n".
                            "Original string: {$writtenInput}\n".
                            "Prepared string: {$preparedWrittenInput}"
                        );

                        $dateNow = $expectedDate;
                    }
                }
            }
        }
    }

    /** @test */
    function next_date_including_today()
    {
        if (count($this->testValuesIncludingToday) === 0) {
            $this->fail('No tests for "$this->testValuesIncludingToday"');
        }

        foreach ($this->testValuesIncludingToday as $startingDate => $values) {
            foreach ($values as $writtenInput => $expectedDates) {
                $dateNow = $startingDate;

                $preparedWrittenInput = (new WhenString)->prepare($writtenInput);

                foreach ($expectedDates as $expectedDate) {
                    /** @var $format RecurringWhenFormat */
                    $format = new $this->whenFormat($preparedWrittenInput);

                    $now = Carbon::parse("$dateNow 12:00:00");

                    $setTime = new SecondlessTimeString('13:00:00');

                    $this->assertSame(
                        $expectedDate,
                        $actual = (string) $format->getNextDate($now->copy(), $setTime),
                        class_basename($this->whenFormat)." wrong next date, expected: '{$expectedDate}', actual: '{$actual}'\n".
                        "Now: {$now}     Set time: {$setTime}\n".
                        "Original string: {$writtenInput}\n".
                        "Prepared string: {$preparedWrittenInput}\n"
                    );

                    $dateNow = $expectedDate;
                }
            }
        }
    }

    /** @test */
    function it_has_an_interval_description()
    {
        foreach ($this->testIntervalDescriptions as $writtenInput => $expectedDescription) {
            $this->assertWhenFormatMatches($writtenInput);

            /** @var RecurringWhenFormat $whenFormat */
            $whenFormat = new $this->whenFormat(
                (new WhenString)->prepare($writtenInput)
            );

            $this->assertSame(
                $expectedDescription, $whenFormat->intervalDescription()
            );
        }
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
        $preparedString = (new WhenString)->prepare($string);

        $this->assertTrue(
            $this->whenFormat::matches($preparedString),
            class_basename($this->whenFormat)." did not match prepared string: '{$preparedString}'\n".
            "Original string: ".$string."\n".
            "Prepared string: ".$preparedString
        );
    }

    protected function assertWhenFormatDoesNotMatch($string)
    {
        $preparedString = (new WhenString)->prepare($string);

        $this->assertFalse(
            $this->whenFormat::matches($preparedString),
            class_basename($this->whenFormat)." matches prepared string: '{$preparedString}'\n".
            "Original string: ".$string."\n".
            "Prepared string: ".$preparedString
        );
    }
}
