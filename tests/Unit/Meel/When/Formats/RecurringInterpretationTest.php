<?php

namespace Tests\Unit\Meel\When\Formats;

use App\Meel\When\Formats\Recurring\Monthly;
use App\Meel\When\Formats\Recurring\Weekly;
use App\Meel\When\Formats\Recurring\MonthlyNthDay;
use App\Meel\When\Formats\Recurring\Yearly;
use App\Meel\When\Formats\RecurringInterpretation;
use App\Meel\When\WhenString;
use Tests\TestCase;

class RecurringInterpretationTest extends TestCase
{
    private $testValues = [
        Weekly::class => [
            'weekly',
            'weekly on wednesday',
        ],

        Monthly::class => [
            'monthly',
            'monthly on the 12th',
            'monthly on the 32th',
            'monthly on the 0th',
        ],

        Yearly::class => [
            'yearly',
            'yearly in march',
            'yearly on the 14th of march',
            'yearly in march on the 13th',
        ],

        MonthlyNthDay::class => [
            'first tuesday of the month',
            'second tuesday of the month',
            'third tuesday of the month',
            'fourth tuesday of the month',
            'last tuesday of the month',
        ],
    ];

    /** @test */
    function it_uses_the_correct_class()
    {
        foreach ($this->testValues as $expectedClass => $writtenInputs) {
            foreach ($writtenInputs as $writtenInput) {
                $preparedString = (new WhenString)->prepare($writtenInput);

                $recurringInterpretation = new RecurringInterpretation('2018-03-28 12:00:00', $preparedString);

                $this->assertInstanceOf(
                    $expectedClass,
                    $actual = $recurringInterpretation->getMatchedFormat(),
                    "Interpreted '{$writtenInput}' as a '".get_class($actual)."' format, expected '{$expectedClass}'"
                );
            }
        }
    }
}
