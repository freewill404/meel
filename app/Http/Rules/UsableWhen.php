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

        return $this->usableWhen(
            new ScheduleFormat($value)
        );
    }

    protected function usableWhen(ScheduleFormat $schedule): bool
    {
        return $schedule->isUsableInterpretation();
    }

    public function message()
    {
        return 'Not usable.';
    }
}
