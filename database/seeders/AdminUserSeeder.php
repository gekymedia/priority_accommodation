<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@priority.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@priority.com',
                'phone' => '0241234567',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        echo "Admin user created!\n";
        echo "Email: admin@priority.com\n";
        echo "Phone: 0241234567\n";
        echo "Password: password123\n";
    }
}

