<?php

namespace App\Jobs\Diagnostic;

use App\Jobs\BaseJob;
use App\Models\Feedback;
use App\Models\User;
use App\Support\Enums\UserRole;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendAdminAlertJob extends BaseJob implements ShouldQueue
{
    public function handle()
    {
        $lines = [];

        $this->maybeAppendFailedJobs($lines);

        $this->maybeAppendErrorLogSize($lines);

        $this->maybeAppendFeedbackCount($lines);

        if (count($lines) === 0) {
            return;
        }

        $adminEmails = User::where('role', UserRole::ADMIN)
            ->pluck('email')
            ->flip()
            ->map(function ($name) {
                return 'Admin';
            })
            ->all();

        Mail::raw(implode("\r\n", $lines), function (Message $message) use ($adminEmails) {
            $message->to($adminEmails)->subject('Admin Alert ('.now()->format('Y-m-d').') | Meel.me');
        });
    }

    protected function maybeAppendFailedJobs(array &$lines)
    {
        $failedJobCount = DB::table('failed_jobs')->count();

        if ($failedJobCount) {
            $lines[] = 'There are '.$failedJobCount.' failed jobs!';
        }
    }

    protected function maybeAppendErrorLogSize(array &$lines)
    {
        $logFilePath = storage_path('logs/laravel.log');

        $errorLogSize = file_exists($logFilePath) ? filesize($logFilePath) : 0;

        if ($errorLogSize) {
            $lines[] = 'Error log is '.$errorLogSize.' bytes big!';
        }
    }

    protected function maybeAppendFeedbackCount(array &$lines)
    {
        $feedbackCount = Feedback::count();

        if ($feedbackCount) {
            $lines[] = 'New feedback! ('.$feedbackCount.'x)';
        }
    }
}
