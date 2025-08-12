<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Check if users with these phone numbers already exist to avoid duplicates
        $existingPhoneNumbers = DB::table('users')
            ->whereIn('phone_number', ['09201234567', '09181234567', '09301234567', '09401234567'])
            ->pluck('phone_number')
            ->toArray();
            
        if (!empty($existingPhoneNumbers)) {
            $this->command->warn('Users with phone numbers already exist: ' . implode(', ', $existingPhoneNumbers));
            $this->command->info('Skipping seeder to avoid duplicate entries.');
            return;
        }

        // Vendor 1 It sells Fish
        $vendorUserId1 = DB::table('users')->insertGetId([
            'role' => 'vendor',
            'first_name' => 'Lito',
            'last_name' => 'Manalo',
            'email' => 'vendor1@example.com',
            'phone_number' => '09201234567',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('vendors')->insert([
            'user_id' => $vendorUserId1,
            'vendor_name' => 'Lito’s Harvest Basket',
            'stall_number' => 'B12',
            'market_section' => 'Seafood',
            'business_hours' => '6AM - 2PM',
            'public_contact_number' => '09201234567',
            'public_email' => 'info@litosmarket.com',
            'description' => 'We sell the freshest seafood in town!',
            'verification_status' => 'verified',
            'accepts_cod' => true,
            'average_rating' => 4.5,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Vendor 2 it sells Fruits and Vagetables
        $vendorUserId2 = DB::table('users')->insertGetId([
            'role' => 'vendor',
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'email' => 'vendor2@example.com',
            'phone_number' => '09181234567',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('vendors')->insert([
            'user_id' => $vendorUserId2,
            'vendor_name' => 'Maria’s Organic Produce',
            'stall_number' => 'A05',
            'market_section' => 'Fruits & Vegetables',
            'business_hours' => '5AM - 3PM',
            'public_contact_number' => '09181234567',
            'public_email' => 'info@mariasproduce.com',
            'description' => 'Fresh organic fruits and vegetables daily.',
            'verification_status' => 'verified',
            'accepts_cod' => true,
            'average_rating' => 4.8,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Vendor 3 It sells Meats
        $vendorUserId3 = DB::table('users')->insertGetId([
            'role' => 'vendor',
            'first_name' => 'Carlos',
            'last_name' => 'Reyes',
            'email' => 'vendor3@example.com',
            'phone_number' => '09301234567',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('vendors')->insert([
            'user_id' => $vendorUserId3,
            'vendor_name' => 'Reyes Meat Shop',
            'stall_number' => 'C09',
            'market_section' => 'Meat',
            'business_hours' => '6AM - 4PM',
            'public_contact_number' => '09301234567',
            'public_email' => 'info@reyesmeatshop.com',
            'description' => 'Quality beef, pork, and chicken products.',
            'verification_status' => 'verified',
            'accepts_cod' => false,
            'average_rating' => 4.6,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Vendor 4 It sells Fish
        $vendorUserId4 = DB::table('users')->insertGetId([
            'role' => 'vendor',
            'first_name' => 'Ana',
            'last_name' => 'Lopez',
            'email' => 'vendor4@example.com',
            'phone_number' => '09401234567',
            'password' => Hash::make('password123'),
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('vendors')->insert([
            'user_id' => $vendorUserId4,
            'vendor_name' => 'Ana’s Fresh Catch',
            'stall_number' => 'D15',
            'market_section' => 'Seafood',
            'business_hours' => '4AM - 12PM',
            'public_contact_number' => '09401234567',
            'public_email' => 'info@anasfreshcatch.com',
            'description' => 'Fresh seafood caught daily from local waters.',
            'verification_status' => 'verified',
            'accepts_cod' => true,
            'average_rating' => 4.9,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
