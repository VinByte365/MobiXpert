<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderLineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderLine::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 5);
        $price = $product->price;
        $subtotal = $price * $quantity;

        return [
            'order_id' => Order::inRandomOrder()->first()->order_id ?? Order::factory(),
            'product_id' => $product->product_id,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $subtotal
        ];
    }
}