<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('account.index', [
            'emailSchedules' => $request->user()->emailSchedules,
        ]);
    }
}
