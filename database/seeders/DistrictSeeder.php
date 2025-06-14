<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            // Near (0-2 km)
            ['name' => 'Santo Rosario (Pob.)', 'distance_km' => 0.00, 'delivery_fee' => 50.00],
            ['name' => 'Del Pilar', 'distance_km' => 0.50, 'delivery_fee' => 50.00],
            ['name' => 'Santa Teresita', 'distance_km' => 0.70, 'delivery_fee' => 50.00],
            ['name' => 'Lourdes', 'distance_km' => 1.00, 'delivery_fee' => 50.00],
            ['name' => 'Juliana', 'distance_km' => 1.20, 'delivery_fee' => 50.00],
            ['name' => 'San Nicolas', 'distance_km' => 1.30, 'delivery_fee' => 50.00],
            ['name' => 'Santo Niño', 'distance_km' => 1.50, 'delivery_fee' => 50.00],
            ['name' => 'Santa Lucia', 'distance_km' => 1.60, 'delivery_fee' => 50.00],

            // Intermediate (2-4 km)
            ['name' => 'Del Rosario', 'distance_km' => 2.00, 'delivery_fee' => 50.00],
            ['name' => 'Del Carmen', 'distance_km' => 2.20, 'delivery_fee' => 50.00],
            ['name' => 'San Isidro', 'distance_km' => 2.50, 'delivery_fee' => 50.00],
            ['name' => 'Maimpis', 'distance_km' => 2.70, 'delivery_fee' => 50.00],
            ['name' => 'San Felipe', 'distance_km' => 3.00, 'delivery_fee' => 50.00],
            ['name' => 'Pandaras', 'distance_km' => 3.20, 'delivery_fee' => 50.00],
            ['name' => 'Quebiawan', 'distance_km' => 3.50, 'delivery_fee' => 50.00],
            ['name' => 'San Pedro Cutud', 'distance_km' => 3.80, 'delivery_fee' => 50.00],
            ['name' => 'Alasas', 'distance_km' => 3.90, 'delivery_fee' => 50.00],
            ['name' => 'Magliman', 'distance_km' => 4.00, 'delivery_fee' => 50.00],
            ['name' => 'San Juan', 'distance_km' => 4.20, 'delivery_fee' => 50.00],

            // Mid to Far (4-6 km)
            ['name' => 'Dela Paz Sur', 'distance_km' => 4.50, 'delivery_fee' => 50.00],
            ['name' => 'Dela Paz Norte', 'distance_km' => 4.70, 'delivery_fee' => 50.00],
            ['name' => 'Calulut', 'distance_km' => 5.00, 'delivery_fee' => 50.00],
            ['name' => 'Sindalan', 'distance_km' => 5.20, 'delivery_fee' => 50.00],
            ['name' => 'Malpitic', 'distance_km' => 5.50, 'delivery_fee' => 50.00],
            ['name' => 'Malino', 'distance_km' => 5.70, 'delivery_fee' => 50.00],
            ['name' => 'Saguin', 'distance_km' => 5.80, 'delivery_fee' => 50.00],
            ['name' => 'Dolores', 'distance_km' => 6.00, 'delivery_fee' => 50.00],
            ['name' => 'San Agustin', 'distance_km' => 6.20, 'delivery_fee' => 50.00],
            ['name' => 'San Jose', 'distance_km' => 6.50, 'delivery_fee' => 50.00],
            ['name' => 'Baliti', 'distance_km' => 6.70, 'delivery_fee' => 50.00],

            // Outer Ring (6.8 km and beyond)
            ['name' => 'Bulaon', 'distance_km' => 7.00, 'delivery_fee' => 50.00],
            ['name' => 'Pulung Bulo', 'distance_km' => 7.30, 'delivery_fee' => 50.00],
            ['name' => 'Panipuan', 'distance_km' => 7.80, 'delivery_fee' => 50.00],
            ['name' => 'Telabastagan', 'distance_km' => 8.00, 'delivery_fee' => 50.00],
            ['name' => 'Lara', 'distance_km' => 8.50, 'delivery_fee' => 50.00],
        ];

        DB::table('districts')->insert($districts);
    }
    
}
