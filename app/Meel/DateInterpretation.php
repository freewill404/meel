<?php

namespace App\Meel;

use Illuminate\Support\Carbon;
use LogicException;

class DateInterpretation
{
    protected $string;

    protected $date; // Y-m-d

    public function __construct(string $string)
    {
        $this->string = $string;

        $this->date = $this->interpretDate($string);
    }

    public function isValidDate()
    {
        return (bool) $this->date;
    }

    public function getDate(): Carbon
    {
        if (! $this->isValidDate()) {
            throw new LogicException('The given input is not interpreted as a valid date');
        }

        return Carbon::parse($this->date.' 00:00:00');
    }

    protected function interpretDate($string)
    {
        // Match dates like:
        //   "01-01-2000"
        //   "1-10-2000"
        //   "10-1-2000"
        //   "1-1-2000"
        if (preg_match('/(\d?\d-\d?\d-\d\d\d\d)/', $string, $matches)) {
            return $this->parseYearMonthDay($matches[1]);
        }

        // Match dates like:
        //   "23-2"
        //   "2-23"
        if (preg_match('/(\d?\d-\d?\d)/', $string, $matches)) {
            return $this->parseMonthDay($matches[1]);
        }

        return false;
    }

    protected function parseYearMonthDay($string)
    {
        [$a, $b, $year] = explode('-', $string);

        [$day, $month] = $this->parseDayAndMonth($a, $b);

        if ($day === false || $year < 2017 || $year > 2299) {
            return false;
        }

        return "{$year}-{$month}-{$day}";
    }

    protected function parseMonthDay($string)
    {
        [$a, $b] = explode('-', $string);

        [$day, $month] = $this->parseDayAndMonth($a, $b);

        return $day === false
            ? false
            : now()->format('Y-')."{$month}-{$day}";
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
        $failed = [false, false];

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
