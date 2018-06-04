<?php

use App\Events\EmailSent;
use App\Mail\Email;
use App\Models\EmailSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class EmailScheduleTableSeeder extends Seeder
{
    use WithFaker;

    protected $maximumDaysAgo = 20;

    protected $whens = [
        'now',
        'tomorrow',
        'next week',
        'next month at 11:30',
        'in 2 hours',
        'in 3 days',
        'every week on saturday',
        'every month on the 15th',
        'every year in may',
    ];

    public function run()
    {
        $this->setUpFaker();

        Mail::fake();

        $realNow = now();

        $realNowUnix = $realNow->format('U');

        $this->consoleWrite('Creating EmailSchedules... ');

        User::all()->each(function (User $user) {
            shuffle($this->whens);

            foreach ($this->whens as $when) {
                Carbon::setTestNow(
                    $this->getRandomPastDateTime()
                );

                $user->emailSchedules()->create([
                    'when' => $when,
                    'what' => $this->faker->sentence,
                ]);

                Carbon::setTestNow(null);
            }
        });

        $this->consoleWriteLine('done!');

        $oldestCreatedAt = EmailSchedule::oldest()->first()->created_at;

        Carbon::setTestNow($oldestCreatedAt);

        $this->consoleWriteLine('Simulating <info>'.$totalMinuteDifference = now()->diffInMinutes($realNow).'</info> minutes of cron jobs...');

        while (now()->format('U') < $realNowUnix) {
            EmailSchedule::shouldBeSentNow()
                ->each(function (EmailSchedule $schedule) {
                    $schedule->sendEmail();

                    EmailSent::dispatch($schedule, new Email($schedule));
                });

            Carbon::setTestNow(
                now()->addMinute()
            );

            if (($minuteDifference = now()->diffInMinutes($realNow)) % 1000 === 0 && $minuteDifference !== 0) {
                $this->consoleWriteLine('           '.str_pad($minuteDifference, strlen($totalMinuteDifference), ' '));
            }
        }

        Carbon::setTestNow(null);
    }

    protected function getRandomPastDateTime()
    {
        $randomDays = $this->faker->numberBetween(1, $this->maximumDaysAgo);

        $randomSeconds = $this->faker->numberBetween(0, 86400); // seconds in 24 hours

        return now()
            ->subDays($randomDays)
            ->subSeconds($randomSeconds);
    }

    protected function consoleWriteLine($string)
    {
        $this->command->getOutput()->writeln($string);
    }

    protected function consoleWrite($string)
    {
        $this->command->getOutput()->write($string);
    }
}
