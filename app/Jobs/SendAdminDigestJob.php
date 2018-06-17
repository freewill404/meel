<?php

namespace App\Jobs;

use App\Models\SiteStats;
use App\Models\User;
use App\Support\Enums\UserRole;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class SendAdminDigestJob extends BaseJob implements ShouldQueue
{
    public function handle()
    {
        $lastMonthStart = now()->subMonth(1)->startOfMonth()->setTimeFromTimeString('00:00:00');
        $lastMonthEnd   = now()->subMonth(1)->endOfMonth()->setTimeFromTimeString('23:59:59');

        $siteStats = SiteStats::query()
            ->whereDate('date', '>=', $lastMonthStart)
            ->whereDate('date', '<=', $lastMonthEnd)
            ->get();

        $content = implode("\r\n", [
            'total users:       '.User::count(),
            '',
            'users registered:  '.$siteStats->sum->users_registered,
            'schedules created: '.$siteStats->sum->schedules_created,
            'emails sent:       '.$siteStats->sum->emails_sent,
            'emails not sent:   '.$siteStats->sum->emails_not_sent,
            '',
            'period start: '.$lastMonthStart,
            'period end  : '.$lastMonthEnd
        ]);

        $month = $lastMonthStart->format('F');

        $adminEmails = User::where('role', UserRole::ADMIN)
            ->pluck('email')
            ->flip()
            ->map(function ($name) {
                return 'Admin';
            })
            ->all();

        Mail::raw($content, function (Message $message) use ($month, $adminEmails) {
            $message->to($adminEmails)->subject('Admin Digest for '.$month.' | Meel.me');
        });
    }
}
