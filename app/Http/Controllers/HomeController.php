<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeelRequest;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return Auth::check() ? view('meel') : view('home');
    }

    public function post(MeelRequest $request)
    {
        dd(
            $request->all()
        );
    }
}
