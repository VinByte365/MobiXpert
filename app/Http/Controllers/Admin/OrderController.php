<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderLines.product'])
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, $order_id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $order = Order::findOrFail($order_id);
        
        // Prevent any status change if already updated once
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order status can only be updated once');
        }

        $order->update(['status' => $request->status]);

        // Send email if status changed to completed or cancelled
        if (in_array($request->status, ['completed', 'cancelled'])) {
            Mail::to($order->user->email)
                ->send(new OrderStatusUpdated($order));
        }

        return back()->with('success', 'Order status updated successfully');
    }
}