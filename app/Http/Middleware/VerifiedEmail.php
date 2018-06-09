<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class VerifiedEmail
{
    public function handle($request, $next)
    {
        $user = Auth::user();

        if ($user && ! $user->email_confirmed) {
            Auth::logout();

            return response('Email not confirmed', 403);
        }

        return $next($request);
    }
}
