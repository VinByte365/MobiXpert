<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
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
            $this->command->info('Skipping review seeding. Need users and products first.');
            return;
        }
        
        // Create reviews for products
        foreach ($products as $product) {
            // Each product gets 0-5 reviews
            $reviewCount = rand(0, 5);
            
            // Get random users to write reviews
            $reviewers = $users->random(min($reviewCount, $users->count()));
            
            foreach ($reviewers as $user) {
                // Find an order for this user that contains this product and is completed
                $order = Order::where('id', $user->id)
                    ->where('status', 'completed')
                    ->whereHas('orderLines', function($query) use ($product) {
                        $query->where('product_id', $product->product_id);
                    })
                    ->inRandomOrder()
                    ->first();
                
                if (!$order) {
                    continue; // Skip if no such order exists
                }
                
                // Check if user already reviewed this product for this order
                $existingReview = Review::where('product_id', $product->product_id)
                    ->where('id', $user->id)
                    ->where('order_id', $order->order_id)
                    ->first();
                
                if (!$existingReview) {
                    Review::create([
                        'product_id' => $product->product_id,
                        'order_id' => $order->order_id,
                        'id' => $user->id,
                        'rating' => rand(3, 5), // Slightly biased toward positive reviews
                        'comment' => $this->getRandomReviewComment(),
                    ]);
                }
            }
        }
        
        // Add some negative reviews too
        for ($i = 0; $i < 10; $i++) {
            $product = $products->random();
            $user = $users->random();
            $order = Order::where('id', $user->id)
                ->where('status', 'completed')
                ->whereHas('orderLines', function($query) use ($product) {
                    $query->where('product_id', $product->product_id);
                })
                ->inRandomOrder()
                ->first();
            
            if (!$order) {
                continue;
            }
            
            // Check if user already reviewed this product for this order
            $existingReview = Review::where('product_id', $product->product_id)
                ->where('id', $user->id)
                ->where('order_id', $order->order_id)
                ->first();
            
            if (!$existingReview) {
                Review::create([
                    'product_id' => $product->product_id,
                    'order_id' => $order->order_id,
                    'id' => $user->id,
                    'rating' => rand(1, 2), // Negative reviews
                    'comment' => $this->getNegativeReviewComment(),
                ]);
            }
        }
    }
    
    /**
     * Get a random positive review comment.
     *
     * @return string
     */
    private function getRandomReviewComment()
    {
        $comments = [
            'Great product! Exactly what I was looking for.',
            'The quality is excellent. Very satisfied with my purchase.',
            'Fast delivery and the product works perfectly.',
            'Highly recommended. Will buy again.',
            'Amazing value for the price. Love it!',
            'This phone exceeded my expectations. The camera is fantastic!',
            'Battery life is impressive. Lasts all day with heavy use.',
            'The display is crystal clear and vibrant.',
            'Very user-friendly interface. Easy to set up.',
            'Sleek design and feels premium in the hand.',
        ];
        
        return $comments[array_rand($comments)];
    }
    
    /**
     * Get a random negative review comment.
     *
     * @return string
     */
    private function getNegativeReviewComment()
    {
        $comments = [
            'Not worth the price. Disappointed with the quality.',
            'Battery drains too quickly. Would not recommend.',
            'Had issues with the screen after just a few weeks.',
            'The camera quality is not as advertised.',
            'Too slow for my needs. Will be returning it.',
            'Customer service was unhelpful when I had problems.',
            'Overheats during normal use.',
            'The build quality feels cheap.',
            'Software has too many bugs.',
            'Not compatible with my accessories as claimed.',
        ];
        
        return $comments[array_rand($comments)];
    }
}