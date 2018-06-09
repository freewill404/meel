<?php

namespace Tests;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Snapshots\MatchesSnapshots;
use Illuminate\Contracts\Console\Kernel;

abstract class TestCase extends BaseTestCase
{
    use MatchesSnapshots;

    protected $testFilePath;

    public function setUp()
    {
        parent::setUp();

        $this->testFilePath = base_path('tests/Files/');

        Carbon::setTestNow('2018-03-28 12:00:15');

        Mail::fake();
    }

    protected function tearDown()
    {
        // Almost all logic on the site ignores the seconds of the time. Ignoring the seconds sets them to 0.
        // Having the seconds of "TestNow" set to 0 can cause subtle bugs. For example: if a piece of code
        // (incorrectly) forgets to ignore the seconds, and then uses some code that (correctly) ignores them,
        // comparing the time value of both pieces of code will always be "true" because both the ignored
        // seconds and the TestNow seconds are 0. In production, the seconds are almost never 0 (1/60),
        // and so the comparison is usually "false".
        if (now()->second === 0) {
            $this->fail('Carbon "TestNow" should not have the seconds set to zero!');
        }

        parent::tearDown();
    }

    protected function getSnapshotDirectory(): string
    {
        return $this->testFilePath.'_snapshots_';
    }

    protected function progressTimeInMinutes($minutes = 1)
    {
        Carbon::setTestNow(
            now()->addMinutes($minutes)
        );

        return $this;
    }

    protected function apiLogin($user = null)
    {
        $user = $user ?: factory(User::class)->create();

        return $this->actingAs($user, 'api');
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        Hash::driver('bcrypt')->setRounds(4);

        return $app;
    }
}
