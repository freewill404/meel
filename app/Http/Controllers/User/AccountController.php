<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Support\Enums\Timezones;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    public function index()
    {
        return view('account.index', [
            'emailSchedules' => Auth::user()->emailSchedules,
        ]);
    }

    public function settings()
    {
        return view('account.settings', [
            'user'      => Auth::user(),
            'timezones' => Timezones::all(),
        ]);
    }

    public function updateTimezone(Request $request)
    {
        $values = $request->validate([
            'timezone' => Timezones::required(),
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