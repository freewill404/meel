<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class RequestFormat extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'when' => 'required|string|max:255',
        ]);

        Feedback::create([
            'user_id'  => $request->user()->id,
            'feedback' => 'Api format request: '.$request->get('when'),
        ]);
    }
}
