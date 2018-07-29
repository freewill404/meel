<?php

use App\Models\User;
use App\Support\Enums\UserRole;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->create([
            'email'          => 'test@example.com',
            'role'           => UserRole::ADMIN,
            'timezone'       => 'Europe/Amsterdam',
            'remember_token' => 'REMEMBER_TOKEN',
        ]);

        factory(User::class)->create([
            'email'          => 'customer@example.com',
            'timezone'       => 'Europe/London',
            'remember_token' => 'ANOTHER_REMEMBER_TOKEN',
        ]);

        factory(User::class)->create([
            'email'          => 'stranger@example.com',
            'timezone'       => 'Asia/Shanghai',
            'remember_token' => 'YET_ANOTHER_REMEMBER_TOKEN',
        ]);
    }
}
