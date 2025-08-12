<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if users with these phone numbers already exist to avoid duplicates
        $existingPhoneNumbers = DB::table('users')
            ->whereIn('phone_number', ['09171234567', '09191234567'])
            ->pluck('phone_number')
            ->toArray();
            
        if (!empty($existingPhoneNumbers)) {
            $this->command->warn('Users with phone numbers already exist: ' . implode(', ', $existingPhoneNumbers));
            $this->command->info('Skipping seeder to avoid duplicate entries.');
            return;
        }

        DB::table('users')->insert([
            [
                'role' => 'customer',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'email' => 'customer1@example.com',
                'phone_number' => '09171234567',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'role' => 'customer',
                'first_name' => 'Maria',
                'last_name' => 'Reyes',
                'email' => 'customer2@example.com',
                'phone_number' => '09191234567',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}

