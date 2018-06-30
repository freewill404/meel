<?php

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use App\Mail\Email;
use App\Models\EmailSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EmailScheduleTableSeeder extends Seeder
{
    protected $maximumDaysAgo = 60;

    protected $schedules = [
        'now'                     => 'Seed the database',
        'tomorrow'                => 'Commit my changes',
        'next week'               => 'Clean my shoes',
        'next month at 11:30'     => 'Renew my domain names',
        'in 2 hours'              => 'Take the cake out of the oven',
        'in 3 days'               => 'Go to the market',
        'every week on saturday'  => 'Clean the house',
        'every month on the 15th' => 'Change bedsheets',
        'every year in may'       => 'Make a dentist appointment',
        'daily'                   => 'Use meel.me',
    ];

    public function run()
    {
        Mail::fake();

        User::all()->each(function (User $user) {
            $this->seedEmailSchedules($user);

            $this->runEmailSchedules($user, now());
        });
    }

    protected function seedEmailSchedules(User $user)
    {
        foreach ($this->schedules as $when => $what) {
            Carbon::setTestNow(
                now()->subDays(random_int(1, $this->maximumDaysAgo))->subSeconds(random_int(0, 86400))
            );

            $user->emailSchedules()->create([
                'when' => $when,
                'what' => $what,
            ]);

            Carbon::setTestNow(null);
        }
    }

    protected function runEmailSchedules(User $user, Carbon $realNow)
    {
        /** @var EmailSchedule $schedule */
        $schedule = $user->emailSchedules()->whereNotNull('next_occurrence')->oldest('next_occurrence')->first();

        do {
            Carbon::setTestNow(
                Carbon::parse($schedule->next_occurrence, $user->timezone)->setTimezone('Europe/Amsterdam')
            );

            $user->refresh()->emails_left
                ? EmailSent::dispatch($schedule, new Email($schedule))
                : EmailNotSent::dispatch($schedule);

            $schedule = $user->emailSchedules()->whereNotNull('next_occurrence')->oldest('next_occurrence')->first();
        } while ($realNow->greaterThanOrEqualTo($schedule->next_occurrence));

        Carbon::setTestNow(null);
    }
}
