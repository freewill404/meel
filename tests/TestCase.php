<?php

namespace Tests;

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

        Carbon::setTestNow('2018-03-28 12:00:00');

        Mail::fake();
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

    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        Hash::driver('bcrypt')->setRounds(4);

        return $app;
    }
}
