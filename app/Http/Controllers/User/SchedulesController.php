<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeelRequest;
use Illuminate\Support\Facades\Auth;

class SchedulesController extends Controller
{
    public function index()
    {
        return view('schedules.index', [
            'user'      => Auth::user(),
            'schedules' => Auth::user()->schedules->sortBy('next_occurrence'),
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
