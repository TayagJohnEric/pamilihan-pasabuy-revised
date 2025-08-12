<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Get all vendors with their market sections
        $vendors = DB::table('vendors')
            ->join('users', 'vendors.user_id', '=', 'users.id')
            ->select('vendors.id as vendor_id', 'vendors.market_section', 'vendors.vendor_name')
            ->get();

        // Get categories for reference
        $categories = DB::table('categories')->get()->keyBy('category_name');

        foreach ($vendors as $vendor) {
            $products = $this->getProductsForVendor($vendor->market_section, $vendor->vendor_name);
            
            foreach ($products as $product) {
                // Get category ID based on product type
                $categoryId = $this->getCategoryId($categories, $product['category']);
                
                // Skip products with invalid categories
                if (!$categoryId) {
                    $this->command->warn("Skipping product '{$product['name']}' - category '{$product['category']}' not found");
                    continue;
                }
                
                DB::table('products')->insert([
                    'vendor_id' => $vendor->vendor_id,
                    'category_id' => $categoryId,
                    'product_name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'unit' => $product['unit'],
                    'is_budget_based' => $product['is_budget_based'],
                    'indicative_price_per_unit' => $product['indicative_price_per_unit'],
                    'image_url' => $product['image_url'],
                    'is_available' => true,
                    'quantity_in_stock' => rand(10, 100),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    /**
     * Get products based on vendor's market section
     */
    private function getProductsForVendor($marketSection, $vendorName): array
    {
        switch ($marketSection) {
            case 'Seafood':
                return [
                    [
                        'name' => 'Fresh Tilapia',
                        'description' => 'Farm-raised fresh tilapia, cleaned and ready to cook',
                        'price' => 180.00,
                        'unit' => 'kg',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/tilapia.jpg',
                        'category' => 'Fish'
                    ],
                    [
                        'name' => 'Bangus (Milkfish)',
                        'description' => 'Fresh bangus from local fish farms',
                        'price' => 220.00,
                        'unit' => 'kg',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/bangus.jpg',
                        'category' => 'Fish'
                    ],
                    [
                        'name' => 'Fresh Shrimp',
                        'description' => 'Medium-sized fresh shrimp, perfect for cooking',
                        'price' => 350.00,
                        'unit' => 'kg',
                        'is_budget_based' => true,
                        'indicative_price_per_unit' => 350.00,
                        'image_url' => 'images/products/shrimp.jpg',
                        'category' => 'Fish'
                    ],
                    [
                        'name' => 'Squid',
                        'description' => 'Fresh squid, cleaned and ready for cooking',
                        'price' => 280.00,
                        'unit' => 'kg',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/squid.jpg',
                        'category' => 'Fish'
                    ]
                ];

            case 'Fruits & Vegetables':
                return [
                    [
                        'name' => 'Organic Tomatoes',
                        'description' => 'Fresh organic tomatoes, perfect for salads and cooking',
                        'price' => 120.00,
                        'unit' => 'kg',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/tomatoes.jpg',
                        'category' => 'Vegetable'
                    ],
                    [
                        'name' => 'Fresh Bananas',
                        'description' => 'Sweet ripe bananas from local farms',
                        'price' => 80.00,
                        'unit' => 'kg',
                        'is_budget_based' => true,
                        'indicative_price_per_unit' => 80.00,
                        'image_url' => 'images/products/bananas.jpg',
                        'category' => 'Fruit'
                    ],
                    [
                        'name' => 'Organic Lettuce',
                        'description' => 'Crisp organic lettuce leaves, perfect for salads',
                        'price' => 60.00,
                        'unit' => 'head',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/lettuce.jpg',
                        'category' => 'Vegetable'
                    ],
                    [
                        'name' => 'Mangoes',
                        'description' => 'Sweet and juicy Philippine mangoes',
                        'price' => 150.00,
                        'unit' => 'kg',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/mangoes.jpg',
                        'category' => 'Fruit'
                    ]
                ];

            case 'Meat':
                return [
                    [
                        'name' => 'Fresh Pork Belly',
                        'description' => 'Premium pork belly cuts, perfect for adobo and lechon kawali',
                        'price' => 320.00,
                        'unit' => 'kg',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/pork-belly.jpg',
                        'category' => 'Meat'
                    ],
                    [
                        'name' => 'Chicken Breast',
                        'description' => 'Fresh boneless chicken breast, ideal for various dishes',
                        'price' => 280.00,
                        'unit' => 'kg',
                        'is_budget_based' => true,
                        'indicative_price_per_unit' => 280.00,
                        'image_url' => 'images/products/chicken-breast.jpg',
                        'category' => 'Meat'
                    ],
                    [
                        'name' => 'Ground Beef',
                        'description' => 'Fresh ground beef, perfect for burgers and pasta sauce',
                        'price' => 450.00,
                        'unit' => 'kg',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/ground-beef.jpg',
                        'category' => 'Meat'
                    ],
                    [
                        'name' => 'Pork Chops',
                        'description' => 'Tender pork chops, ready for grilling or frying',
                        'price' => 380.00,
                        'unit' => 'kg',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/pork-chops.jpg',
                        'category' => 'Meat'
                    ]
                ];

            default:
                // Default products for any other market section
                return [
                    [
                        'name' => 'Sample Product 1',
                        'description' => 'Sample product description',
                        'price' => 100.00,
                        'unit' => 'piece',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/sample1.jpg',
                        'category' => 'Fish' // Default category
                    ],
                    [
                        'name' => 'Sample Product 2',
                        'description' => 'Sample product description',
                        'price' => 150.00,
                        'unit' => 'kg',
                        'is_budget_based' => true,
                        'indicative_price_per_unit' => 150.00,
                        'image_url' => 'images/products/sample2.jpg',
                        'category' => 'Fish'
                    ],
                    [
                        'name' => 'Sample Product 3',
                        'description' => 'Sample product description',
                        'price' => 200.00,
                        'unit' => 'kg',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/sample3.jpg',
                        'category' => 'Fish'
                    ],
                    [
                        'name' => 'Sample Product 4',
                        'description' => 'Sample product description',
                        'price' => 250.00,
                        'unit' => 'piece',
                        'is_budget_based' => false,
                        'indicative_price_per_unit' => null,
                        'image_url' => 'images/products/sample4.jpg',
                        'category' => 'Fish'
                    ]
                ];
        }
    }

    /**
     * Get category ID based on category name
     */
    private function getCategoryId($categories, $categoryName): ?int
    {
        $category = $categories->get($categoryName);
        if (!$category) {
            $this->command->warn("Category '{$categoryName}' not found in database");
            return null;
        }
        return $category->id;
    }
}
