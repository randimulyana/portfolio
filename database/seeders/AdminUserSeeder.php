<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan updateOrCreate agar aman dijalankan berulang kali
        User::updateOrCreate(
            ['email' => 'admin@portfolio.test'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user seeded: admin@portfolio.test / password');
    }
}
