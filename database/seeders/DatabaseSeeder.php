<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,            
            BrandSeeder::class,
            PriceRangeSeeder::class,
            ProductSeeder::class,
            CartSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            OrderLineSeeder::class,
        ]);
    }
}
