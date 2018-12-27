<?php

namespace App\Http\Controllers\User;

use App\Support\Enums\Timezone;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccountController
{
    public function index()
    {
        return view('account.index', [
            'user' => Auth::user(),
            'timezones' => Timezone::selectValues(),
        ]);
    }

    public function more()
    {
        return view('account.more', [
            'user' => Auth::user(),
        ]);
    }

    public function updateTimezone(Request $request)
    {
        $values = $request->validate([
            'timezone' => Timezone::required(),
        ]);

        $request->user()->update($values);

        Session::flash('setting-status', 'Timezone updated!');

        return back();
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        Auth::logoutOtherDevices(
            $request->get('new_password')
        );

        Session::flash('setting-status', 'Password updated!');

        return back();
    }
}
