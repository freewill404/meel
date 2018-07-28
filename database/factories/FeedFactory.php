<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Feed::class, function (Faker $faker) {
    return [
        'url'               => $faker->unique()->url,
        'when'              => 'every day at 08:00',
        'emails_sent'       => 0,
        'group_new_entries' => true,
        'last_poll_error'   => null,
    ];
});
