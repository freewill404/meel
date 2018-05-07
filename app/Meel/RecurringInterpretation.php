<?php

namespace App\Meel;

use App\Support\Enums\Days;
use App\Support\Enums\Intervals;
use App\Support\Enums\Months;

class RecurringInterpretation
{
    protected $string;

    protected $interval = false;

    protected $dayOfTheWeek = false;

    protected $dateOfTheMonth = false;

    protected $monthOfTheYear = false;

    public function __construct(string $string)
    {
        $this->string = $string;

        if (strpos($string, 'weekly') !== false) {
            $this->interpretWeeklyInterval($string);
        } elseif (strpos($string, 'monthly') !== false) {
            $this->interpretMonthlyInterval($string);
        } elseif (strpos($string, 'yearly') !== false) {
            $this->interpretYearlyInterval($string);
        }
    }

    protected function interpretWeeklyInterval($string)
    {
        $this->interval = Intervals::WEEKLY;

        // weekly on wednesday
        $this->dayOfTheWeek = preg_match('/weekly on (\w+)/', $string, $matches)
            ? $matches[1]
            : Days::MONDAY;
    }

    protected function interpretMonthlyInterval($string)
    {
        $this->interval = Intervals::MONTHLY;

        // monthly on the 12th
        $this->dateOfTheMonth = preg_match('/monthly on the (\d+)/', $string, $matches)
            ? $matches[1]
            : 1;
    }

    protected function interpretYearlyInterval($string)
    {
        $this->interval = Intervals::YEARLY;

        $this->dateOfTheMonth = 1;

        $this->monthOfTheYear = Months::JANUARY;

        // yearly on the 12th of march
        if (preg_match('/yearly on the (\d+)\w+ of (\w+)/', $string, $matches)) {
            $this->dateOfTheMonth = $matches[1];

            $this->monthOfTheYear = $matches[2];
        } elseif (preg_match('/yearly in (\w+) on the (\d+)/', $string, $matches)) {
            $this->dateOfTheMonth = $matches[2];

            $this->monthOfTheYear = $matches[1];
        } elseif (preg_match('/yearly in (\w+)/', $string, $matches)) {
            $this->monthOfTheYear = $matches[1];
        }
    }

    public function getDayOfTheWeek()
    {
        if ($this->dayOfTheWeek === false) {
            return false;
        }

        return Days::has($this->dayOfTheWeek) ? $this->dayOfTheWeek : Days::MONDAY;
    }

    public function getDateOfTheMonth()
    {
        if ($this->dateOfTheMonth === false) {
            return false;
        }

        return $this->dateOfTheMonth >= 1 && $this->dateOfTheMonth <= 31
            ? (int) $this->dateOfTheMonth
            : 1;
    }

    public function getMonthOfTheYear()
    {
        if ($this->monthOfTheYear === false) {
            return false;
        }

        return Months::has($this->monthOfTheYear) ? $this->monthOfTheYear : Months::JANUARY;
    }

    public function getInterval()
    {
        return $this->interval;
    }
}
