<?php

namespace App\Policies;

use App\Models\EmailSchedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmailSchedulePolicy
{
    use HandlesAuthorization;

    public function update(User $user, EmailSchedule $emailSchedule)
    {
        // Ended email schedules can't be updated.
        if (! $emailSchedule->next_occurrence) {
            return false;
        }

        return $user->id === $emailSchedule->user_id;
    }

    public function delete(User $user, EmailSchedule $emailSchedule)
    {
        return $user->id === $emailSchedule->user_id;
    }
}
