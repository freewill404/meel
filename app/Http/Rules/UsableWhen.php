<?php

namespace App\Http\Rules;

use App\Meel\When\ScheduleFormat;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UsableWhen implements Rule
{
    public function passes($attribute, $writtenInput)
    {
        // A default will be used if the value is "null".
        if ($writtenInput === null) {
            return true;
        }

        /** @var User $user */
        $user = Auth::user();

        $scheduleFormat = new ScheduleFormat(
            now($user->timezone ?? 'Europe/Amsterdam'),
            $writtenInput
        );

        return $this->usableWhen($scheduleFormat);
    }

    protected function usableWhen(ScheduleFormat $schedule): bool
    {
        return $schedule->usable();
    }

    public function message()
    {
        return 'Not usable.';
    }
}
