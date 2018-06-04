<?php

namespace App\Http\Rules;

use App\Meel\EmailScheduleFormat;
use Illuminate\Contracts\Validation\Rule;

class UsableWhen implements Rule
{
    public function passes($attribute, $value)
    {
        // A default will be used if the value is "null".
        if ($value === null) {
            return true;
        }

        $scheduleFormat = new EmailScheduleFormat($value);

        return $scheduleFormat->isUsableInterpretation();
    }

    public function message()
    {
        return 'Not usable.';
    }
}
