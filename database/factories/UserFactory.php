<?php

use App\Support\Enums\UserRole;
use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'email'               => $faker->unique()->safeEmail,
        'timezone'            => 'Europe/Amsterdam',
        'password'            => '$2y$04$vmM7jNTINfByJhhXXLNdAuLnfE3TEXB9XmAlKwXhrB.mZmK/mEEL.', // secret
        'remember_token'      => str_random(10),
        'email_confirm_token' => null,
        'email_confirmed'     => true,
        'role'                => UserRole::USER,
        'emails_sent'         => 0,
        'emails_not_sent'     => 0,
        'schedules_created'   => 0,
        'emails_left'         => 100,
    ];
});
