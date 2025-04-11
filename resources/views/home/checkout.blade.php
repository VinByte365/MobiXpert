@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Order Confirmation</h1>
    
    <div class="row">
        @if(session()->has('cart') && count(session('cart')) > 0)
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Confirm Your Order</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Please review your order details before confirming
                    </div>

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ auth()->id() }}">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Confirm Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    @if(session()->has('cart') && count(session('cart')) > 0)
                        <ul class="list-group list-group-flush mb-3">
                            @php $total = 0; @endphp
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity']; @endphp
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">{{ $details['name'] }}</h6>
                                        <small class="text-muted">Qty: {{ $details['quantity'] }}</small>
                                    </div>
                                    <span class="text-muted">${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="fw-bold">Subtotal</h6>
                            <h6 class="fw-bold">${{ number_format($total, 2) }}</h6>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <h6>Shipping</h6>
                            <h6>Free</h6>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="fw-bold">Total</h5>
                            <h5 class="fw-bold">${{ number_format($total, 2) }}</h5>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Your cart is empty. Please add some products before checkout.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="col-12">
            <div class="alert alert-info">
                Your cart is empty. <a href="{{ route('shop') }}">Continue shopping</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection