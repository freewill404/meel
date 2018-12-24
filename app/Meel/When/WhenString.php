<?php

namespace App\Meel\When;

class WhenString
{
    private const REPLACE_WORDS = [
        'a'       => '1',
        'an'      => '1',
        'one'     => '1',
        'two'     => '2',
        'three'   => '3',
        'four'    => '4',
        'five'    => '5',
        'six'     => '6',
        'seven'   => '7',
        'eight'   => '8',
        'nine'    => '9',
        'ten'     => '10',
        'fifteen' => '15',
        'thirty'  => '30',
        'sixty'   => '60',

        'mon'   => 'monday',
        'tue'   => 'tuesday',
        'tues'  => 'tuesday',
        'wed'   => 'wednesday',
        'thu'   => 'thursday',
        'thur'  => 'thursday',
        'thurs' => 'thursday',
        'fri'   => 'friday',
        'sat'   => 'saturday',
        'sun'   => 'sunday',

        'jan'  => 'january',
        'feb'  => 'february',
        'mar'  => 'march',
        'apr'  => 'april',
        'jun'  => 'june',
        'jul'  => 'july',
        'aug'  => 'august',
        'sep'  => 'september',
        'sept' => 'september',
        'oct'  => 'october',
        'nov'  => 'november',
        'dec'  => 'december',

        'daily'     => 'every 1 day',
        'every day' => 'every 1 day',

        'yearly'           => 'every 1 year',
        'every year'       => 'every 1 year',
        'every decade'     => 'every 10 year',
        'every century'    => 'every 100 year',
        'every millennium' => 'every 1000 year',
        'every millenium'  => 'every 1000 year',
        'every milennium'  => 'every 1000 year',
        'every milenium'   => 'every 1000 year',

        'in 1 decade'     => 'in 10 years',
        'in 1 century'    => 'in 100 years',
        'in 1 millennium' => 'in 1000 years',
        'in 1 millenium'  => 'in 1000 years',
        'in 1 milennium'  => 'in 1000 years',
        'in 1 milenium'   => 'in 1000 years',

        'decade from now'     => '10 years from now',
        'century from now'    => '100 years from now',
        'millennium from now' => '1000 years from now',
        'millenium from now'  => '1000 years from now',
        'milennium from now'  => '1000 years from now',
        'milenium from now'   => '1000 years from now',

        'bimonthly'   => 'every 2 month',
        'bi monthly'  => 'every 2 month',
        'bi-monthly'  => 'every 2 month',
        'every month' => 'every 1 month',
        'monthly'     => 'every 1 month',

        'biweekly'   => 'every 2 week',
        'bi weekly'  => 'every 2 week',
        'bi-weekly'  => 'every 2 week',
        'every week' => 'every 1 week',
        'weekly'     => 'every 1 week',

        'first'  => '1st',
        'second' => '2nd',
        'third'  => '3rd',
        'fourth' => '4th',

        'min' => 'minutes',
        'mins' => 'minutes',
    ];

    private const MONTHS = [
        'january'   => '01',
        'february'  => '02',
        'march'     => '03',
        'april'     => '04',
        'may'       => '05',
        'june'      => '06',
        'july'      => '07',
        'august'    => '08',
        'september' => '09',
        'october'   => '10',
        'november'  => '11',
        'december'  => '12',
    ];

    public function prepare($string): string
    {
        $string = str_replace(',', ' and ', $string);

        $string = preg_replace('/  +/', ' ', $string);

        $string = trim($string);

        $string = strtolower($string);

        foreach (static::REPLACE_WORDS as $search => $replace) {
            $string = preg_replace('/(^| )'.$search.'( |$)/', '${1}'.$replace.'${2}', $string);
        }

        return $this->replaceWrittenMonths($string);
    }

    protected function replaceWrittenMonths($string)
    {
        $months = '(january|february|march|april|may|june|july|august|september|october|november|december)';

        if (! preg_match('/'.$months.'/', $string)) {
            return $string;
        }

        // Turns patterns like: "01 september", "1st september" into "01-09"
        $string = preg_replace_callback('/(\d+)(?:st|nd|rd|)[\- ]'.$months.'/', function ($matches) {
            $date = str_pad($matches[1], 2, '0', STR_PAD_LEFT);

            if ($date == 0 || $date > 31) {
                return $matches[0];
            }

            $writtenMonth = $matches[2];

            return $date.'-'.static::MONTHS[$writtenMonth];
        }, $string);

        // Turns patterns like: "september 01", "september 1st" into "01-09"
        $string = preg_replace_callback('/'.$months.'[\- ](\d+)(?:st|nd|rd|)/', function ($matches) {
            $date = str_pad($matches[2], 2, '0', STR_PAD_LEFT);

            if ($date == 0 || $date > 31) {
                return $matches[0];
            }

            $writtenMonth = $matches[1];

            return $date.'-'.static::MONTHS[$writtenMonth];
        }, $string);

        // Changes patterns like: "2000 01-01", "01-01 2000" into "01-01-2000"
        $string = preg_replace('/(\d{4}) (\d\d-\d\d)/', '$2-$1', $string);
        $string = preg_replace('/(\d\d-\d\d) (\d{4})/', '$1-$2', $string);

        return $string;
    }
}
