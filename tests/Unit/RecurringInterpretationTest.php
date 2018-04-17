<?php

namespace Tests\Unit;

use App\Meel\RecurringInterpretation;
use App\Support\Enums\Days;
use App\Support\Enums\Intervals;
use App\Support\Enums\Months;
use Tests\TestCase;

class RecurringInterpretationTest extends TestCase
{
    private function assertRecurringInterpretation($expectedInterval, $expectedDay, $input)
    {
        $recurringInterpretation = new RecurringInterpretation($input);

        $this->assertSame(
            $expectedInterval,
            $actual = $recurringInterpretation->getInterval(),
            "Interpreted '{$input}' as a '{$actual}' interval, expected '{$expectedInterval}'"
        );

        $this->assertSame(
            $expectedDay,
            $actual = $recurringInterpretation->getDayOfTheWeek(),
            "Interpreted the day of '{$input}' as '{$actual}', expected '{$expectedDay}'"
        );
    }

    private function assertMonthlyRecurringInterpretation($expectedDate, $input)
    {
        $recurringInterpretation = new RecurringInterpretation($input);

        $this->assertSame(
            Intervals::MONTHLY,
            $actual = $recurringInterpretation->getInterval(),
            "Interpreted '{$input}' as a '{$actual}' interval, expected ".Intervals::MONTHLY
        );

        $this->assertSame(
            $expectedDate,
            $actual = $recurringInterpretation->getDateOfTheMonth(),
            "Interpreted the date of '{$input}' as '{$actual}', expected '{$expectedDate}'"
        );
    }

    private function assertYearlyRecurringInterpretation($expectedDate, $expectedMonth, $input)
    {
        $recurringInterpretation = new RecurringInterpretation($input);

        $this->assertSame(
            Intervals::YEARLY,
            $actual = $recurringInterpretation->getInterval(),
            "Interpreted '{$input}' as a '{$actual}' interval, expected ".Intervals::YEARLY
        );

        $this->assertSame(
            $expectedDate,
            $actual = $recurringInterpretation->getDateOfTheMonth(),
            "Interpreted the date of '{$input}' as '{$actual}', expected '{$expectedDate}'"
        );

        $this->assertSame(
            $expectedMonth,
            $actual = $recurringInterpretation->getMonthOfTheYear(),
            "Interpreted the month of '{$input}' as '{$actual}', expected '{$expectedMonth}'"
        );
    }

    /** @test */
    function it_interprets_weekly_intervals()
    {
        $this->assertRecurringInterpretation(Intervals::WEEKLY, Days::MONDAY, 'weekly');

        $this->assertRecurringInterpretation(Intervals::WEEKLY, Days::WEDNESDAY, 'weekly on wednesday');
    }

    /** @test */
    function it_interprets_monthly_intervals()
    {
        $this->assertMonthlyRecurringInterpretation(1, 'monthly');

        $this->assertMonthlyRecurringInterpretation(12, 'monthly on the 12th');

        $this->assertMonthlyRecurringInterpretation(1, 'monthly on the 32th');
        $this->assertMonthlyRecurringInterpretation(1, 'monthly on the 0th');
    }

    /** @test */
    function it_interprets_yearly_intervals()
    {
        $this->assertYearlyRecurringInterpretation(1, Months::JANUARY, 'yearly');

        $this->assertYearlyRecurringInterpretation(1, Months::MARCH, 'yearly in march');

        $this->assertYearlyRecurringInterpretation(14, Months::MARCH, 'yearly on the 14th of march');

        $this->assertYearlyRecurringInterpretation(13, Months::MARCH, 'yearly in march on the 13th');
    }
}
