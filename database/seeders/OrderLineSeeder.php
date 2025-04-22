<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Make sure we have orders and products first
        $orders = Order::all();
        $products = Product::all();
        
        if ($orders->isEmpty() || $products->isEmpty()) {
            $this->command->info('Skipping order line seeding. Need orders and products first.');
            return;
        }
        
        // For each order, create 1-5 order lines
        foreach ($orders as $order) {
            // Skip if order already has order lines
            if ($order->orderLines()->count() > 0) {
                continue;
            }
            
            // Add 1-5 products to each order
            $orderProducts = $products->random(rand(1, 5));
            $totalAmount = 0;
            
            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;
                
                // Create order line
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
        }
    }
}