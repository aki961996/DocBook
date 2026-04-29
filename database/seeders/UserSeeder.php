<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['phone' => '919526117719'],
            [
                'name'               => 'Test User',
                'phone_verified_at'  => now(),
            ]
        );

        $this->command->info('Test user created: phone 919526117719');
    }
}