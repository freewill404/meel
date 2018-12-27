<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController
{
    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        return view('auth.request-password-reset');
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return redirect()->route('password.requested');
    }

    public function requestedPassword()
    {
        return view('auth.requested-password');
    }
}
