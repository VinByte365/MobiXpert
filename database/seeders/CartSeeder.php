<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
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
            $this->command->info('Skipping cart seeding. Need users and products first.');
            return;
        }
        
        // Create 20 cart items distributed among users
        foreach ($users as $user) {
            // Add 1-3 random products to each user's cart
            $randomProductCount = rand(1, 3);
            
            for ($i = 0; $i < $randomProductCount; $i++) {
                $randomProduct = $products->random();
                
                // Check if this product is already in user's cart
                $existingCart = Cart::where('id', $user->id)
                    ->where('product_id', $randomProduct->product_id)
                    ->first();
                
                if (!$existingCart) {
                    Cart::create([
                        'id' => $user->id,
                        'product_id' => $randomProduct->product_id,
                        'quantity' => rand(1, 5)
                    ]);
                }
            }
        }
    }
}