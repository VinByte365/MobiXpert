<?php

namespace Database\Factories;

use App\Models\PriceRange;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceRangeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PriceRange::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $min = $this->faker->numberBetween(100, 5000);
        $max = $this->faker->numberBetween($min + 500, $min + 2000);
        
        return [
            'range_name' => '$' . $min . ' - $' . $max,
            'min_price' => $min,
            'max_price' => $max,
        ];
    }
}