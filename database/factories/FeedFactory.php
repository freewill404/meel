<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Feed::class, function (Faker $faker) {
    return [
        'url'            => $faker->unique()->url, // tests rely on these urls being unique.
        'last_polled_at' => now(),
        'emails_sent'    => 0,
    ];
});
