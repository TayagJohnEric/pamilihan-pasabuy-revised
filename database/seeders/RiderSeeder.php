<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiderSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Check if user with this phone number already exists to avoid duplicates
        $existingPhoneNumber = DB::table('users')
            ->where('phone_number', '09331234567')
            ->first();
            
        if ($existingPhoneNumber) {
            $this->command->warn('User with phone number 09331234567 already exists');
            $this->command->info('Skipping seeder to avoid duplicate entries.');
            return;
        }

        // Create rider user
        $riderUserId = DB::table('users')->insertGetId([
            'role' => 'rider',
            'first_name' => 'Mark',
            'last_name' => 'Salvador',
            'email' => 'rider1@example.com',
            'phone_number' => '09331234567',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create rider profile
        DB::table('riders')->insert([
            'user_id' => $riderUserId,  
            'average_rating' => 4.7,
            'total_deliveries' => 120,
            'daily_deliveries' => 5,
            'is_available' => false,
            'verification_status' => 'verified',
            'license_number' => 'LIC123456',
            'vehicle_type' => 'motorcycle',
            'current_latitude' => 15.12345678,
            'current_longitude' => 120.12345678,
            'location_last_updated_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}

