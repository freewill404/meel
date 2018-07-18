<?php

namespace App\Http\Rules;

use App\Meel\Schedules\ScheduleFormat;
use Illuminate\Contracts\Validation\Rule;

class UsableWhen implements Rule
{
    public function passes($attribute, $value)
    {
        // A default will be used if the value is "null".
        if ($value === null) {
            return true;
        }

        $scheduleFormat = new ScheduleFormat($value);

        return $scheduleFormat->isUsableInterpretation();
    }

    public function message()
    {
        return 'Not usable.';
    }
}
