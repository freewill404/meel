<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Meel\EmailScheduleFormat;
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

        $emailSchedule = new EmailScheduleFormat($writtenInput);

        if (! $emailSchedule->isUsableInterpretation()) {
            return ['valid' => false, 'humanInterpretation' => ''];
        }

        $humanInterpretation = $emailSchedule->isRecurring()
            ? 'Recurring, at '
            : 'Once, at ';

        return [
            'valid' => true,
            'humanInterpretation' => $humanInterpretation.$emailSchedule->nextOccurrence(),
        ];
    }
}
