<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use PDF;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = session()->get('cart', []);
        
        // Validate cart is not empty
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }

        // Calculate total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create order
        $order = Order::create([
            'id' => $user->id,
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        // Create order lines and deduct quantities
        foreach ($cart as $id => $details) {
            $subtotal = $details['price'] * $details['quantity'];
            
            OrderLine::create([
                'order_id' => $order->order_id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
                'subtotal' => $subtotal
            ]);

            // Deduct product quantity
            Product::where('product_id', $id)
                       ->decrement('stock_quantity', $details['quantity']);
        }

        // Generate PDF receipt
        $pdf = PDF::loadView('pdf.receipt', compact('order'));

        // Send email
        Mail::to($user->email)->send(new OrderConfirmationMail($order, $pdf));

        // Clear both session and database cart
        $request->session()->forget('cart');
        
        if (Auth::check()) {
            Cart::where('id', Auth::id())->delete();
        }
        
        return redirect()->route('orders.show', $order->order_id)
            ->with('success', 'Order placed successfully! Check your email for confirmation.');
    }

    public function show($order_id)
    {
        $order = Order::with(['orderLines.product'])
                     ->where('order_id', $order_id)
                     ->firstOrFail();
        
        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::with('orderLines.product')
                     ->where('id', auth()->id())
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('orders.index', compact('orders'));
    }
}