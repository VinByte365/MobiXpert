<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\PriceRange;
use Illuminate\Http\Request;
use App\Facades\Cart; // Changed from App\Models\Cart to App\Facades\Cart

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('brand')
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        $brands = Brand::all();
        
        return view('home.index', compact('featuredProducts', 'brands'));
    }
    
    public function shop(Request $request)
    {
        $query = Product::with('brand', 'priceRange')->where('stock_quantity', '>', 0);
        
        // Filter by brand
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
        }
        
        // Filter by price range
        if ($request->has('price_range') && $request->price_range != '') {
            $query->where('price_range_id', $request->price_range);
        }
        
        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Sort products
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12);
        $brands = Brand::all();
        $priceRanges = PriceRange::all();
        
        return view('home.shop', compact('products', 'brands', 'priceRanges'));
    }
    
    public function productDetail($id)
    {
        $product = Product::with(['brand', 'priceRange', 'reviews.user'])->findOrFail($id);
        
        // Get related products (same brand or price range)
        $relatedProducts = Product::where('product_id', '!=', $id)
            ->where(function($query) use ($product) {
                $query->where('brand_id', $product->brand_id)
                      ->orWhere('price_range_id', $product->price_range_id);
            })
            ->limit(4)
            ->get();
        
        // Check if user can review this product
        $canReview = false;
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if user has purchased this product and it was delivered
            $canReview = $user->orders()
                ->where('status', 'delivered')
                ->whereHas('orderLines', function($query) use ($id) {  // Changed from 'orderItems' to 'orderLines'
                    $query->where('product_id', $id);
                })
                ->exists() && 
                // Check if user hasn't already reviewed this product
                !$user->reviews()->where('product_id', $id)->exists();
        }
        
        return view('home.product-detail', compact('product', 'relatedProducts', 'canReview'));
    }
}
