<?php

namespace App\Http\Controllers\User\Api;

use App\Meel\DateTimeDiff;
use App\Meel\When\ScheduleFormat;
use App\Meel\When\WhenString;
use App\Models\InputLog;
use Illuminate\Http\Request;

class HumanInterpretation
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
        $values = $request->validate([
            'written_input' => 'nullable|string|max:255',
            'source' => 'required|string|max:255',
            'session_id' => 'required|string|max:255',
        ]);

        $writtenInput = $request->get('written_input');

        if ($writtenInput === null) {
            $error = ['valid' => true, 'humanInterpretation' => ''];

            return [$error, null, null];
        }

        $schedule = new ScheduleFormat(
            $now = now($request->user()->timezone), $writtenInput
        );

        InputLog::create($values + [
            'prepared_written_input' => (new WhenString)->prepare($writtenInput),
            'usable' => $schedule->usable(),
            'recurring' => $schedule->recurring(),
            'created_at' => now(),
        ]);

        if (! $schedule->usable()) {
            $error = ['valid' => false, 'humanInterpretation' => ''];

            return [$error, null, null];
        }

        $dateTimeDiff = new DateTimeDiff($now, $schedule->nextOccurrence());

        return [null, $schedule, $dateTimeDiff->diff()];
    }
}
