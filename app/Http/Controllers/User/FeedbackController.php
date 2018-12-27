<?php

namespace App\Http\Controllers\User;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController
{
    public function index()
    {
        return view('feedback.index');
    }

    public function post(Request $request)
    {
        $request->validate([
            'feedback' => 'required|string|max:5000',
        ]);

        Feedback::create([
            'user_id' => $request->user()->id,
            'feedback' => $request->get('feedback'),
        ]);

        return redirect()->route('user.feedback.done');
    }

    public function done()
    {
        return view('feedback.done');
    }
}
