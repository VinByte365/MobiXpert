<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('home.cart', compact('cart', 'total'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;
        $userId = Auth::id();

        $product = Product::findOrFail($productId);
        
        // Save to database
        $cartItem = Cart::updateOrCreate(
            [
                'id' => $userId,
                'product_id' => $productId
            ],
            [
                'quantity' => $quantity, // Changed from DB::raw to fixed quantity
                'price' => $product->price
            ]
        );

        // Also maintain session cart for guest users
        $cart = Session::get('cart', []);
            
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->product_id,
                'name' => $product->name,
                'price' => $product->price,
                'image_path' => $product->image_path,
                'quantity' => $quantity,
            ];
        }
        
        Session::put('cart', $cart);
        
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $productId = $request->product_id;
        $quantity = $request->quantity;
        
        // Update session cart
        $cart = Session::get('cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }
        
        // Update database cart for authenticated user
        if (Auth::check()) {
            Cart::where('id', Auth::id())
                ->where('product_id', $productId)
                ->update(['quantity' => $quantity]);
        }
        
        return redirect()->route('cart')->with('success', 'Cart updated successfully!');
    }
    
    public function remove($productId)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }
        
        // Remove from database cart for authenticated user
        if (Auth::check()) {
            Cart::where('id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        }
        
        return redirect()->route('cart')->with('success', 'Product removed from cart!');
    }
    
    public function clear()
    {
        // Clear session cart
        Session::forget('cart');
        
        // Clear database cart for authenticated user
        if (Auth::check()) {
            Cart::where('id', Auth::id())->delete();
        }
        
        return redirect()->route('cart')->with('success', 'Cart cleared successfully!');
    }
}