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

    public function addYears($int)
    {
        $this->year += $int;

        return $this;
    }

    public function isAfterToday($timezone = null): bool
    {
        return $this->isAfter(now($timezone));
    }

    public function isAfter($dateTime)
    {
        if (! $dateTime instanceof Carbon) {
            $dateTime = Carbon::parse($dateTime);
        }

        [$year, $month, $day] = explode('-', $dateTime->toDateString());

        return $this->compareTo($year, $month, $day) === -1;
    }

    public function isToday($timezone = null): bool
    {
        return $this->isSame(
            now($timezone)->toDateString()
        );
    }

    public function isSame($dateTime): bool
    {
        if (! $dateTime instanceof Carbon) {
            $dateTime = Carbon::parse($dateTime);
        }

        [$y, $m, $d] = explode('-', $dateTime->toDateString());

        return $this->compareTo($y, $m, $d) === 0;
    }

    public function isBeforeToday($timezone = null): bool
    {
        return $this->isBefore(now($timezone));
    }

    public function isBefore($dateTime)
    {
        if (! $dateTime instanceof Carbon) {
            $dateTime = Carbon::parse($dateTime);
        }

        [$year, $month, $day] = explode('-', $dateTime->toDateString());

        return $this->compareTo($year, $month, $day) === 1;
    }

    private function compareTo($year, $month, $day)
    {
        if ($year > $this->year) {
            return 1;
        }

        if ($year < $this->year) {
            return -1;
        }

        if ($month > $this->month) {
            return 1;
        }

        if ($month < $this->month) {
            return -1;
        }

        if ($day > $this->day) {
            return 1;
        }

        if ($day < $this->day) {
            return -1;
        }

        return 0;
    }

    public function __toString()
    {
        return implode('-', [
            $this->year,
            str_pad($this->month, 2, '0', STR_PAD_LEFT),
            str_pad($this->day, 2, '0', STR_PAD_LEFT),
        ]);
    }

    public static function now($timezone)
    {
        return new static(
            now($timezone)
        );
    }

    public static function tomorrow($timezone)
    {
        return new static(
            now($timezone)->addDays(1)
        );
    }
}
