@extends('layouts.app')

@section('title', 'Order #' . $order->order_id)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_id }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order #{{ $order->order_id }}</h5>
                    <span class="badge 
                        @if($order->status == 'pending') bg-warning text-dark
                        @elseif($order->status == 'cancelled') bg-danger
                        @elseif($order->status == 'completed') bg-success
                        @elseif($order->status == 'delivered') bg-info
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
                    <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
                    @if($order->billing_address)
                        <p><strong>Billing Address:</strong> {{ $order->billing_address }}</p>
                    @endif
                    
                    <h6 class="mt-4 mb-3">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderLines as $line)
                                    <tr>
                                        <td>
                                            <a href="{{ route('product.detail', $line->product_id) }}">
                                                {{ $line->product->name }}
                                            </a>
                                        </td>
                                        <td>${{ number_format($line->price, 2) }}</td>
                                        <td>{{ $line->quantity }}</td>
                                        <td>${{ number_format($line->price * $line->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="fw-bold">Subtotal</h6>
                        <h6 class="fw-bold">${{ number_format($order->total_amount, 2) }}</h6>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Shipping</h6>
                        <h6>Free</h6>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="fw-bold">Total</h5>
                        <h5 class="fw-bold">${{ number_format($order->total_amount, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection