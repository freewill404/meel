<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FormatRequest;
use Illuminate\Http\Request;

class RequestFormatController extends Controller
{
    public function index()
    {
        return view('request-format.index');
    }

    public function post(Request $request)
    {
        $request->validate([
            'format' => 'required|string|max:255',
        ]);

        FormatRequest::create([
            'user_id' => $request->user()->id,
            'format'  => $request->get('format'),
        ]);

        return redirect()->route('user.requestFormat.done');
    }

    public function done()
    {
        return view('request-format.done');
    }
}
