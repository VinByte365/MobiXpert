@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Shopping Cart</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(count($cart) > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Cart Items ({{ count($cart) }})</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cart as $productId => $item)
                            <div class="row mb-4 align-items-center">
                                <div class="col-md-2 col-4">
                                    <img src="{{ asset('storage/'.$item['image_path']) }}" alt="{{ $item['name'] }}" class="img-fluid">
                                </div>
                                <div class="col-md-4 col-8">
                                    <h5>{{ $item['name'] }}</h5>
                                    <p class="text-muted mb-0">${{ number_format($item['price'], 2) }}</p>
                                </div>
                                <div class="col-md-3 col-6 mt-3 mt-md-0">
                                    <form action="{{ route('cart.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $productId }}">
                                        <div class="input-group">
                                            <input type="number" name="quantity" class="form-control" value="{{ $item['quantity'] }}" min="1">
                                            <button type="submit" class="btn btn-outline-secondary">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-2 col-6 text-end mt-3 mt-md-0">
                                    <h6>${{ number_format($item['price'] * $item['quantity'], 2) }}</h6>
                                </div>
                                <div class="col-md-1 col-12 text-end mt-3 mt-md-0">
                                    <a href="{{ route('cart.remove', $productId) }}" class="text-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('shop') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                        <a href="{{ route('cart.clear') }}" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-2"></i>Clear Cart
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout') }}" class="btn btn-primary">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted">Looks like you haven't added any products to your cart yet.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-3">Start Shopping</a>
        </div>
    @endif
</div>
@endsection