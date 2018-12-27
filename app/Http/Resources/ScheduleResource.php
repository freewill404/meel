<?php

namespace App\Http\Resources;

use App\Models\Schedule;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var Schedule $schedule */
        $schedule = $this->resource;

        $user = $schedule->user;

        return [
            'obfuscatedId' => $schedule->obfuscated_id,
            'when' => $schedule->when,
            'what' => $schedule->what,
            'recurring' => $schedule->is_recurring,
            'timesSent' => $schedule->times_sent,
            'nextOccurrence' => $schedule->next_occurrence ? (string) $schedule->next_occurrence->setTimezone($user->timezone) : null,
            'lastSentAt' => $schedule->last_sent_at ? (string) $schedule->last_sent_at->setTimezone($user->timezone) : null,
        ];
    }
}
