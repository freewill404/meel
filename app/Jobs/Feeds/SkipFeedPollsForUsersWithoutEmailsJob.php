<?php

namespace App\Jobs\Feeds;

use App\Jobs\BaseJob;
use App\Models\Feed;
use App\Models\User;

class SkipFeedPollsForUsersWithoutEmailsJob extends BaseJob
{
    public function handle()
    {
        $usersWithoutEmailsLeft = User::where('emails_left', 0)->pluck('id');

        // We don't poll feeds from users that don't have emails left. This
        // causes feed items they missed to pile up. This job prevents
        // users from receiving a potentially huge amount of emails
        // as soon as they purchase more emails.
        Feed::whereIn('user_id', $usersWithoutEmailsLeft)
            ->update(['last_polled_at' => now()]);
    }
}
