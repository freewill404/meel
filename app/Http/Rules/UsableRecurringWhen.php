<?php

namespace App\Http\Rules;

use App\Meel\Schedules\ScheduleFormat;

class UsableRecurringWhen extends UsableWhen
{
    protected function usableWhen(ScheduleFormat $schedule): bool
    {
        return $schedule->isUsableInterpretation() && $schedule->isRecurring();
    }
}
