<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeelRequest;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return Auth::check() ? view('meel') : view('home');
    }

    public function post(MeelRequest $request)
    {
        $request->user()->emailSchedules()->create([
            'what' => $request->get('what'),
            'when' => $request->get('when') ?? $request->user()->default_when,
        ]);

        return redirect()->route('home.success');
    }

    public function success()
    {
        return view('meel-success');
    }
}
