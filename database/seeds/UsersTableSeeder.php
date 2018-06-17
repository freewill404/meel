<?php

use App\Models\User;
use App\Support\Enums\UserRole;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'email'           => 'test@example.com',
            'role'            => UserRole::ADMIN,
            'email_confirmed' => true,
            'timezone'        => 'Europe/Amsterdam',
            'password'        => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token'  => 'REMEMBER_TOKEN',
        ]);

        User::create([
            'email'           => 'customer@example.com',
            'email_confirmed' => true,
            'timezone'        => 'Europe/London',
            'password'        => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token'  => 'ANOTHER_REMEMBER_TOKEN',
        ]);

        User::create([
            'email'           => 'stranger@example.com',
            'email_confirmed' => true,
            'timezone'        => 'Asia/Shanghai',
            'password'        => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token'  => 'YET_ANOTHER_REMEMBER_TOKEN',
        ]);
    }
}
