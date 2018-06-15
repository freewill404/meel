<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeelRequest;

class MeelController extends Controller
{
    public function post(MeelRequest $request)
    {
        $request->user()->emailSchedules()->create([
            'what' => $request->get('what'),
            'when' => $request->get('when') ?? $request->user()->default_when,
        ]);

        return redirect()->route('user.meel.ok');
    }

    public function ok()
    {
        return view('meel-ok');
    }
}
