<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MoreController extends Controller
{
    public function more()
    {
        return view('more.index', [
           'user' => Auth::user(),
        ]);
    }
}
