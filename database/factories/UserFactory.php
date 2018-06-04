<?php

use App\Support\Enums\UserRole;
use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'email'               => $faker->unique()->safeEmail,
        'timezone'            => 'Europe/Amsterdam',
        'password'            => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token'      => str_random(10),
        'email_confirm_token' => null,
        'email_confirmed'     => true,
        'role'                => UserRole::USER,
    ];
});
