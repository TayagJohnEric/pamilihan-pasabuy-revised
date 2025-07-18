<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $now = Carbon::now();

        DB::table('categories')->insert([
            [
                'category_name' => 'Fish',
                'description' => 'Fresh fish and seafood items.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_name' => 'Meat',
                'description' => 'Various types of meat like pork, beef, and chicken.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_name' => 'Vegetable',
                'description' => 'Fresh vegetables sourced from local farms.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_name' => 'Fruit',
                'description' => 'Seasonal and tropical fruits.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
        
    }

