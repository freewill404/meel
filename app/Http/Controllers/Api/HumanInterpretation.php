<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Meel\DateTimeDiff;
use App\Meel\When\ScheduleFormat;
use Illuminate\Http\Request;

class HumanInterpretation extends Controller
{
    public function schedule(Request $request)
    {
        [$error, $schedule, $diffString] = $this->interpret($request);

        if ($error) {
            return $error;
        }

        $string = $schedule->recurring()
            ? 'Recurring '.$schedule->intervalDescription().', first occurrence '.$diffString
            : 'Once, '.$diffString;

        return ['valid' => true, 'humanInterpretation' => $string];
    }

    public function feed(Request $request)
    {
        [$error, $schedule, $diffString] = $this->interpret($request);

        if ($error) {
            return $error;
        }

        if (! $schedule->recurring()) {
            return ['valid' => false, 'humanInterpretation' => 'This schedule is not recurring'];
        }

        return [
            'valid' => true,
            'humanInterpretation' => 'Recurring '.$schedule->intervalDescription().', first poll '.$diffString,
        ];
    }

    private function interpret(Request $request)
    {
        $request->validate([
            'when' => 'nullable|string|max:255',
        ]);

        $writtenInput = $request->get('when');

        if ($writtenInput === null) {
            $error = ['valid' => true, 'humanInterpretation' => ''];

            return [$error, null, null];
        }

        $schedule = new ScheduleFormat(
            $now = now($request->user()->timezone),
            $writtenInput
        );

        if (! $schedule->usable()) {
            $error = ['valid' => false, 'humanInterpretation' => ''];

            return [$error, null, null];
        }

        $dateTimeDiff = new DateTimeDiff($now, $schedule->nextOccurrence());

        return [null, $schedule, $dateTimeDiff->diff()];
    }
}
