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
    
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        
        // Check if user can review this product
        $user = auth()->user();
        $canReview = $user->orders()
            ->where('status', 'delivered')
            ->whereHas('orderLines', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists() && 
            !$user->reviews()->where('product_id', $productId)->exists();
            
        if (!$canReview) {
            return redirect()->route('product.detail', $productId)
                ->with('error', 'You can only review products you have purchased and received.');
        }
        
        return view('reviews.create', compact('product'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
        
        $productId = $request->product_id;
        
        // Check if user can review this product
        $user = auth()->user();
        $canReview = $user->orders()
            ->where('status', 'delivered')
            ->whereHas('orderLines', function($query) use ($productId) {  // Changed from 'orderItems' to 'orderLines'
                $query->where('product_id', $productId);
            })
            ->exists() && 
            !$user->reviews()->where('product_id', $productId)->exists();
            
        if (!$canReview) {
            return redirect()->route('product.detail', $productId)
                ->with('error', 'You can only review products you have purchased and received.');
        }
        
        // Create the review
        $review = new Review();
        $review->product_id = $productId;
        $review->user_id = $user->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();
        
        return redirect()->route('product.detail', $productId)
            ->with('success', 'Your review has been submitted successfully!');
    }
}
