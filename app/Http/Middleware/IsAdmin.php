<?php

namespace App\Http\Middleware;

use App\Support\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, $next)
    {
        $user = Auth::user();

        if (! $user || $user->role !== UserRole::ADMIN) {
            abort(401);
        }

        return $next($request);
    }
}
