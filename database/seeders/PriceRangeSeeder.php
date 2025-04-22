<?php

namespace Database\Seeders;

use App\Models\PriceRange;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PriceRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // First check if we have any products
        $products = Product::all();
        $hasProducts = $products->isNotEmpty();
        
        $priceRanges = [
            [
                'range_name' => 'Budget',
                'min_price' => 0,
                'max_price' => 299.99,
                'product_id' => $hasProducts ? $products->random()->product_id : null
            ],
            [
                'range_name' => 'Mid-Range',
                'min_price' => 300,
                'max_price' => 699.99,
                'product_id' => $hasProducts ? $products->random()->product_id : null
            ],
            [
                'range_name' => 'Flagship',
                'min_price' => 700,
                'max_price' => 1000,
                'product_id' => $hasProducts ? $products->random()->product_id : null
            ]
        ];

        foreach ($priceRanges as $range) {
            PriceRange::create($range);
        }
    }
}