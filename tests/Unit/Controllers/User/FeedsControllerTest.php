<?php

namespace Tests\Unit\Controllers\User;

use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_show_a_feed()
    {
        [$user, $feed] = $this->createUserAndFeed();

        $this->actingAs($user)
            ->showFeed($feed)
            ->assertStatus(200);
    }

    /** @test */
    function you_can_only_see_your_own_feeds()
    {
        [$user1, $feed1] = $this->createUserAndFeed();
        [$user2, $feed2] = $this->createUserAndFeed();

        $this->actingAs($user1)
            ->showFeed($feed2)
            ->assertStatus(403);
    }

    /** @test */
    function it_can_update_a_feed()
    {
        [$user, $feed] = $this->createUserAndFeed(true);

        $this->assertSame('2018-03-29 08:00:00', (string) $feed->next_poll_at);

        $this->actingAs($user)
            ->updateFeed($feed, [
                'url'               => 'http://example.com/new-url',
                'when'              => 'every 2 months',
                'group_new_entries' => false,
            ])
            ->assertStatus(302)
            ->assertRedirect(route('user.feeds'));

        $this->assertSame(false, $feed->refresh()->group_new_entries);

        $this->assertSame('http://example.com/new-url', $feed->url);

        $this->assertSame('every 2 months', $feed->when);

        // It has also updated the "next_poll_at" field
        $this->assertSame('2018-05-01 08:00:00', (string) $feed->next_poll_at);
    }

    /** @test */
    function the_new_when_must_be_a_valid_recurring_schedule()
    {
        [$user, $feed] = $this->createUserAndFeed();

        $this->actingAs($user)
            ->updateFeed($feed, [
                'when' => 'tomorrow',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('when');
    }

    /** @test */
    function it_only_updates_feeds_you_own()
    {
        [$user1, $feed1] = $this->createUserAndFeed();
        [$user2, $feed2] = $this->createUserAndFeed();

        $this->actingAs($user1)
            ->updateFeed($feed2, [])
            ->assertStatus(403);
    }

    /** @test */
    function it_can_delete_a_feed()
    {
        [$user, $feed] = $this->createUserAndFeed();

        $this->actingAs($user)
            ->deleteFeed($feed)
            ->assertStatus(302)
            ->assertRedirect(route('user.feeds'));

        $this->assertSame(0, Feed::count());
    }

    /** @test */
    function it_only_deletes_feeds_you_own()
    {
        [$user1, $feed1] = $this->createUserAndFeed();
        [$user2, $feed2] = $this->createUserAndFeed();

        $this->actingAs($user1)
            ->deleteFeed($feed2)
            ->assertStatus(403);

        $this->assertSame(2, Feed::count());
    }

    /** @test */
    function it_can_create_a_feed()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->createFeed([
                'url'               => 'http://meel.me/feed',
                'when'              => 'every 2 months',
                'group_new_entries' => false,
            ])
            ->assertStatus(302);

        $this->assertSame(1, $user->feeds->count());

        $feed = $user->feeds->first();

        $this->assertSame('http://meel.me/feed', $feed->url);

        $this->assertSame('every 2 months', $feed->when);

        // A listener has set the following fields:
        $this->assertSame('2018-05-01 08:00:00', (string) $feed->next_poll_at);
        $this->assertSame((string) secondless_now(), (string) $feed->last_polled_at);
    }

    /** @test */
    function it_can_only_create_a_feed_if_you_have_feeds_left()
    {
        $user = factory(User::class)->create(['max_feeds' => 2]);

        $user->feeds()->saveMany(
            factory(Feed::class, 2)->make()
        );

        $this->actingAs($user)
            ->createFeed([])
            ->assertStatus(403);
    }

    private function createUserAndFeed(bool $groupNewEntries = true)
    {
        $user = factory(User::class)->create();

        $user->feeds()->save(
            $feed = factory(Feed::class)->make(['group_new_entries' => $groupNewEntries])
        );

        return [$user, $feed];
    }

    private function updateFeed($feed, $values)
    {
        return $this->put(route('user.feeds.update', $feed), $values + [
            'url'               => $feed->url,
            'when'              => $feed->when,
            'group_new_entries' => $feed->group_new_entries,
        ]);
    }

    private function createFeed($values)
    {
        return $this->post(route('user.feeds.store'), $values);
    }

    private function showFeed($feed)
    {
        return $this->get(route('user.feeds.show', $feed));
    }

    private function deleteFeed($feed)
    {
        return $this->delete(route('user.feeds.delete', $feed));
    }
}
