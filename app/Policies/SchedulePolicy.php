<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Schedule $schedule)
    {
        // Ended email schedules can't be updated.
        if (! $schedule->next_occurrence) {
            return false;
        }

        return $user->id === $schedule->user_id;
    }

    public function delete(User $user, Schedule $schedule)
    {
        return $user->id === $schedule->user_id;
    }
}
