<?php

namespace App\Http\Rules;

use App\Meel\EmailScheduleFormat;
use Illuminate\Contracts\Validation\Rule;

class UsableWhen implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        // If the value is "null", a default will be used
        if ($value === null) {
            return true;
        }

        $scheduleFormat = new EmailScheduleFormat($value);

        return $scheduleFormat->isUsableInterpretation();
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
