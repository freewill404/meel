<?php

namespace Tests\Unit;

use App\Meel\WhenFormats\Recurring\Monthly;
use App\Meel\WhenFormats\Recurring\Weekly;
use App\Meel\WhenFormats\Recurring\MonthlyNthDay;
use App\Meel\WhenFormats\Recurring\Yearly;
use App\Meel\WhenFormats\RecurringInterpretation;
use App\Meel\WhenString;
use Tests\TestCase;

class RecurringInterpretationTest extends TestCase
{
    private function assertRecurringInterpretation($expectedFormat, $string)
    {
        $preparedString = WhenString::prepare($string);

        $recurringInterpretation = new RecurringInterpretation($preparedString);

        $this->assertInstanceOf(
            $expectedFormat,
            $actual = $recurringInterpretation->getMatchedFormat(),
            "Interpreted '{$string}' as a '".get_class($actual)."' format, expected '{$expectedFormat}'"
        );
    }

    /** @test */
    function it_interprets_weekly_intervals()
    {
        $this->assertRecurringInterpretation(Weekly::class, 'weekly');

        $this->assertRecurringInterpretation(Weekly::class, 'weekly on wednesday');
    }

    /** @test */
    function it_interprets_monthly_intervals()
    {
        $this->assertRecurringInterpretation(Monthly::class, 'monthly');

        $this->assertRecurringInterpretation(Monthly::class, 'monthly on the 12th');

        $this->assertRecurringInterpretation(Monthly::class, 'monthly on the 32th');

        $this->assertRecurringInterpretation(Monthly::class, 'monthly on the 0th');
    }

    /** @test */
    function it_interprets_yearly_intervals()
    {
        $this->assertRecurringInterpretation(Yearly::class, 'yearly');

        $this->assertRecurringInterpretation(Yearly::class, 'yearly in march');

        $this->assertRecurringInterpretation(Yearly::class, 'yearly on the 14th of march');

        $this->assertRecurringInterpretation(Yearly::class, 'yearly in march on the 13th');
    }

    /** @test */
    function it_interprets_monthly_on_the_nth_day_intervals()
    {
        $this->assertRecurringInterpretation(MonthlyNthDay::class, 'first tuesday of the month');

        $this->assertRecurringInterpretation(MonthlyNthDay::class, 'second tuesday of the month');

        $this->assertRecurringInterpretation(MonthlyNthDay::class, 'third tuesday of the month');

        $this->assertRecurringInterpretation(MonthlyNthDay::class, 'fourth tuesday of the month');

        $this->assertRecurringInterpretation(MonthlyNthDay::class, 'last tuesday of the month');
    }
}
