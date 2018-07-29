<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Feed::class, function (Faker $faker) {
    return [
        'url'               => $faker->unique()->url,
        'when'              => 'every day at 08:00',
        'group_new_entries' => true,
        'last_poll_error'   => null,
        'emails_sent'       => 0,

        // Set by an event listener:
        //   next_poll_at
        //   last_polled_at
    ];
});
