<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhenController extends Controller
{
    public function humanInterpretation(Request $request)
    {
        $request->validate([
            'when' => 'nullable|string|max:255',
        ]);

        $writtenInput = $request->get('when');

        if ($writtenInput === null) {
            return ['valid' => true, 'humanInterpretation' => ''];
        }

        dd('TODO!! FINISH WHEN CONTROLLER API');
    }
}
