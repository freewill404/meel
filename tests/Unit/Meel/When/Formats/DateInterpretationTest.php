<?php

namespace Tests\Unit\Meel\When\Formats;

use App\Meel\When\Formats\DateInterpretation;
use App\Meel\When\WhenString;
use Tests\TestCase;

class DateInterpretationTest extends TestCase
{
    protected $fullValues = [
        '2018-03-28' => [
            '28-03-2018' => '2018-03-28',
            'On 28-03-2018' => '2018-03-28',
            '31-01-2018' => '2018-01-31',

            // ambiguous d-m/m-d
            '03-02-2018' => '2018-02-03',
        ]
    ];

    protected $dayMonthValues = [
        '2018-03-28' => [
            // unambiguous m-d
            '28-03' => '2018-03-28',
            'On 28-03' => '2018-03-28',

            // ambiguous m-d (in this case always assume d-m)
            // also, this date is in the past and doesn't specify a year, so it sets the next year
            '03-02' => '2019-02-03',

            '31-01' => '2019-01-31',
        ],
    ];

    protected $yearValues = [
        '2018-03-28' => [
            'in the year 2020' => '2020-01-01',
        ],
    ];

    protected $unusableValues = [
        '31-06',
        '32-05',
        '00-06',
    ];

    /** @test */
    function it_interprets_valid_full_date_values()
    {
        $this->assertDateInterpretations($this->fullValues, true, true, true);
    }

    /** @test */
    function it_interprets_valid_dates_without_a_year()
    {
        $this->assertDateInterpretations($this->dayMonthValues, false, true, true);
    }

    /** @test */
    function it_interprets_valid_dates_with_only_a_year()
    {
        $this->assertDateInterpretations($this->yearValues, true, false, false);
    }

    /** @test */
    function it_handles_unusable_values()
    {
        foreach ($this->unusableValues as $writtenInput) {
            $preparedString = (new WhenString)->prepare($writtenInput);

            $dateInterpretation = new DateInterpretation('2018-03-28 12:00:00', $preparedString);

            $this->assertFalse(
                $dateInterpretation->hasSpecifiedDate()
            );
        }
    }

    private function assertDateInterpretations($testValues, bool $shouldHaveYear, bool $shouldHaveMonth, bool $shouldHaveDay)
    {
        $interpretations = [];

        $failures = [];

        foreach ($testValues as $now => $values) {
            foreach ($values as $writtenInput => $expected) {
                $preparedString = (new WhenString)->prepare($writtenInput);

                $interpretations[] = $dateInterpretation = new DateInterpretation($now, $preparedString);

                $actual = (string) $dateInterpretation->getDateString();

                if ($expected !== $actual) {
                    $failures[] = 'Set datetime:  '.$now;
                    $failures[] = 'Written input: '.$writtenInput;

                    if ($preparedString !== $writtenInput) {
                        $failures[] = 'Prepared input: '.$preparedString;
                    }

                    $failures[] = 'Expected: '.$expected.'    actual: '.$actual;
                    $failures[] = '';
                }
            }
        }

        $this->assertSame('', implode("\n", $failures));

        foreach ($interpretations as $dateInterpretation) {
            $this->assertTrue($dateInterpretation->hasSpecifiedYear() === $shouldHaveYear);
            $this->assertTrue($dateInterpretation->hasSpecifiedMonth() === $shouldHaveMonth);
            $this->assertTrue($dateInterpretation->hasSpecifiedDay() === $shouldHaveDay);
        }
    }
}
