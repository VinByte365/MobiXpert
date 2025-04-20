<?php

namespace Database\Seeders;

use App\Models\PriceRange;
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
        $priceRanges = [
            [
                'range_name' => 'Budget',
                'min_price' => 0,
                'max_price' => 299.99
            ],
            [
                'range_name' => 'Mid-Range',
                'min_price' => 300,
                'max_price' => 699.99
            ],
            [
                'range_name' => 'Flagship',
                'min_price' => 700,
                'max_price' => 1000
            ]
        ];

        foreach ($priceRanges as $range) {
            PriceRange::create($range);
        }
    }
}