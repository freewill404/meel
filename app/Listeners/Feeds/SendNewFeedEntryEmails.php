<?php

namespace App\Listeners\Feeds;

use App\Events\Feeds\FeedEmailNotSent;
use App\Events\Feeds\FeedEmailSent;
use App\Events\Feeds\FeedPolled;
use App\Mail\FeedEntriesEmail;
use App\Mail\FeedEntryEmail;
use App\Models\Feed;

class SendNewFeedEntryEmails
{
    public function handle(FeedPolled $event)
    {
        $newEntries = $event->feedEntries->entriesSince($event->feed->last_polled_at);

        // If a User wants new entries grouped, but there is only
        // one new entry, send it as a single entry email.
        $event->feed->group_new_entries && count($newEntries) > 1
            ? $this->sendGroupedEntriesEmail($event->feed, $newEntries)
            : $this->sendSingleEntryEmails($event->feed, $newEntries);
    }

    protected function sendGroupedEntriesEmail(Feed $feed, array $newEntries)
    {
        $email = new FeedEntriesEmail($feed, $newEntries);

        $feed->user->sendEmail($email);

        FeedEmailSent::dispatch($feed, $email);
    }

    protected function sendSingleEntryEmails(Feed $feed, array $newEntries)
    {
        foreach ($newEntries as $feedEntry) {
            $email = new FeedEntryEmail($feed, $feedEntry);

            // The user can run out of emails while sending multiple emails.
            $feed->user->sendEmail($email)
                ? FeedEmailSent::dispatch($feed, $email)
                : FeedEmailNotSent::dispatch($feed, $email);
        }
    }
}
