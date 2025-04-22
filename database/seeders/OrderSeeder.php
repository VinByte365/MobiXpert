<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Make sure we have users and products first
        $users = User::all();
        $products = Product::all();
        
        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('Skipping order seeding. Need users and products first.');
            return;
        }
        
        // Create 30 orders
        Order::factory(30)->create()->each(function ($order) use ($products) {
            // Add 1-5 products to each order
            $orderProducts = $products->random(rand(1, 5));
            $totalAmount = 0;
            
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;
                
                // Create order line with subtotal
                OrderLine::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal
                ]);
            }
            
            // Update order total
            $order->update(['total_amount' => $totalAmount]);
        });
    }
}