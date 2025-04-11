@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Discover the Latest Mobile Technology</h1>
                <p class="lead mb-4">Explore our wide range of smartphones, tablets, and accessories. Get the best deals on premium brands.</p>
                <a href="{{ route('shop') }}" class="btn btn-light btn-lg">Shop Now</a>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="{{ asset('images/display.png') }}" alt="Latest Smartphone" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center">Featured Products</h2>
                <p class="text-center text-muted">Check out our latest and most popular mobile devices</p>
            </div>
        </div>
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card product-card h-100">
                    <img src="{{ asset('storage/'.$product->image_path) }}" class="card-img-top product-img p-3" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        @if($product->brand)
                        <p class="card-text text-muted">{{ $product->brand->name }}</p>
                        @endif
                        <p class="card-text fw-bold">₱{{ number_format($product->price, 2) }}</p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-grid gap-2">
                            <a href="{{ route('product.detail', $product->product_id) }}" class="btn btn-outline-primary">View Details</a>
                            @if($product->stock_quantity > 0)
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                            </form>
                            @else
                            <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('shop') }}" class="btn btn-outline-primary">View All Products</a>
            </div>
        </div>
    </div>
</section>

<!-- Brands Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center">Shop by Brand</h2>
                <p class="text-center text-muted">Explore products from your favorite brands</p>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($brands as $brand)
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4 text-center">
                <a href="{{ route('shop', ['brand' => $brand->brand_id]) }}" class="text-decoration-none">
                    <div class="card h-100 product-card py-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $brand->name }}</h5>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>


@endsection