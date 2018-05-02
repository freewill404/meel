<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class MeelController extends Controller
{
    public function index()
    {
        return view('meel');
    }
}
