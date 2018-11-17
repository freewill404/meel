<?php

namespace App\Http\Controllers;

use App\Models\SiteStats;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return view('schedules.create');
        }

        $stats = SiteStats::select([
                'email_schedules_created',
                'scheduled_emails_sent',
                'feed_emails_sent',
                'feed_polls',
            ])
            ->get();

        return view('home', [
            'schedulesCreated' => $stats->sum->email_schedules_created,
            'emailsSent' => $stats->sum->scheduled_emails_sent + $stats->sum->feed_emails_sent,
            'feedPolls' => $stats->sum->feed_polls,
        ]);
    }
}
