<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'total_amount' => $this->faker->randomFloat(2, 50, 2000),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }
}