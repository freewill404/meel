<?php

namespace Tests\Unit\Meel\When;

use App\Meel\When\WhenString;
use Tests\TestCase;

class WhenStringTest extends TestCase
{
    private $testValues = [
        ' now ' => 'now',
        'Now' => 'now',
        'in an hour' => 'in 1 hour',
        'an hour from now' => '1 hour from now',
        'in a minute' => 'in 1 minute',
        'in an hour and a minute' => 'in 1 hour and 1 minute',
        // Only replace when "a" or "an" are not part of a word
        'anders kepa kepan dana' => 'anders kepa kepan dana',
        'the d, e and f' => 'the d and e and f',
        'the d,e,f' => 'the d and e and f',
        'the d,e,f,' => 'the d and e and f and',
        'the d,,b' => 'the d and and b',
        'every mon, tues, thurs,fri' => 'every monday and tuesday and thursday and friday',
        'in one hour' => 'in 1 hour',
        'in two hours' => 'in 2 hours',
        'in three hours' => 'in 3 hours',
        'in four hours' => 'in 4 hours',
        'in five hours' => 'in 5 hours',
        'in six hours' => 'in 6 hours',
        'in seven hours' => 'in 7 hours',
        'in eight hours' => 'in 8 hours',
        'in nine hours' => 'in 9 hours',
        'in ten hours' => 'in 10 hours',
        'in fifteen minutes' => 'in 15 minutes',
        'in thirty minutes' => 'in 30 minutes',
        'in sixty minutes' => 'in 60 minutes',

        'yearly' => 'every 1 year',
        'every year' => 'every 1 year',
        'every month' => 'every 1 month',
        'monthly' => 'every 1 month',
        'every 3 months' => 'every 3 months',
        'bimonthly' => 'every 2 month',
        'bi monthly' => 'every 2 month',
        'bi-monthly' => 'every 2 month',
        'weekly' => 'every 1 week',
        'every week' => 'every 1 week',
        'biweekly' => 'every 2 week',
        'bi weekly' => 'every 2 week',
        'bi-weekly' => 'every 2 week',

        'on mon' => 'on monday',
        'on tue' => 'on tuesday',
        'on tues' => 'on tuesday',
        'on wed' => 'on wednesday',
        'on thu' => 'on thursday',
        'on thur' => 'on thursday',
        'on thurs' => 'on thursday',
        'on fri' => 'on friday',
        'on sat' => 'on saturday',
        'on sun' => 'on sunday',

        'in jan' => 'in january',
        'in feb' => 'in february',
        'in mar' => 'in march',
        'in apr' => 'in april',
        'in jun' => 'in june',
        'in jul' => 'in july',
        'in aug' => 'in august',
        'in sep' => 'in september',
        'in sept' => 'in september',
        'in oct' => 'in october',
        'in nov' => 'in november',
        'in dec' => 'in december',

        '1st sept'      => '01-09',
        '2018 1st sept' => '01-09-2018',
        '2nd sept'      => '02-09',
        '3rd sept'      => '03-09',
        '1 sept 2018'   => '01-09-2018',
        '1st sept 2018' => '01-09-2018',
        'sept 1'        => '01-09',
        'on sept 1'     => 'on 01-09',
        '2018 sept 1st' => '01-09-2018',
        'sept 1st'      => '01-09',
        'sept 2nd'      => '02-09',
        'sept 3rd'      => '03-09',
        '01-may-2020'   => '01-05-2020',
        'may-01-2020'   => '01-05-2020',
        '2020-may-31'   => '2020-31-05',

        '01 sept 02' => '01-09 02',

        'sept 500rd' => 'september 500rd',
        '500rd sept' => '500rd september',
        '0 sept' => '0 september',
        'sept 0' => 'september 0',
        '32 sept' => '32 september',
        'sept 32' => 'september 32',

        'in 5 mins' => 'in 5 minutes',
        'in 5 min' => 'in 5 minutes',
        'in 5 mins and 1 hour' => 'in 5 minutes and 1 hour',
        'in 1 hour and 1 min' => 'in 1 hour and 1 minutes',
        'in 1 minotaur from now' => 'in 1 minotaur from now',

        'at 2 am' => 'at 02:00',
        'at 2am' => 'at 02:00',
        'at 2 pm' => 'at 14:00',
        'at 2pm' => 'at 14:00',
        'at 12am' => 'at 00:00',
        'at 12pm' => 'at 12:00',
        'at 1:30 am' => 'at 01:30',
        'at 1:30am' => 'at 01:30',
        'at 1:30 pm' => 'at 13:30',
        'at 1:30pm' => 'at 13:30',
        'at 12:30pm' => 'at 12:30',
        'at 12:30 pm' => 'at 12:30',
        'at 12:30am' => 'at 00:30',
        'at 12:30 am' => 'at 00:30',

        // the following am/pm values are invalid.
        'at 13pm' => 'at 13pm',
        'at 13:30pm' => 'at 13:30pm',
        'at 13am' => 'at 13am',
        'at 13:30am' => 'at 13:30am',
        'at 111am' => 'at 111am',
        'at 2 amsterdam' => 'at 2 amsterdam',
        'at 1:30pmazan' => 'at 1:30pmazan',

    ];

    /** @test */
    function it_prepares_written_input()
    {
        foreach ($this->testValues as $writtenInput => $expectedPreparedInput) {
            $this->assertSame(
                $expectedPreparedInput,
                $actual = (new WhenString)->prepare($writtenInput),
                "WhenString error:\n\n  input:    {$writtenInput}\n  prepared: {$actual}\n  expected: {$expectedPreparedInput}\n"
            );

            $this->assertSame(
                $actual,
                (new WhenString)->prepare($actual),
                'Prepared string changed when being prepared twice'
            );
        }
    }
}
