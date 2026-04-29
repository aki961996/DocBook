<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Super admin
        AdminUser::updateOrCreate(
            ['email' => 'superadmin@docbook.in'],
            [
                'name'        => 'Super Admin',
                'password'    => Hash::make('Admin@1234'),
                'role'        => 'super_admin',
                'hospital_id' => null,
            ]
        );

        $this->command->info('Super admin created: superadmin@docbook.in / Admin@1234');
    }
}