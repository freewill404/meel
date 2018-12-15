<?php

namespace App\Meel\When\Formats;

use App\Support\DateTime\DateString;
use Carbon\Carbon;

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

        if ($this->hasSpecifiedDay()) {
            $carbon = Carbon::parse('01-'.$this->getMonth().'-'.$this->getYear())->lastOfMonth();

            if ($this->getDay() > $carbon->day) {
                $this->day = null;
                $this->month = null;
                $this->year = null;
            }
        }
    }

    protected function interpretDate($string)
    {
        // Match dates like: "01-01-2000", "01-01 2000", "01 01 2000"
        if (preg_match('/\d?\d[\- ]\d?\d[\- ]\d{4}/', $string, $matches)) {
            [$a, $b, $year] = preg_split('/[\- ]/', $matches[0]);

            [$day, $month] = $this->parseDayAndMonth($a, $b);

            if ($day && $year > 1999 && $year < 2100) {
                $this->year = $year;

                $this->month = $month;

                $this->day = $day;
            }

            return;
        }

        // Match dates like: "2000-01-01", "2000 01-01", "2000 01 01"
        if (preg_match('/\d{4}[\- ]\d?\d[\- ]\d?\d/', $string, $matches)) {
            [$year, $month, $day] = preg_split('/[\- ]/', $matches[0]);

            if ($day >= 1 && $day <= 31 && $month >= 1 && $month <= 12 && $year > 1999 && $year < 2100) {
                $this->year = $year;

                $this->month = $month;

                $this->day = $day;
            }

            return;
        }

        // Match dates like:
        //   "01-01", "31-12", "12-31"
        //   "2020-05", "05-2020"
        if (preg_match('/\d+[\- ]\d+/', $string, $matches)) {
            [$a, $b] = preg_split('/[\- ]/', $matches[0]);

            if ($a < 99 && $b < 99) {
                [$day, $month] = $this->parseDayAndMonth($a, $b);

                $this->day = $day;

                $this->month = $month;
            } elseif ($a >= 2000 && $a <= 2099 && $b >= 1 && $b <= 12) {
                $this->year = $a;

                $this->month = $b;
            } elseif ($b >= 2000 && $b <= 2099 && $a >= 1 && $a <= 12) {
                $this->year = $b;

                $this->month = $a;
            }

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
     * If ambiguous, assumes dd-mm-yyyy format.
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

    public function getDateString(): DateString
    {
        $dateString = new DateString(
            $this->getYear().'-'.$this->getMonth().'-'.$this->getDay()
        );

        return $dateString->isBeforeToday($this->timezone) && ! $this->hasSpecifiedYear()
            ? $dateString->addYears(1)
            : $dateString;
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
}
