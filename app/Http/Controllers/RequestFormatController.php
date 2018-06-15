<?php

namespace App\Http\Controllers;

use App\Models\FormatRequest;
use Illuminate\Http\Request;

class RequestFormatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        return redirect()->route('requestFormat.done');
    }

    public function done()
    {
        return view('request-format.done');
    }
}
