<?php

namespace App\Http\Controllers\User;

use App\Http\Rules\UsableWhen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchedulesController
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('schedules.index', [
            'user' => $user,
            'noSchedulesYet' => $user->email_schedules_created === 0,
            'hasUpcomingSchedules' => $user->schedules->where('next_occurrence', '!=', null)->isNotEmpty(),
        ]);
    }

    public function ended()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('schedules.ended-schedules', [
            'user' => $user,
            'noSchedulesYet' => $user->email_schedules_created === 0,
            'hasEndedSchedules' => $user->schedules->where('next_occurrence', null)->isNotEmpty(),
        ]);
    }

    public function post(Request $request)
    {
        $request->validate([
            'what' => 'required|string|max:255',
            'when' => ['nullable', 'present', 'string', 'max:255', new UsableWhen],
        ]);

        $request->user()->schedules()->create([
            'what' => $request->get('what'),
            'when' => $request->get('when') ?? $request->user()->default_when,
        ]);

        return redirect()->route('user.schedules.ok');
    }

    public function ok()
    {
        return view('schedules.ok');
    }
}
