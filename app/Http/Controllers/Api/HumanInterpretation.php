<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Meel\Schedules\ScheduleFormat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HumanInterpretation extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'when' => 'nullable|string|max:255',
        ]);

        $writtenInput = $request->get('when');

        if ($writtenInput === null) {
            return ['valid' => true, 'humanInterpretation' => ''];
        }

        $schedule = new ScheduleFormat($writtenInput, $request->user()->timezone);

        if (! $schedule->isUsableInterpretation()) {
            return ['valid' => false, 'humanInterpretation' => ''];
        }

        $nextOccurrenceString = $schedule->nextOccurrence();

        $dayOfTheWeek = Carbon::parse($nextOccurrenceString)->format('l');

        $humanInterpretation = $schedule->isRecurring()
            ? 'Recurring '.$schedule->getIntervalDescription().', first occurrence at '
            : 'Once, at ';

        return [
            'valid' => true,
            'humanInterpretation' => $humanInterpretation.$nextOccurrenceString.' ('.$dayOfTheWeek.')',
        ];
    }
}
