<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            'Iphone',
            'Xiaomi',
            'Samsung',
            'Poco',
            'Infinix'
        ];

        foreach ($brands as $brandName) {
            Brand::create([
                'name' => $brandName
            ]);
        }
    }
}