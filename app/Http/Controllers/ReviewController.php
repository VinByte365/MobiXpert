<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function create($orderId, $productId)
    {
        $product = Product::findOrFail($productId);
        $user = auth()->user();
    
        // Check if the user owns the order and the order contains the product and is completed
        $order = $user->orders()
            ->where('order_id', $orderId)
            ->where('status', 'completed')
            ->whereHas('orderLines', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->first();
    
        $alreadyReviewed = Review::where('id', $user->id)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->exists();
    
        $canReview = $order && !$alreadyReviewed;
    
        if (!$canReview) {
            return redirect()->route('product.detail', $productId)
                ->with('error', 'You can only review products you have purchased and received for this order.');
        }
    
        return view('reviews.create', compact('product', 'orderId'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'product_id' => 'required|exists:products,product_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
    
        $user = auth()->user();
        $orderId = $request->order_id;
        $productId = $request->product_id;
    
        // Check if the user owns the order and the order contains the product and is completed
        $order = $user->orders()
            ->where('order_id', $orderId)
            ->where('status', 'completed')
            ->whereHas('orderLines', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->first();
    
        $alreadyReviewed = Review::where('id', $user->id)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->exists();
    
        $canReview = $order && !$alreadyReviewed;
    
        if (!$canReview) {
            return redirect()->route('product.detail', $productId)
                ->with('error', 'You can only review products you have purchased and received for this order.');
        }
    
        // Create the review
        $review = new Review();
        $review->product_id = $productId;
        $review->order_id = $orderId;
        $review->id = $user->id; // foreign key to users
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();
    
        return redirect()->route('product.detail', $productId)
            ->with('success', 'Your review has been submitted successfully!');
    }
    
    public function edit($review)
    {
        $review = Review::findOrFail($review);
        
        // Check if the user owns this review
        if ($review->id !== auth()->id()) {
            return redirect()->route('product.detail', $review->product_id)
                ->with('error', 'You can only edit your own reviews.');
        }
        
        $product = Product::findOrFail($review->product_id);
        
        return view('reviews.edit', compact('review', 'product'));
    }
    
    public function update(Request $request, $review)
    {
        $review = Review::findOrFail($review);
        
        // Check if the user owns this review
        if ($review->id !== auth()->id()) {
            return redirect()->route('product.detail', $review->product_id)
                ->with('error', 'You can only edit your own reviews.');
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
        
        // Update the review
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();
        
        return redirect()->route('product.detail', $review->product_id)
            ->with('success', 'Your review has been updated successfully!');
    }
    
    public function destroy($review)
    {
        $review = Review::findOrFail($review);
        
        // Check if the user owns this review
        if ($review->id !== auth()->id()) {
            return redirect()->route('product.detail', $review->product_id)
                ->with('error', 'You can only delete your own reviews.');
        }
        
        $productId = $review->product_id;
        $review->delete();
        
        return redirect()->route('product.detail', $productId)
            ->with('success', 'Your review has been deleted successfully!');
    }
}
