<?php

namespace Tests\Unit\Listeners\Feeds;

use App\Events\Feeds\FeedPolled;
use App\Listeners\Feeds\SendNewFeedEntryEmails;
use App\Mail\FeedEntriesEmail;
use App\Mail\FeedEntryEmail;
use App\Meel\Feeds\FeedEntryCollection;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendNewFeedEntryEmailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_sends_single_feed_entry_emails()
    {
        $feedEntries = $this->getFeedEntries(2);

        [$user, $feed] = $this->createUserAndFeed(false);

        (new SendNewFeedEntryEmails)->handle(
            new FeedPolled($feed, $feedEntries)
        );

        Mail::assertQueued(FeedEntryEmail::class, 2);

        Mail::assertQueued(FeedEntryEmail::class, function (FeedEntryEmail $email) {
            return '2003-06-03 11:39:21' === (string) $email->feedEntry->publishedAt;
        });

        Mail::assertQueued(FeedEntryEmail::class, function (FeedEntryEmail $email) {
            return '2003-05-30 13:06:42' === (string) $email->feedEntry->publishedAt;
        });
    }

    /** @test */
    function it_sends_grouped_feed_entry_emails()
    {
        $feedEntries = $this->getFeedEntries(2);

        [$user, $feed] = $this->createUserAndFeed(true);

        (new SendNewFeedEntryEmails)->handle(
            new FeedPolled($feed, $feedEntries)
        );

        Mail::assertQueued(FeedEntriesEmail::class, 1);

        Mail::assertQueued(FeedEntriesEmail::class, function (FeedEntriesEmail $email) {
            return count($email->feedEntries) === 2 &&
                   '2003-06-03 11:39:21' === (string) $email->feedEntries[0]->publishedAt &&
                   '2003-05-30 13:06:42' === (string) $email->feedEntries[1]->publishedAt;
        });
    }

    /** @test */
    function it_single_entries_are_always_sent_as_a_single_entry_email()
    {
        $feedEntries = $this->getFeedEntries(1);

        [$user, $feed] = $this->createUserAndFeed(true);

        (new SendNewFeedEntryEmails)->handle(
            new FeedPolled($feed, $feedEntries)
        );

        Mail::assertQueued(FeedEntryEmail::class, function (FeedEntryEmail $email) {
            return '2003-06-03 11:39:21' === (string) $email->feedEntry->publishedAt;
        });
    }

    /** @test */
    function it_does_nothing_when_there_are_no_new_entries()
    {
        $feedEntries = $this->getFeedEntries(0);

        [$user, $feed] = $this->createUserAndFeed(true);

        (new SendNewFeedEntryEmails)->handle(
            new FeedPolled($feed, $feedEntries)
        );

        Mail::assertNothingQueued();
    }

    private function createUserAndFeed(bool $groupNewEntries, int $emailsLeft = 100)
    {
        $user = factory(User::class)->create(['emails_left' => $emailsLeft]);

        $user->feeds()->save(
            $feed = factory(Feed::class)->make(['group_new_entries' => $groupNewEntries])
        );

        return [$user, $feed];
    }

    protected function getFeedEntries(int $feedEntriesCount)
    {
        $date = [
            0 => '2003-06-05 12:00:01',
            1 => '2003-06-01 12:00:01',
            2 => '2003-05-28 12:00:01',
            3 => '2003-05-26 12:00:01',
            4 => '2003-05-19 12:00:01',
        ][$feedEntriesCount];

        $this->setTestNow($date);

        $feedEntries = new FeedEntryCollection(
            file_get_contents($this->testFilePath.'feeds/001-rss-2.0.txt')
        );

        $this->assertCount($feedEntriesCount, $feedEntries->entriesSince(now()));

        return $feedEntries;
    }
}
