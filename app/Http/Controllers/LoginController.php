<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     */
    protected function redirectTo(): string
    {
        return '/';
    }

    protected function attemptLogin(Request $request)
    {
        $email = $request->get('email');

        $isConfirmedUser = User::query()
            ->where('email', $email)
            ->where('email_confirmed', true)
            ->first();

        if (! $isConfirmedUser) {
            return false;
        }

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
}
