<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormatRequest;
use Illuminate\Http\Request;

class RequestFormat extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'when' => 'required|string|max:255',
        ]);

        FormatRequest::create([
            'user_id' => $request->user()->id,
            'format'  => $request->get('when'),
        ]);
    }
}
