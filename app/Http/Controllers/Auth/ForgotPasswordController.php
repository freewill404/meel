<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');
    }

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
