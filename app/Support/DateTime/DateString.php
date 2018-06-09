<?php

namespace App\Support\DateTime;

use Carbon\Carbon;
use Exception;
use RuntimeException;

class DateString
{
    protected $day;

    protected $month;

    protected $year;

    public function __construct($string)
    {
        if ($string instanceof Carbon) {
            $string = $string->format('Y-m-d');
        }

        try {
            Carbon::createFromFormat('Y-m-d', $string);
        } catch(Exception $e) {
            throw new RuntimeException('Invalid date');
        }

        $lastErrors = Carbon::getLastErrors();

        if ($lastErrors['warning_count'] || $lastErrors['error_count']) {
            throw new RuntimeException('Invalid date');
        }

        [$this->year, $this->month, $this->day] = explode('-', $string);
    }

    public function isAfterToday($timezone = null): bool
    {
        return $this->compareToNow($timezone) === -1;
    }

    public function isToday($timezone = null): bool
    {
        return $this->compareToNow($timezone) === 0;
    }

    public function isBeforeToday($timezone = null): bool
    {
        return $this->compareToNow($timezone) === 1;
    }

    protected function compareToNow($timezone)
    {
        $now = now($timezone)->format('Y-m-d');

        [$yearNow, $monthNow, $dayNow] = explode('-', $now);

        if ($yearNow > $this->year) {
            return 1;
        }

        if ($yearNow < $this->year) {
            return -1;
        }

        if ($monthNow > $this->month) {
            return 1;
        }

        if ($monthNow < $this->month) {
            return -1;
        }

        if ($dayNow > $this->day) {
            return 1;
        }

        if ($dayNow < $this->day) {
            return -1;
        }

        return 0;
    }

    public function __toString()
    {
        return implode('-', [
            $this->year,
            str_pad($this->month, 2, '0', STR_PAD_LEFT),
            str_pad($this->day,   2, '0', STR_PAD_LEFT),
        ]);
    }

    public static function now($timezone)
    {
        return new static(now($timezone));
    }
}
