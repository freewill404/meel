<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

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
