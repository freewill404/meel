<?php

namespace App\Meel;

class RecurringInterpretation
{
    protected $string;

    protected $interval;

    protected $day = false;

    protected $date = false;

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
        // weekly on monday
    }

    protected function interpretMonthlyInterval($string)
    {
        // monthly
        // monthly on the 12th
    }

    protected function interpretYearlyInterval($string)
    {
        // yearly
        // yearly in march
        // yearly on the 12th of march
    }
}
