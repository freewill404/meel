<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Meel\Schedules\ScheduleFormat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HumanInterpretation extends Controller
{
    public function schedule(Request $request)
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

    public function feed(Request $request)
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

        if (! $schedule->isRecurring()) {
            return ['valid' => false, 'humanInterpretation' => 'This schedule is not recurring'];
        }

        $userNow = now($request->user()->timezone);

        $nextOccurrence = Carbon::parse($schedule->nextOccurrence());

        $format = $userNow->year === $nextOccurrence->year
            ? 'l, \t\h\e jS \o\f F \a\t H:i'
            : 'l, \t\h\e jS \o\f F, Y \a\t H:i';

        return [
            'valid' => true,
            'humanInterpretation' => 'Recurring '.$schedule->getIntervalDescription().', first poll on '.$nextOccurrence->format($format),
        ];
    }
}
