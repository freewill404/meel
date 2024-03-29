<?php

namespace App\Http\Controllers;

use App\Http\Rules\UniqueEmail;
use App\Models\User;
use App\Support\Enums\Timezone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController
{
    public function showRegistrationForm()
    {
        return view('auth.register', [
            'timezones' => Timezone::selectValues(),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', new UniqueEmail],
            'timezone' => Timezone::required(),
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'email' => $request->get('email'),
            'timezone' => $request->get('timezone'),
            'password' => bcrypt($request->get('password')),
            'email_confirm_token' => sha1(str_random(16)),
        ]);

        event(
            new Registered($user)
        );

        return redirect()->route('register.done');
    }

    public function registered()
    {
        return view('auth.registered');
    }

    public function confirm(Request $request)
    {
        $token = $request->get('token');

        if (! $token) {
            return response('invalid token', 400);
        }

        $user = User::query()
            ->where('email_confirmed', false)
            ->where('email_confirm_token', $token)
            ->firstOr(function () {
                abort(400, 'invalid token');
            });

        $user->update([
            'email_confirmed' => true,
            'email_confirm_token' => null,
        ]);

        Auth::guard()->login($user);

        return redirect()->route('home');
    }
}
