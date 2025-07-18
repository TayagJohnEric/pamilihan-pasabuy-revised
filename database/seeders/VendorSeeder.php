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

        // Create vendor user
        $vendorUserId = DB::table('users')->insertGetId([
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

        // Create vendor profile
        DB::table('vendors')->insert([
            'user_id' => $vendorUserId,
            'vendor_name' => 'Lito’s Fresh Market',
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
    }
}  

