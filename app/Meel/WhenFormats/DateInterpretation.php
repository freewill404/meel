<?php

namespace App\Meel\WhenFormats;

use App\Meel\DateTime\DateString;

class DateInterpretation
{
    protected $timezone;

    protected $year;

    protected $month;

    protected $day;

    public function __construct(string $string, $timezone = null)
    {
        $this->timezone = $timezone;

        $this->interpretDate($string);
    }

    public function getDateString(): DateString
    {
        return new DateString(
            $this->getYear().'-'.$this->getMonth().'-'.$this->getDay()
        );
    }

    protected function defaultYear()
    {
        return now($this->timezone)->format('Y');
    }

    protected function defaultMonth()
    {
        return '01';
    }

    protected function defaultDay()
    {
        return '01';
    }

    public function getYear(): string
    {
        return $this->year ? str_pad($this->year, 2, '0', STR_PAD_LEFT) : $this->defaultYear();
    }

    public function getMonth(): string
    {
        return $this->month ? str_pad($this->month, 2, '0', STR_PAD_LEFT) : $this->defaultMonth();
    }

    public function getDay(): string
    {
        return $this->day ? str_pad($this->day, 2, '0', STR_PAD_LEFT) : $this->defaultDay();
    }

    public function hasSpecifiedDate(): bool
    {
        return $this->hasSpecifiedDay() || $this->hasSpecifiedMonth() || $this->hasSpecifiedYear();
    }

    public function hasSpecifiedYear(): bool
    {
        return $this->year !== null;
    }

    public function hasSpecifiedMonth(): bool
    {
        return $this->month !== null;
    }

    public function hasSpecifiedDay(): bool
    {
        return $this->day !== null;
    }

    protected function interpretDate($string)
    {
        // Match dates like:
        //   "01-01-2000"
        //   "1-10-2000"
        //   "10-1-2000"
        //   "1-1-2000"
        if (preg_match('/(\d?\d-\d?\d-\d\d\d\d)/', $string, $matches)) {
            [$a, $b, $year] = explode('-', $matches[1]);

            [$day, $month] = $this->parseDayAndMonth($a, $b);

            if ($day && $year > 1999 && $year < 2100) {
                $this->year = $year;

                $this->month = $month;

                $this->day = $day;
            }

            return;
        }

        // Match dates like:
        //   "23-2"
        //   "2-23"
        if (preg_match('/(\d?\d-\d?\d)/', $string, $matches)) {
            [$a, $b] = explode('-', $matches[1]);

            [$day, $month] = $this->parseDayAndMonth($a, $b);

            $this->month = $month;

            $this->day = $day;

            return;
        }

        // Match years between 2000 and 2099
        if (preg_match('/(20\d\d)/', $string, $matches)) {
            $this->year = $matches[1];
        }
    }

    /**
     * Given an "a" and "b" integer, figure out which one is the day,
     * and which one is the month.
     *
     * @param $a
     * @param $b
     *
     * @return array
     */
    protected function parseDayAndMonth($a, $b)
    {
        $failed = [null, null];

        if (! preg_match('/^\d+$/', $a) || ! preg_match('/^\d+$/', $b)) {
            return $failed;
        }

        if ($a <= 0 || $b <= 0) {
            return $failed;
        }

        // If ambiguous, assume dd-mm-yyyy format.
        if ($a <= 12 && $b <= 12) {
            return [$a, $b];
        }

        if ($a <= 31 && $b <= 12) {
            return [$a, $b];
        }

        if ($a <= 12 && $b <= 31) {
            return [$b, $a];
        }

        return $failed;
    }
}
