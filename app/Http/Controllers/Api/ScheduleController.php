<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
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
