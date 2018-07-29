<?php

namespace App\Jobs\Diagnostic;

use App\Jobs\BaseJob;
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
            'total users: '.User::count(),
            'users registered this month: '.$siteStats->sum->users_registered,
            '',
            'email schedules created:   '.$siteStats->sum->email_schedules_created,
            'scheduled emails sent:     '.$siteStats->sum->scheduled_emails_sent,
            'scheduled emails not sent: '.$siteStats->sum->scheduled_emails_not_sent,
            '',
            'feeds created:        '.$siteStats->sum->feeds_created,
            'feed polls:           '.$siteStats->sum->feed_polls,
            'feed emails sent:     '.$siteStats->sum->feed_emails_sent,
            'feed emails not sent: '.$siteStats->sum->feed_emails_not_sent,
            '',
            'total emails sent: '.$siteStats->sum->emails_sent,
            'total emails sent: '.$siteStats->sum->emails_not_sent,
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
