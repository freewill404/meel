<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeelRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SchedulesController extends Controller
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

    public function post(MeelRequest $request)
    {
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
