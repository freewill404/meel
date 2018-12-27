<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchedulesController extends Controller
{
    public function upcoming()
    {
        $upcomingSchedules = Auth::user()
            ->schedules()
            ->whereNotNull('next_occurrence')
            ->orderBy('next_occurrence')
            ->get();

        return ScheduleResource::collection($upcomingSchedules);
    }

    public function ended()
    {
        $endedSchedules = Auth::user()
            ->schedules()
            ->whereNull('next_occurrence')
            ->orderBy('last_sent_at')
            ->get();

        return ScheduleResource::collection($endedSchedules);
    }

    public function put(Request $request, Schedule $schedule)
    {
        $values = $request->validate([
            'what' => 'required|string|max:255',
        ]);

        $schedule->update($values);
    }

    public function delete(Schedule $schedule)
    {
        $schedule->delete();
    }
}
