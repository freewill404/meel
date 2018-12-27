<?php

use App\Models\Feed;
use App\Models\User;
use Illuminate\Database\Seeder;

class FeedsTableSeeder extends Seeder
{
    public function run()
    {
        if (! $this->command->confirm('Seed feeds?', true)) {
            return;
        }

        User::each(function (User $user) {
            $user->feeds()->saveMany([
                factory(Feed::class)->make([
                    'emails_sent' => 0,
                    'group_new_entries' => true,
                    'when' => null
                ]),

                factory(Feed::class)->make([
                    'emails_sent' => 5,
                    'group_new_entries' => false,
                    'when' => 'every day at 8:00'
                ]),

                factory(Feed::class)->make([
                    'emails_sent' => 10,
                    'group_new_entries' => false,
                    'when' => 'every sunday'
                ]),

                factory(Feed::class)->make([
                    'emails_sent' => 25,
                    'group_new_entries' => true,
                    'when' => 'yearly in May'
                ]),

                factory(Feed::class)->make([
                    'emails_sent' => 125,
                    'group_new_entries' => false,
                    'when' => 'daily'
                ]),

                factory(Feed::class)->make([
                    'emails_sent' => 0,
                    'group_new_entries' => true,
                    'when' => 'monthly'
                ]),
            ]);
        });
    }
}
