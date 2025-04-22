<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\PriceRange;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $price = $this->faker->randomFloat(2, 100, 2000);
        
        return [
            'name' => $this->faker->words(3, true) . ' Phone',
            'description' => $this->faker->paragraph(),
            'price' => $price,
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'brand_id' => Brand::inRandomOrder()->first()->brand_id ?? 1,
            'price_range_id' => PriceRange::where('min_price', '<=', $price)
                ->where('max_price', '>=', $price)
                ->inRandomOrder()
                ->first()->price_range_id ?? 1,
            'image_path' => 'products/default-' . $this->faker->numberBetween(1, 5) . '.jpg',
        ];
    }
}