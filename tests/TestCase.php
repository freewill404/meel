<?php

namespace Tests;

use App\Models\User;
use App\Support\Enums\UserRole;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Console\Kernel;
use SjorsO\Gobble\Facades\Gobble;
use SjorsO\MocksTime\MocksTime;
use Spatie\Snapshots\MatchesSnapshots;

abstract class TestCase extends BaseTestCase
{
    use MocksTime, MatchesSnapshots;

    protected $testFilePath;

    protected $mailFake = true;

    protected $snapshotDirectory = '';

    public function setUp()
    {
        parent::setUp();

        $this->testFilePath = base_path('tests/Files/');

        $this->setTestNow('2018-03-28 12:00:15');

        if ($this->mailFake) {
            Mail::fake();
        }

       Gobble::fake();
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

    /**
     * @param null $user
     *
     * @return TestCase|$this
     */
    protected function login($user = null)
    {
        $user = $user ?: factory(User::class)->create();

        return $this->actingAs($user);
    }

    /**
     * @param null $user
     *
     * @return TestCase|$this
     */
    protected function adminLogin($user = null)
    {
        $user = $user ?: factory(User::class)->create(['role' => UserRole::ADMIN]);

        if ($user->role !== UserRole::ADMIN) {
            throw new \RuntimeException();
        }

        return $this->login($user);
    }

    /**
     * @param null $user
     *
     * @return TestCase|$this
     */
    protected function apiLogin($user = null)
    {
        $user = $user ?: factory(User::class)->create();

        return $this->actingAs($user, 'api');
    }

    protected function getSnapshotDirectory(): string
    {
        return $this->getFileSnapshotDirectory();
    }

    protected function getFileSnapshotDirectory(): string
    {
        $snapshotDirectory = ltrim($this->snapshotDirectory, '/');

        return base_path('tests/Files/_snapshots_/'.$snapshotDirectory);
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
