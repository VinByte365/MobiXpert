<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\PriceRange;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Make sure we have brands and price ranges first
        $brands = Brand::all();
        $priceRanges = PriceRange::all();
        
        if ($brands->isEmpty() || $priceRanges->isEmpty()) {
            $this->command->info('Skipping product seeding. Need brands and price ranges first.');
            return;
        }
        
        // Create some predefined products
        $products = [
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'The latest flagship iPhone with advanced features and powerful performance.',
                'price' => 1299.99,
                'stock_quantity' => 50,
                'brand_id' => $brands->where('name', 'Iphone')->first()->brand_id ?? 1,
                'image_path' => 'products/iphone-15-pro.jpg',
            ],
            [
                'name' => 'Samsung Galaxy S23 Ultra',
                'description' => 'Premium Android smartphone with exceptional camera capabilities and S Pen support.',
                'price' => 1199.99,
                'stock_quantity' => 45,
                'brand_id' => $brands->where('name', 'Samsung')->first()->brand_id ?? 2,
                'image_path' => 'products/samsung-s23.jpg',
            ],
            [
                'name' => 'Xiaomi 13 Pro',
                'description' => 'High-performance smartphone with Leica optics and fast charging.',
                'price' => 899.99,
                'stock_quantity' => 60,
                'brand_id' => $brands->where('name', 'Xiaomi')->first()->brand_id ?? 3,
                'image_path' => 'products/xiaomi-13.jpg',
            ],
            [
                'name' => 'Poco F5 Pro',
                'description' => 'Flagship killer with top-tier specs at a competitive price.',
                'price' => 499.99,
                'stock_quantity' => 75,
                'brand_id' => $brands->where('name', 'Poco')->first()->brand_id ?? 4,
                'image_path' => 'products/poco-f5.jpg',
            ],
            [
                'name' => 'Infinix Note 30 Pro',
                'description' => 'Feature-rich smartphone with large display and long battery life.',
                'price' => 299.99,
                'stock_quantity' => 90,
                'brand_id' => $brands->where('name', 'Infinix')->first()->brand_id ?? 5,
                'image_path' => 'products/infinix-note30.jpg',
            ],
        ];
        
        foreach ($products as $productData) {
            $price = $productData['price'];
            
            // Find appropriate price range
            $priceRange = PriceRange::where('min_price', '<=', $price)
                ->where('max_price', '>=', $price)
                ->first();
                
            if ($priceRange) {
                $productData['price_range_id'] = $priceRange->price_range_id;
                Product::create($productData);
            }
        }
        
        // Create additional random products
        Product::factory(20)->create();
    }
}