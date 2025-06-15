<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User; // Ensure this path matches your User model's namespace

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'role' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'phone_number' => '1234567890',
            'password' => Hash::make('password123'), // Always hash passwords!
            'profile_image_url' => null,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'is_active' => true,
            'last_login_at' => now(),
        ]);
    }
}
