<?php

use App\Meel\When\ScheduleFormat;
use App\Meel\When\WhenString;
use App\Models\InputLog;
use Illuminate\Database\Seeder;

class InputLogsTableSeeder extends Seeder
{
    public function run()
    {
        $this->createInputLog([
            'e',
            'every',
            'every day',
            'every day at',
            'every day at 1',
            'every day at 1:00',
            'every day at 13:00',
        ]);

        $this->createInputLog([
            'in j',
            'in jan',
        ]);

        $this->createInputLog([
            'tomo',
            'tomorrow',
            'tomorrow at',
            'tomorrow at 17',
            'tomorrow at 17:00',
        ]);
    }

    private function createInputLog($values)
    {
        $sessionId = random_int(9999, 9999999);

        $source = collect(['schedule', 'feed'])->random();

        foreach ($values as $value) {
            $schedule = new ScheduleFormat(now(), $value);

            InputLog::create([
                'session_id' => $sessionId,
                'source' => $source,
                'written_input' => $value,
                'prepared_written_input' => (new WhenString)->prepare($value),
                'usable' => $schedule->usable(),
                'recurring' => $schedule->recurring(),
                'created_at' => now(),
            ]);
        }
    }
}
