@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="card">
                <img src="{{ asset('storage/'.$product->image_path) }}" class="img-fluid" alt="{{ $product->name }}">
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="col-lg-7">
            <h1 class="mb-2">{{ $product->name }}</h1>
            @if($product->brand)
            <p class="text-muted mb-3">Brand: {{ $product->brand->name }}</p>
            @endif
            
            <h2 class="text-primary mb-3">₱{{ number_format($product->price, 2) }}</h2>
            
            <div class="mb-4">
                <p>{{ $product->description }}</p>
            </div>
            
            <div class="mb-4">
                <p class="mb-1"><strong>Availability:</strong> 
                    @if($product->stock_quantity > 0)
                        <span class="text-success">In Stock ({{ $product->stock_quantity }} available)</span>
                    @else
                        <span class="text-danger">Out of Stock</span>
                    @endif
                </p>
                @if($product->priceRange)
                <p class="mb-1"><strong>Price Range:</strong> {{ $product->priceRange->name }}</p>
                @endif
            </div>
            
            @if($product->stock_quantity > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="quantity" class="col-form-label">Quantity:</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock_quantity }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                </form>
            @endif
            
            <!-- Rating summary -->
            @if(isset($product->reviews) && count($product->reviews) > 0)
            <div class="mb-4">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <span class="h3 text-warning">{{ number_format($product->reviews->avg('rating'), 1) }}</span>
                        <span class="text-muted">/ 5</span>
                    </div>
                    <div>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($product->reviews->avg('rating')))
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                        <div class="text-muted small">{{ count($product->reviews) }} reviews</div>
                    </div>
                </div>
            </div>
            @endif
           
        </div>
    </div>
    
    <!-- Product Description -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Product Description</h5>
                </div>
                <div class="card-body">
                    <p>{{ $product->description }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Customer Reviews</h5>
                    @if(auth()->check() && $canReview)
                        <a href="{{ route('reviews.create', $product->product_id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-star me-1"></i> Write a Review
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if(isset($product->reviews) && count($product->reviews) > 0)
                        @foreach($product->reviews as $review)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ $review->user->name }}</strong>
                                        <div class="text-muted small">{{ $review->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        @if(auth()->check() && $review->id === auth()->id())
                                            <div class="dropdown ms-2">
                                                <button class="btn btn-link text-dark p-0" type="button" id="reviewMenu{{ $review->review_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="reviewMenu{{ $review->review_id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('reviews.edit', $review->review_id) }}">Edit</a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No reviews yet for this product.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if(count($relatedProducts) > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Related Products</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card product-card h-100">
                        <img src="{{ asset('storage/'.$relatedProduct->image_path) }}" class="card-img-top product-img p-3" alt="{{ $relatedProduct->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                            @if($relatedProduct->brand)
                            <p class="card-text text-muted">{{ $relatedProduct->brand->name }}</p>
                            @endif
                            <p class="card-text fw-bold">${{ number_format($relatedProduct->price, 2) }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('product.detail', $relatedProduct->product_id) }}" class="btn btn-outline-primary">View Details</a>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $relatedProduct->product_id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection